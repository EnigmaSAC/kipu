<x-layouts.admin>
    <x-slot name="title">
        {{ setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1)) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" route="estimates.settings.estimate.update">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            title="{{ trans('general.general') }}"
                            description="{{ trans('estimates::settings.estimate.form_description.general') }}"
                        />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text
                            name="name"
                            label="{{ trans('general.name') }}"
                            value="{{ setting('estimates.estimate.name') }}"
                        />

                        <x-form.group.text
                            name="number_prefix"
                            label="{{ trans('settings.invoice.prefix') }}"
                            value="{{ setting('estimates.estimate.number_prefix') }}"
                            not-required
                        />

                        <x-form.group.text
                            name="number_digit"
                            label="{{ trans('settings.invoice.digit') }}"
                            value="{{ setting('estimates.estimate.number_digit') }}"
                            not-required
                        />

                        <x-form.group.text 
                            name="number_next"
                            label="{{ trans('settings.invoice.next') }}"
                            value="{{ setting('estimates.estimate.number_next') }}"
                            not-required
                        />

                        <x-form.group.select
                            name="approval_terms"
                            label="{{ trans('estimates::settings.estimate.approval_terms') }}"
                            :options="$payment_terms"
                            :selected="setting('estimates.estimate.approval_terms')"
                            not-required
                        />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            title=" {{ trans_choice('general.templates', 1) }}"
                            description="{{ trans('estimates::settings.estimate.form_description.template') }}"
                        />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-2 rounded-lg cursor-pointer text-center py-2 px-2">
                            <label class="cursor-pointer">
                                <div @click="form.template='default'">
                                    <img src="{{ asset('public/img/invoice_templates/default.png') }}" class="h-60 my-3" alt="Default" />

                                    <input type="radio" name="template" value="default" v-model="form._template">
                                    {{ trans('settings.invoice.default') }}
                                </div>
                            </label>
                        </div>

                        <div class="sm:col-span-2 rounded-lg cursor-pointer text-center py-2 px-2">
                            <label class="cursor-pointer">
                                <div @click="form.template='classic'">
                                    <img src="{{ asset('public/img/invoice_templates/classic.png') }}" class="h-60 my-3" alt="Classic" />

                                    <input type="radio" name="template" value="classic" v-model="form._template">
                                    {{ trans('settings.invoice.classic') }}
                                </div>
                            </label>
                        </div>

                        <div class="sm:col-span-2 rounded-lg cursor-pointer text-center py-2 px-2">
                            <label class="cursor-pointer">
                                <div @click="form.template='modern'">
                                    <img src="{{ asset('public/img/invoice_templates/modern.png') }}" class="h-60 my-3" alt="Modern" />

                                    <input type="radio" name="template" value="modern" v-model="form._template">
                                    {{ trans('settings.invoice.modern') }}
                                </div>
                            </label>
                        </div>

                        <x-form.group.color
                            name="color"
                            label="{{ trans('general.color') }}"
                            :value="setting('estimates.estimate.color')"
                        />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            title="{{ trans_choice('general.defaults', 2) }}"
                            description="{{ trans('estimates::settings.estimate.form_description.default') }}"
                        />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text
                            name="title"
                            label="{{ trans('settings.invoice.title') }}"
                            value="{{ setting('estimates.estimate.title') }}"
                            not-required
                        />

                        <x-form.group.text
                            name="subheading"
                            label="{{ trans('settings.invoice.subheading') }}"
                            value="{{ setting('estimates.estimate.subheading') }}"
                            not-required
                        />

                        <x-form.group.textarea 
                            name="notes"
                            label="{{ trans_choice('general.notes', 2) }}"
                            :value="setting('estimates.estimate.notes')"
                            form-group-class="sm:col-span-3"
                        />

                        <x-form.group.textarea
                            name="footer"
                            label="{{ trans('general.footer') }}"
                            :value="setting('estimates.estimate.footer')"
                            form-group-class="sm:col-span-3"
                        />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            title="{{ trans_choice('settings.invoice.column', 2) }}"
                            description="{{ trans('estimates::settings.estimate.form_description.column') }}"
                        />
                    </x-slot>

                    <x-slot name="body">
                        <div class="grid col-span-6 grid-rows-3">
                            <x-form.group.invoice-text
                                name="item_name"
                                label="{{ trans('settings.invoice.item_name') }}"
                                :options="$item_names"
                                :selected="setting('estimates.estimate.item_name')"
                                change="settingsInvoice"
                                input-name="item_name_input"
                                :input-value="setting('estimates.estimate.item_name_input')"
                                form-group-class="sm:col-span-6 sm:gap-0"
                            />

                            <x-form.group.invoice-text
                                name="price_name"
                                label="{{ trans('settings.invoice.price_name') }}"
                                :options="$price_names"
                                :selected="setting('estimates.estimate.price_name')"
                                change="settingsInvoice"
                                input-name="price_name_input"
                                :input-value="setting('estimates.estimate.price_name_input')"
                                form-group-class="sm:col-span-6 sm:gap-0"
                            />

                            <x-form.group.invoice-text
                                name="quantity_name"
                                label="{{ trans('settings.invoice.quantity_name') }}"
                                :options="$quantity_names"
                                :selected="setting('estimates.estimate.quantity_name')"
                                change="settingsInvoice"
                                input-name="quantity_name_input"
                                :input-value="setting('estimates.estimate.quantity_name_input')"
                                form-group-class="sm:col-span-6 sm:gap-0"
                            />
                        </div>

                        <x-form.group.toggle
                            name="hide_item_description"
                            label="{{ trans('settings.invoice.hide.item_description') }}"
                            :value="setting('estimates.estimate.hide_item_description')"
                            not-required 
                            form-group-class="sm:col-span-6"
                        />

                        <x-form.group.toggle
                            name="hide_amount"
                            label="{{ trans('settings.invoice.hide.amount') }}"
                            :value="setting('estimates.estimate.hide_amount')"
                            not-required
                            form-group-class="sm:col-span-6"
                        />
                    </x-slot>
                </x-form.section>

                @can('update-estimates-settings-estimate')
                    <x-form.section>
                        <x-slot name="foot">
                            <x-form.buttons :cancel="url()->previous()" />
                        </x-slot>
                    </x-form.section>
                @endcan

                <x-form.input.hidden name="_template" :value="setting('estimates.estimate.template')" />
                <x-form.input.hidden name="_prefix" value="estimates.estimate" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
