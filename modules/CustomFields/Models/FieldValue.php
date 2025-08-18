<?php

namespace Modules\CustomFields\Models;

use App\Abstracts\Model;

class FieldValue extends Model
{
    protected $table = 'custom_fields_field_values';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'field_id', 'model_id', 'model_type', 'value'];

    public function field()
    {
        return $this->belongsTo('Modules\CustomFields\Models\Field');
    }

    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Convert amount to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getValueAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        $values = json_decode($value);

        if (is_array($values) && !empty($values)) {
            $value = $values;
        }

        return $value;
    }

    /**
     * Scope record.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecord($query, $model)
    {
        return $query->where('model_id', $model->id)->where('model_type', $this->getModelClass($model));
    }

    protected function getModelClass($model)
    {
        $main_models = array_keys(config('custom-fields'));

        foreach ($main_models as $main_model) {
            if (is_subclass_of($model, $main_model)) {
                return $main_model;
            }
        }

        return get_class($model);
    }
}
