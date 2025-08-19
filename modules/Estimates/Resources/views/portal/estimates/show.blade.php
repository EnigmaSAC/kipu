<x-layouts.portal>
    <x-slot name="title">
        {{ setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1)) . ': ' . $estimate->document_number }}
    </x-slot>

    <x-slot name="buttons">
        @if($estimate->status !== 'expired')
            @if($estimate->status !== 'approved')
                <x-link override="class" href="{{ route('portal.estimates.estimates.approve', $estimate->id) }}" class="bg-green text-white px-3 py-1.5 mb-3 sm:mb-0 rounded-lg text-sm font-medium leading-6 hover:bg-green-700">
                    {{ trans('estimates::general.approve') }}
                </x-link>
            @endif
            @if($estimate->status !== 'refused')
                <x-link override="class" href="{{ route('portal.estimates.estimates.refuse', $estimate->id) }}" class="bg-red text-white px-3 py-1.5 mb-3 sm:mb-0 rounded-lg text-sm font-medium leading-6 hover:bg-red-700">
                    {{ trans('estimates::general.refuse') }}
                </x-link>
            @endif
        @endif
        @stack('button_pdf_start')
        <x-link href="{{ route('portal.estimates.estimates.pdf', $estimate->id) }}" class="bg-green text-white px-3 py-1.5 mb-3 sm:mb-0 rounded-lg text-sm font-medium leading-6 hover:bg-green-700">
            {{ trans('general.download') }}
        </x-link>
        @stack('button_pdf_end')

        @stack('button_print_start')
        <x-link href="{{ route('portal.estimates.estimates.print', $estimate->id) }}" target="_blank" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium leading-6">
            {{ trans('general.print') }}
        </x-link>
        @stack('button_print_end')
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col lg:flex-row my-10 lg:space-x-24 rtl:space-x-reverse space-y-12 lg:space-y-0">
            <div class="w-full lg:w-5/12">
            </div>

            <div class="hidden lg:block w-7/12">
                <x-documents.show.template
                    type="estimate"
                    :document="$estimate"
                    document-template="{{ setting('estimates.estimate.template', 'default') }}"
                    hide-due-at
                />
            </div>
        </div>
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-script folder="portal" file="apps" />
</x-layouts.portal>
