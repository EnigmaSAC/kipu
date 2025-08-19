<x-layouts.admin>
    <x-slot name="title">
        {{ trans('dynamic-document-notes::general.name') }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" route="dynamic-document-notes.settings.update">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            title="{{ trans('general.general') }}"
                            description="{{ trans('dynamic-document-notes::general.form_description.general') }}"
                        />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.editor name="template" label="{{ trans_choice('general.templates', 1) }}" value="{!! setting('dynamic-document-notes.template', config('dynamic-document-notes.default')) !!}" v-model='form.template' rows="5" form-group-class="col-span-6" />

                        <div class="sm:col-span-6">
                            <div class="bg-gray-200 rounded-md p-3">
                                <small>
                                    {!! trans('dynamic-document-notes::general.fields', ['field_list' => implode(', ', $fields)]) !!}
                                </small>
                            </div>
                        </div>
                    </x-slot>
                </x-form.section>

                @can('update-dynamic-document-notes-settings')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
                @endcan

                <x-form.input.hidden name="_prefix" value="company" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
