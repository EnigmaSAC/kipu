<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.edit', ['type' => setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1))]) }}
    </x-slot>

    <x-slot name="content">
        <x-documents.form.content
          :type="$type"
          :document="$estimate"
          :text-item-description="@trans('general.description')"
          contact-type="customer"
          hide-recurring
          hide-order-number
          hide-due-at
        />
    </x-slot>

    <x-documents.script :type="$type" :items="$estimate->items()->get()" :document="$estimate" alias="core" />
</x-layouts.admin>
