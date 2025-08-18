<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => trans_choice('custom-fields::general.fields', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form
                id="field"
                method="PATCH"
                :route="['custom-fields.fields.update', $field->id]"
                :model="$field">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            :title="trans('general.general')"
                            :description="trans('custom-fields::general.section-head.general')" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            :title="trans_choice('general.types', 1)"
                            :description="trans('custom-fields::general.section-head.type')" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select
                            name="type"
                            :label="trans_choice('general.types', 1)"
                            :options="$types"
                            change="onChangeType"
                            clear="onClearType"
                            required
                        />

                        <x-form.group.select
                            name="rule"
                            :label="trans('custom-fields::general.form.rule')"
                            dynamicOptions="rules"
                            force-dynamic-option-value
                            v-disabled="! this.form.type"
                            :selected="$selected_validation_rules"
                            change="onChangeRule"
                            multiple
                            not-required
                        />

                        <template v-if="can_type === 'values'">
                            <div class="form-group relative sm:col-span-6">
                                <x-form.label name="items">
                                    {{ trans('custom-fields::general.form.items') }}
                                </x-form.label>

                                <x-table>
                                    <x-table.thead>
                                        <x-table.tr class="flex items-center px-1">
                                            <x-table.th class="w-8/12">
                                                {{ trans('custom-fields::general.form.value') }}
                                            </x-table.th>
                                            <x-table.th class="w-2/12">
                                                {{ trans_choice('general.defaults', 1) . '?' }}
                                            </x-table.th>
                                            <x-table.th class="w-2/12" kind="right">
                                                {{ trans('general.actions') }}
                                            </x-table.th>
                                        </x-table.tr>
                                    </x-table.thead>

                                    <x-table.tbody>
                                        <x-table.tr v-for="(row, index) in items" v-bind:index="index" class="relative flex items-center px-1 border-b" override="class">
                                            <x-table.td class="w-8/12" kind="cursor-none">
                                                <x-form.group.text
                                                    name="values[]"
                                                    v-model="row.values"
                                                    form-group-class="ltr:pr-6 rtl:pl-6" />
                                            </x-table.td>

                                            <x-table.td class="w-2/12" kind="cursor-none">
                                                <x-form.input.checkbox
                                                    name="is_default"
                                                    v-model="row.is_default"
                                                    @click="onDefaultClicked(index)" />
                                            </x-table.td>

                                            <x-table.td class="w-2/12" kind="right">
                                                <div class="group">
                                                    <x-button
                                                        type="button"
                                                        v-on:click="onDeleteItem(index)"
                                                        :title="trans('general.delete')"
                                                        class="w-6 h-7 rounded-lg p-0 group-hover:bg-gray-100"
                                                        override="class"
                                                    >
                                                        <span class="w-full material-icons-outlined text-lg text-center text-gray-300 group-hover:text-gray-500">delete</span>
                                                    </x-button>
                                                </div>
                                            </x-table.td>
                                        </x-table.tr>

                                        <tr class="flex">
                                            <td class="w-full">
                                                <button
                                                    type="button"
                                                    v-on:click="onAddItem"
                                                    class="w-full h-10 flex items-center justify-center border-b text-purple font-medium disabled:bg-gray-200 hover:bg-gray-100"
                                                >
                                                    <span class="material-icons-outlined text-base font-bold ltr:mr-1 rtl:ml-1">add</span>
                                                    {{ trans('custom-fields::general.add_value') }}
                                                </button>
                                            </td>
                                        </tr>
                                    </x-table.tbody>
                                </x-table>

                                <div class="invalid-feedback d-block"
                                    v-if="{{ 'form.errors.has("' . 'items' . '")' }}"
                                    v-html="{{ 'form.errors.get("' . 'items' . '")' }}">
                                </div>
                            </div>
                        </template>

                        <div v-else-if="can_type === 'value' && field_type == 'toggle'">
                            <x-form.group.toggle
                                name="default"
                                :label="trans_choice('general.defaults', 1) . ' ' . trans('custom-fields::general.form.value')"
                                show="field_type == 'toggle'"
                                not-required />
                        </div>

                        <template v-else-if="can_type === 'value'">
                            @php
                                $field_types = [
                                    'text',
                                    'textarea',
                                    'date',
                                    'dateTime',
                                    'time'
                                ];
                            @endphp

                            @foreach($field_types as $field_type)
                                @php
                                    $show_date_format = match ($field_type) {
                                        'dateTime'  => company_date_format() . ' H:i',
                                        'time'      => 'H:i',
                                        default     => company_date_format(),
                                    };
                                @endphp

                                <div class="sm:col-span-3" v-show="field_type == '{{ $field_type }}'">
                                    <x-dynamic-component
                                        :component="'form.group.' . str()->kebab($field_type)"
                                        :label="trans_choice('general.defaults', 1) . ' ' . trans('custom-fields::general.form.value')"
                                        name="default"
                                        :show-date-format="$show_date_format"
                                        not-required
                                    />
                                </div>
                            @endforeach
                        </template>
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            :title="trans_choice('custom-fields::general.locations', 1)"
                            :description="trans('custom-fields::general.section-head.location')" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select
                            name="location"
                            :label="trans_choice('custom-fields::general.locations', 1)"
                            :options="$locations"
                            change="onChangeLocation" />

                        <x-form.group.select
                            name="sort"
                            :label="trans('custom-fields::general.sort')"
                            :options="$sort_values"
                            change="onChangeSort"
                            dynamicOptions="sorts"
                            v-disabled="disabled.sort"
                            sort-options="false" />

                        <x-form.group.select
                            name="order"
                            :label="trans('custom-fields::general.order')"
                            :options="$orders"
                            dynamicOptions="orders"
                            v-disabled="disabled.order" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            :title="trans('custom-fields::general.design')"
                            :description="trans('custom-fields::general.section-head.design')" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select
                            name="width"
                            :label="trans('general.width')"
                            :options="$width_options"
                            sort-options="false"
                            not-required />

                        <x-form.group.select
                            name="show"
                            :label="trans('custom-fields::general.show')"
                            :options="$shows" />
                    </x-slot>
                </x-form.section>

                <x-form.group.switch name="enabled" label="{{ trans('general.enabled') }}" />

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="custom-fields.fields.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script.declaration :value="[
        'selected_validation_rules'     => $selected_validation_rules,
        'rules_cant_be'                 => config('custom-fields-rules.cant_be'),
        'rules_by_type'                 => $rules_by_type,
        'field_values'                  => $custom_field_values,
        'view'                          => $view,
        'edit_sorts'                    => $sort_values,
        'orders'                        => $orders,
        'sort_orders'                   => $sort_orders,
        'field_location'                => $field->location,
        'field_sort'                    => $field->sort,
        'field_default'                 => $field->default
    ]" />

    <x-script alias="custom-fields" file="fields" />
</x-layouts.admin>
