<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    @include($class->views['header'])

    @if ($condition)
        @livewire('currency-calculator')
        @else
        {{ trans('currency-calculator::general.condition') }}
    @endif
</div>
