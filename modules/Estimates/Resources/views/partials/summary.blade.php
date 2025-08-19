@if ($estimates->count() || request()->get('search', false))
    <div class="flex items-center justify-center text-black mt-10 mb-10">
        @foreach ($summaryItems as $item)
            <div @class(['w-1/2 sm:w-1/4 text-center'])>
                @php
                    $text_color = (! empty($item['text_color'])) ? $item['text_color'] : 'text-purple group-hover:text-purple-700';
                @endphp

                @if (! empty($item['tooltip']))
                    <x-tooltip id="tooltip-summary-{{ $loop->index }}" placement="top" message="{!! $item['tooltip'] !!}">
                        @if (! empty($item['href']))
                            <x-link href="{{ $item['href'] }}" class="group" override="class">
                                <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                    {!! $item['amount'] !!}
                                    <span
                                        class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900"
                                        style="height: 1px;"></span>
                                </div>

                                <span class="font-light mt-3">
                                    {!! $item['title'] !!}
                                </span>
                            </x-link>
                        @else
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                {!! $item['amount'] !!}
                                <span
                                    class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900"
                                    style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $item['title'] !!}
                            </span>
                        @endif
                    </x-tooltip>
                @else
                    @if (! empty($item['href']))
                        <x-link href="{{ $item['href'] }}" class="group" override="class">
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                {!! $item['amount'] !!}
                                <span
                                    class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900"
                                    style="height: 1px;"></span>
                            </div>
                            <span class="font-light mt-3">
                                {!! $item['title'] !!}
                            </span>
                        </x-link>
                    @else
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                            {!! $item['amount'] !!}
                            <span
                                class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900"
                                style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $item['title'] !!}
                        </span>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
@endif
