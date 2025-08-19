<x-layouts.print>
    <x-slot name="title">
        {{ setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1)) . ': ' . $document->document_number }}
    </x-slot>

    <x-slot name="content">
        <x-documents.template.modern
            :type="$type"
            :document="$document"
            hide-due-at
        />
    </x-slot>
</x-layouts.print>
