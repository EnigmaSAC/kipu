<x-layouts.admin>
    <x-slot name="title">{{ trans('custom-fields::general.name') }}</x-slot>

    <x-slot name="buttons">
        @can('create-custom-fields-fields')
            <x-link href="{{ route('custom-fields.fields.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('custom-fields::general.fields', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        @if ($custom_fields->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search
                    search-string="Modules\CustomFields\Models\Field"
                    bulk-action="Modules\CustomFields\BulkActions\Fields"
                />

                <x-table>
                    <x-table.thead>
                        <x-table.tr class="flex items-center px-1">
                            <x-table.th class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                <x-index.bulkaction.all />
                            </x-table.th>

                            <x-table.th class="w-6/12 sm:w-4/12">
                                <x-sortablelink column="name" :title="trans('general.name')" />
                            </x-table.th>

                            <x-table.th class="w-3/12 hidden sm:table-cell">
                                <x-sortablelink column="location.name" :title="trans_choice('custom-fields::general.locations', 1)" />
                            </x-table.th>

                            <x-table.th class="w-3/12 hidden sm:table-cell">
                                <x-sortablelink column="type.name" :title="trans_choice('general.types', 1)" />
                            </x-table.th>

                            <x-table.th class="w-6/12 sm:w-2/12 ">
                                <x-sortablelink column="show" :title="trans('custom-fields::general.show')" />
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($custom_fields as $field)
                            <x-table.tr href="{{ route('custom-fields.fields.edit', $field->id) }}">
                                <x-table.td class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                    <x-index.bulkaction.single id="{{ $field->id }}" name="{{ $field->name }}" />
                                </x-table.td>

                                <x-table.td class="w-6/12 sm:w-4/12 flex items-center">
                                    <x-slot name="first" override="class" class="font-medium truncate w-11/12">
                                        {{ $field->name }}
                                    </x-slot>

                                    <x-slot name="second">
                                        @if (! $field->enabled)
                                            <x-index.disable text="{{ trans_choice('custom-fields::general.fields', 1) }}" />
                                        @endif
                                    </x-slot>
                                </x-table.td>

                                <x-table.td class="w-3/12 truncate hidden sm:table-cell">
                                    <div class="truncate">
                                        {{ $locations[$field->location] ?? trans('custom-fields::general.location_defined', ['location' => $field->location]) }}
                                    </div>
                                </x-table.td>

                                <x-table.td class="w-3/12 hidden sm:table-cell">
                                    <div class="truncate">
                                        {{ $types[$field->type] }}
                                    </div>
                                </x-table.td>

                                <x-table.td class="w-6/12 sm:w-2/12">
                                    <div class="truncate">
                                        {{ trans('custom-fields::general.form.shows.' . $field->show) }}
                                    </div>
                                </x-table.td>

                                <x-table.td class="p-0" override="class">
                                    <x-table.actions :model="$field" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$custom_fields" />
            </x-index.container>
        @else
            <x-empty-page
                group="custom-fields"
                page="fields"
                image-empty-page="{{ asset('modules/CustomFields/Resources/assets/img/custom-fields.png') }}"
                url-docs-path="https://akaunting.com/docs/app-manual/operation/custom-fields"
                permission-create="create-custom-fields-fields"
                hide-button-import
            />
        @endif
    </x-slot>

    <x-script alias="custom-fields" file="fields" />
</x-layouts.admin>
