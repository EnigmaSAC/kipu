@if($has_currency)
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        <div class="form-group relative sm:col-span-3">
            <input class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
            onkeypress='validate(event)'
            type="text"
            wire:model.lazy="base_currency_rate"
            wire:loading.attr="disabled" wire:target="base_currency, second_currency, base_currency_rate, second_currency_rate">

            <input class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
            onkeypress='validate(event)'
            type="text"
            wire:model.lazy="second_currency_rate"
            wire:loading.attr="disabled" wire:target="base_currency, second_currency, base_currency_rate, second_currency_rate">
        </div>

        <div class="form-group relative sm:col-span-3">
            <select
            class="un-el-select"
            name="base_curreny"
            wire:model.debounce.1ms="base_currency"
            wire:loading.attr="disabled" wire:target="base_currency, second_currency, base_currency_rate, second_currency_rate">
                @foreach($base_currency_data as $currency)
                    <option value="{{ $currency['id'] }}">{{ $currency['name'] }}</option>
                @endforeach
            </select>

            <select
            class="un-el-select"
            name="second_curreny"
            wire:model.debounce.1ms="second_currency"
            wire:loading.attr="disabled" wire:target="base_currency, second_currency, base_currency_rate, second_currency_rate">
                @foreach($second_currency_data as $currency)
                    <option value="{{ $currency['id'] }}">{{ $currency['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
@else
    <div x-show="! $wire.has_currency">
        <span class="block mb-3 text-black-400">{{ trans('currency-calculator::general.has_currency') }}</span>
        

        <x-link href="{{ route('settings.default.edit') }}" target="_blank" class="font-light text-xs border-b transition-all hover:font-medium" override="class">
            {{ trans('currency-calculator::general.go_to_settings') }}
        </x-link>
    </div>
@endif

@push('scripts')
    <script>
        function validate(evt) {
            var theEvent = evt || window.event;

            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }

            var regex = /[0-9]|\./;

            if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>
@endpush
