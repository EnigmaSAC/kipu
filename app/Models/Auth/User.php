<?php

namespace App\Models\Auth;

use Akaunting\Sortable\Traits\Sortable;
use App\Notifications\Auth\Reset;
use App\Traits\Media;
use App\Traits\Owners;
use App\Traits\Sources;
use App\Traits\Tenants;
use App\Traits\Users;
use App\Utilities\Date;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable implements HasLocalePreference
{
    use HasFactory,
        HasRelationships,
        LaratrustUserTrait,
        Media,
        Notifiable,
        Owners,
        SearchString,
        SoftDeletes,
        Sortable,
        Sources,
        Tenants,
        Users;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'enabled',
        'landing_page',
        'created_from',
        'created_by'
    ];

    protected $casts = [
        'enabled'           => 'boolean',
        'last_logged_in_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    protected $hidden = ['password', 'remember_token'];

    public $sortable = ['name', 'email', 'enabled'];

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model->setCompanyIds();
        });

        static::saving(function ($model) {
            $model->unsetCompanyIds();
        });

        // Borrado en cascada (soft) de la invitación asociada
        static::deleting(function (User $user) {
            optional($user->invitation)->delete();

            if (method_exists($user, 'isForceDeleting') && $user->isForceDeleting()) {
                optional($user->invitation)->forceDelete();
            }
        });
    }

    public function companies()
    {
        return $this->belongsToMany('App\Models\Common\Company', 'App\Models\Auth\UserCompany');
    }

    public function contact()
    {
        return $this->hasOne('App\Models\Common\Contact', 'user_id', 'id');
    }

    public function dashboards()
    {
        return $this->belongsToMany('App\Models\Common\Dashboard', 'App\Models\Auth\UserDashboard');
    }

    public function invitation(): HasOne
    {
        return $this->hasOne(\App\Models\Auth\UserInvitation::class, 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(role_model_class(), 'App\Models\Auth\UserRole');
    }

    public function getNameAttribute($value)
    {
        return empty($value) ? trans('general.na') : ucfirst($value);
    }

    public function getPictureAttribute($value)
    {
        if (setting('default.use_gravatar', '0') == '1') {
            try {
                $url = 'https://www.gravatar.com/avatar/' . md5(strtolower($this->getAttribute('email'))) . '?size=90&d=404';
                $client = new \GuzzleHttp\Client(['verify' => false]);
                $client->request('GET', $url)->getBody()->getContents();
                $value = $url;
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // ignoramos si no existe gravatar
            }
        }

        if (!empty($value)) {
            return $value;
        } elseif (!$this->hasMedia('picture')) {
            return false;
        }

        return $this->getMedia('picture')->last();
    }

    public function getLastLoggedAttribute($value)
    {
        return !empty($value)
            ? Date::parse($value)->diffForHumans()
            : trans('auth.never');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new Reset($token, $this->email));
    }

    public function scopeCollect($query, $sort = 'name')
    {
        $request = request();
        $search = $request->get('search');
        $request_sort = $request->get('sort');

        $query->usingSearchString($search)->sortable($sort);

        $request->merge(['sort' => $request_sort]);

        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));

        return $query->paginate($limit);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function scopeIsCustomer($query)
    {
        return $query->wherePermissionIs('read-client-portal');
    }

    public function scopeIsNotCustomer($query)
    {
        return $query->wherePermissionIs('read-admin-panel');
    }

    public function scopeIsEmployee($query)
    {
        return $query->whereHasRole('employee');
    }

    public function scopeIsNotEmployee($query)
    {
        return $query->wherePermissionIs('read-admin-panel');
    }

    public function scopeEmail($query, $email)
    {
        return $query->where('email', '=', $email);
    }

    public function setCompanyIds()
    {
        $company_ids = $this->withoutEvents(function () {
            return $this->companies->pluck('id')->toArray();
        });

        $this->setAttribute('company_ids', $company_ids);
    }

    public function unsetCompanyIds()
    {
        $this->offsetUnset('company_ids');
    }

    public function isCustomer()
    {
        return (bool) $this->can('read-client-portal');
    }

    public function isNotCustomer()
    {
        return (bool) $this->can('read-admin-panel');
    }

    public function isEmployee()
    {
        return (bool) $this->hasRole('employee');
    }

    public function isNotEmployee()
    {
        return (bool) !$this->hasRole('employee');
    }

    public function scopeSource($query, $source)
    {
        return $query->where($this->qualifyColumn('created_from'), $source);
    }

    public function scopeIsOwner($query)
    {
        return $query->where($this->qualifyColumn('created_by'), user_id());
    }

    public function scopeIsNotOwner($query)
    {
        return $query->where($this->qualifyColumn('created_by'), '<>', user_id());
    }

    public function ownerKey($owner)
    {
        return $this->isNotOwnable() ? 0 : $this->created_by;
    }

    public function preferredLocale()
    {
        return $this->locale;
    }

    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.show'),
            'icon' => 'visibility',
            'url' => route('users.show', $this->id),
            'permission' => 'read-auth-users',
            'attributes' => [
                'id' => 'index-line-actions-show-user-' . $this->id,
            ],
        ];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('users.edit', $this->id),
            'permission' => 'update-auth-users',
            'attributes' => [
                'id' => 'index-line-actions-edit-user-' . $this->id,
            ],
        ];

        if ($this->hasPendingInvitation()) {
            $actions[] = [
                'title' => trans('general.resend') . ' ' . trans_choice('general.invitations', 1),
                'icon' => 'replay',
                'url' => route('users.invite', $this->id),
                'permission' => 'update-auth-users',
                'attributes' => [
                    'id' => 'index-line-actions-resend-user-' . $this->id,
                ],
            ];
        }

        if (user()->id != $this->id) {
            $actions[] = [
                'type' => 'delete',
                'icon' => 'delete',
                'route' => 'users.destroy',
                'permission' => 'delete-auth-users',
                'attributes' => [
                    'id' => 'index-line-actions-delete-user-' . $this->id,
                ],
                'model' => $this,
            ];
        }

        return $actions;
    }

    protected static function newFactory()
    {
        return \Database\Factories\User::new();
    }
}
