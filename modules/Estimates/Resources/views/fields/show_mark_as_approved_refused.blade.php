<x-show.accordion type="confirm" :open="true">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.confirm') }}"
            description="{{ $description }}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="flex flex-wrap space-x-3 rtl:space-x-reverse">
            @can('update-estimates-estimates')
                @if($document->status === 'approved')
                    <x-link override="class" class="pointer-events-none px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-green hover:bg-green-700 text-white disabled:bg-green-100"
                            href="{{ route('estimates.estimates.approve', $document->id) }}"
                            @click="e => e.target.classList.add('disabled')"
                    >
                        {{ trans('estimates::general.mark_approved') }}
                    </x-link>
                @else
                    <x-link override="class" class="px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-green hover:bg-green-700 text-white disabled:bg-green-100"
                            href="{{ route('estimates.estimates.approve', $document->id) }}"
                            @click="e => e.target.classList.add('disabled')"
                    >
                        {{ trans('estimates::general.mark_approved') }}
                    </x-link>
                @endif
                @if($document->status === 'refused')
                    <x-link override="class" class="pointer-events-none px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-red hover:bg-red-700 text-white disabled:bg-red-100"
                            href="{{ route('estimates.estimates.refuse', $document->id) }}"
                            @click="e => e.target.classList.add('disabled')"
                    >
                        {{ trans('estimates::general.mark_refused') }}
                    </x-link>
                @else
                    <x-link override="class" class="px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-red hover:bg-red-700 text-white disabled:bg-red-100"
                            href="{{ route('estimates.estimates.refuse', $document->id) }}"
                            @click="e => e.target.classList.add('disabled')"
                    >
                        {{ trans('estimates::general.mark_refused') }}
                    </x-link>
                @endif
            @endcan
        </div>
    </x-slot>
</x-show.accordion>
