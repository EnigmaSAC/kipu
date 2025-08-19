<x-layouts.admin>
    <x-slot name="title">
        {{ setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1)) . ': ' . $estimate->document_number }}
    </x-slot>

    <x-slot name="status">
        <x-show.status
            status="{{ $estimate->status }}"
            background-color="bg-{{ $estimate->status_label }}"
            text-color="text-text-{{ $estimate->status_label }}"
        />
    </x-slot>

    <x-slot name="buttons">
        <x-documents.show.buttons
            :type="$type"
            :document="$estimate"
        />
    </x-slot>

    <x-slot name="moreButtons">
        <x-documents.show.more-buttons
            :type="$type"
            :document="$estimate"
            hide-cancel
        />
    </x-slot>

    <x-slot name="content">
        <x-documents.show.content
            :type="$type"
            :document="$estimate"
            share-route="{{ route('estimates.modals.estimates.emails.create', $estimate->id) }}"
            hide-receive
            hide-make-payment
            hide-schedule
            hide-children
            hide-header-due-at
            hide-due-at
            hide-button-paid
            hide-get-paid
            hide-footer-transactions
            hide-send
        />
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-documents.script :type="$type" alias="core" />
</x-layouts.admin>
