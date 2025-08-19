<x-show.accordion type="send" :open="true">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.send') }}"
            description="{{ $description }}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="flex flex-wrap space-x-3 rtl:space-x-reverse">
            @if ($document->contact_email)
                <x-button id="button-email-send" kind="secondary" @click="onSendEmail('{{ route('estimates.modals.estimates.emails.create', $document->id) }}')">
                    {{ trans('invoices.send_mail') }}
                </x-button>
            @else
                <x-tooltip message="{{ trans('invoices.messages.email_required') }}" placement="top">
                    <x-dropdown.button disabled="disabled">
                        {{ trans('invoices.send_mail') }}
                    </x-dropdown.button>
                </x-tooltip>
            @endif

            @can('update-estimates-estimates')
                @if ($document->status == 'draft')
                    <x-link href="{{ route('estimates.estimates.sent', $document->id) }}" @click="e => e.target.classList.add('disabled')">
                        {{ trans('invoices.mark_sent') }}
                    </x-link>
                @else
                    <x-button disabled="disabled">
                        {{ trans('invoices.mark_sent') }}
                    </x-button>
                @endif
            @endcan

            @if ($document->status != 'cancelled')
                <x-button @click="onShareLink('{{ route('estimates.modals.estimates.share.create', $document->id) }}')">
                    {{ trans('general.share_link') }}
                </x-button>
            @endif
        </div>
    </x-slot>
</x-show.accordion>
