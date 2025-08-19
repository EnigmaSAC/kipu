<x-show.accordion type="convert" :open="true">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('estimates::general.convert') }}"
            description="{{ $description }}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="flex flex-wrap space-x-3 rtl:space-x-reverse">
            @can('update-estimates-estimates')
                @if('approved' === $document->status && $salesPurchaseOrdersEnabled)
                    <x-link override="class" class="px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-blue-300 hover:bg-blue-500 text-white disabled:bg-blue-100"
                            href="{{ route('estimates.estimates.convert-to-sales-order', $document->id) }}"
                            @click="e => e.target.classList.add('disabled')"
                    >
                        {{ trans('estimates::general.convert_to_sales_order') }}
                    </x-link>
                @endif

                <x-link override="class" class="px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-green hover:bg-green-700 text-white disabled:bg-green-100"
                        href="{{ route('estimates.estimates.convert', $document->id) }}"
                        @click="e => e.target.classList.add('disabled')"
                >
                    {{ trans('estimates::general.convert_to_invoice') }}
                </x-link>
            @endcan
        </div>
    </x-slot>
</x-show.accordion>
