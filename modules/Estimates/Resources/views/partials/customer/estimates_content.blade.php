<x-tabs.tab id="estimates">
    @if ($documents->count())
        <x-table>
            <x-table.thead>
                <x-table.tr class="flex items-center px-1">
                    <x-table.th class="w-4/12 table-title hidden sm:table-cell">
                        <x-slot name="first">
                            <x-sortablelink column="due_at" title="{{ trans('estimates::general.expiry_date') }}" />
                        </x-slot>

                        <x-slot name="second">
                            <x-sortablelink column="issued_at" title="{{ trans('estimates::general.estimate_date') }}" />
                        </x-slot>
                    </x-table.th>

                    <x-table.th class="w-3/12 table-title hidden sm:table-cell">
                        <x-sortablelink column="status" title="{{ trans_choice('general.statuses', 1) }}" />
                    </x-table.th>

                    <x-table.th class="w-6/12 sm:w-3/12 table-title'">
                        <x-slot name="first">
                            <x-sortablelink column="contact_name" title="{{ trans_choice('general.customers', 1) }}" />
                        </x-slot>

                        <x-slot name="second">
                            <x-sortablelink column="document_number" title="{{ trans_choice('general.numbers', 1) }}" />
                        </x-slot>
                    </x-table.th>

                    <x-table.th class="w-6/12 sm:w-2/12 ltr:pl-6 rtl:pr-6 py-3 ltr:text-right rtl:text-left text-sm font-medium text-black tracking-wider" override="class">
                        <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                    </x-table.th>
                </x-table.tr>
            </x-table.thead>

            <x-table.tbody>
                @foreach($documents as $item)
                    @php $paid = $item->paid; @endphp
                    <x-table.tr href="{{ route('estimates.estimates.show', $item->id) }}">
                        <x-table.td class="w-4/12 table-title hidden sm:table-cell">
                            <x-slot name="first" class="font-bold truncate" override="class">
                                @if(null !== $item->extra_param->expire_at)
                                    {{ \Date::parse($item->extra_param->expire_at)->diffForHumans() }}
                                @endif
                            </x-slot>

                            <x-slot name="second">
                                <x-date date="{{ $item->issued_at }}" />
                            </x-slot>
                        </x-table.td>

                        <x-table.td class="w-3/12 table-title hidden sm:table-cell">
                            <x-show.status status="{{ $item->status }}" background-color="bg-{{ $item->status_label }}" text-color="text-text-{{ $item->status_label }}" />
                        </x-table.td>

                        <x-table.td class="w-6/12 sm:w-3/12 table-title'">
                            <x-slot name="first">
                                {{ $item->contact_name }}
                            </x-slot>

                            <x-slot name="second" class="relative w-20 font-normal group" data-tooltip-target="tooltip-information-{{ $item->id }}" data-tooltip-placement="left" override="class,data-tooltip-target,data-tooltip-placement">
                                                    <span class="border-black border-b border-dashed">
                                                        {{ $item->document_number }}
                                                    </span>

                                <div class="w-full absolute h-10 -left-10 -mt-6"></div>

                                <x-documents.index.information :document="$item" />
                            </x-slot>
                        </x-table.td>

                        <x-table.td class="w-6/12 sm:w-2/12 ltr:pl-6 rtl:pr-6 py-3 ltr:text-right rtl:text-left text-sm font-medium text-black tracking-wider" override="class">
                            <x-money amount="{{ $item->amount }}" currency="{{ $item->currency_code }}" convert />
                        </x-table.td>

                        <x-table.td kind="action">
                            <x-table.actions :model="$item" />
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            </x-table.tbody>
        </x-table>

        <x-pagination :items="$documents" />
    @endif
</x-tabs.tab>
