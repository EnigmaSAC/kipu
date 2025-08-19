<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1))]) }}
    </x-slot>

    <x-slot name="favorite"
            title="{{ trans('general.title.new', ['type' => setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1))]) }}"
            icon="check"
            route="estimates.estimates.create"
    ></x-slot>

    <x-slot name="content">
        <x-documents.form.content
            :type="$type"
            contact-type="customer"
            :text-item-description="@trans('general.description')"
            hide-recurring
            hide-order-number
            hide-due-at
        />
    </x-slot>

    <x-documents.script :type="$type" alias="core" />
</x-layouts.admin>
