<?php

namespace Modules\CustomFields\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldCreate;
use Illuminate\Support\Facades\DB;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldTypeOption;

class CreateField extends Job implements ShouldCreate
{
    public function handle()
    {
        $this->prepareRequest();

        DB::transaction(function () {
            $this->model = Field::create($this->request->except('items'));
    
            $this->model->update([
                'code' => 'custom_field_' . $this->model->id,
            ]);
    
            if (isset($this->request->items)) {
                foreach ($this->request->items as $item) {
                    $field_type_option = FieldTypeOption::create([
                        'company_id' => company_id(),
                        'field_id' => $this->model->id,
                        'value' => $item['values'],
                    ]);
    
                    if ($item['is_default'] == 'true') {
                        $this->model->update(['default' => $field_type_option->id]);
                    }
                }
            }
        });

        return $this->model;
    }

    protected function prepareRequest(): void
    {
        $this->request->merge(['code' => '']);

        if ($this->request['sort'] === 'item_custom_fields') {
            $this->request->merge(['sort' => $this->request->sort]);
        } else {
            $this->request->merge(['sort' => $this->request->sort . (! empty($this->request->order) ? '_' : '') . $this->request->order]);
        }

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

        if (! empty($this->request['rule'])) {
            $this->request['rule'] = implode('|', $this->request['rule']);
        }
    }
}
