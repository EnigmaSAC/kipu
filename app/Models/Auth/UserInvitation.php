<?php

namespace App\Models\Auth;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInvitation extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_invitations';

    protected $tenantable = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['user_id', 'token', 'expires_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Auth\User::class, 'user_id');
    }
 
    /**
     * Scope a query to only include given token value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeToken($query, $token)
    {
        $query->where('token', $token);
    }
}
