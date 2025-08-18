<?php

namespace Modules\CustomFields\Models;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Field extends Model
{
    use Cloneable, Notifiable, HasFactory;

    protected $table = 'custom_fields_fields';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'code', 'width', 'type', 'required', 'rule', 'enabled', 'location', 'sort', 'show', 'default'];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['fieldTypeOption'];

    public function fieldTypeOption()
    {
        return $this->hasMany('Modules\CustomFields\Models\FieldTypeOption', 'field_id', 'id');
    }

    public function field_values()
    {
        return $this->hasMany('Modules\CustomFields\Models\FieldValue', 'field_id', 'id');
    }

    public function scopeByLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', $location);
    }

    public function scopeByLocations(Builder $query, array $locations): Builder
    {
        return $query->whereIn('location', $locations);
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('custom-fields.fields.edit', $this->id),
            'permission' => 'update-custom-fields-fields',
        ];

        $actions[] = [
            'title' => trans('general.duplicate'),
            'icon' => 'file_copy',
            'url' => route('custom-fields.fields.duplicate', $this->id),
            'permission' => 'create-custom-fields-fields',
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'custom-fields.fields.destroy',
            'permission' => 'delete-custom-fields-fields',
            'model' => $this,
        ];

        return $actions;
    }

    /**
     * A no-op callback that gets fired when a model is cloned and saved to the
     * database
     *
     * @param  Illuminate\Database\Eloquent\Model $src
     * @return void
     */
    public function onCloned($src)
    {
        $this->update(['code' => 'custom_field_' . $this->id]);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\CustomFields\Database\Factories\Field::new();
    }
}
