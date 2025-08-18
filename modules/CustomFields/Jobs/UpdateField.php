<?php

namespace Modules\CustomFields\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use Illuminate\Support\Facades\DB;
use Modules\CustomFields\Models\FieldTypeOption;

class UpdateField extends Job implements ShouldUpdate
{
    public function handle()
    {
        $this->prepareRequest();

        DB::transaction(function () {
            $this->model->update($this->request->input());
    
            $values = [];
    
            if (isset($this->request->items)) {
                foreach ($this->request->items as $item) {
                    $values[] = $item['values'];
                }
            }
    
            $options = $this->model->fieldTypeOption;
    
            foreach ($options as $option) {
                if (! in_array($option->value, $values)) {
                    foreach ($this->model->field_values as $field_value) {
                        if ($field_value->value == $option->id) {
                            $field_value->delete();
                        }
                    }
    
                    $option->delete();
                }
            }
    
            if (isset($this->request->items)) {
                $option_values = $options->pluck('value')->toArray();
    
                foreach ($this->request->items as $item) {
                    if (! in_array($item['values'], $option_values)) {
                        $field_type_option = FieldTypeOption::create([
                            'company_id' => company_id(),
                            'field_id' => $this->model->id,
                            'value' => $item['values'],
                        ]);
    
                        if ($item['is_default'] == 'true') {
                            $this->model->update(['default' => $field_type_option->id]);
                        }
                    }
    
                    if (in_array($item['values'], $option_values)) {
                        if ($item['is_default'] == 'true') {
                            $field_type_option = $options->where('value', $item['values'])->first();
    
                            $this->model->update(['default' => $field_type_option->id]);
                        }
                    }
                }
            }
        });

        return $this->model;
    }

    protected function prepareRequest(): void
    {
        if (empty($this->request['width']) && $this->request['type'] != 'textarea') {
            $this->request->merge([
                'width' => '50',
            ]);
        }

        if (empty($this->request['width']) && $this->request['type'] == 'textarea') {
            $this->request->merge([
                'width' => '100',
            ]);
        }

        if ($this->request['sort'] !== 'item_custom_fields') {
            $this->request['sort'] .= '_' . $this->request['order'];
        }

        if ($this->request->has('rule') && ! empty($this->request->input('rule'))) {
            $this->request['rule'] = implode('|', $this->request->input('rule'));
        }

        if (! $this->request->has('rule')) {
            $this->request['rule'] = null;
        }
    }
}