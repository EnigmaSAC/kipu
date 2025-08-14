<x-layouts.modules>
    <x-slot name="title">
        {{ trans_choice('general.modules', 2) }}
    </x-slot>

    <x-slot name="buttons">
        <x-link href="{{ route('apps.upload.form') }}">
            {{ trans('modules.upload_title') }}
        </x-link>
    </x-slot>

    <x-slot name="content">
        @if (! empty($installed))
            <x-modules.installed :modules="$installed" />
        @else
            <div class="py-6 font-medium">
                <div class="flex items-center justify-between mb-5 lg:mb-0">
                    <h4 class="py-3 font-medium lg:text-2xl">
                        {{ trans('modules.my_apps') }}
                    </h4>
                </div>

                <x-modules.no-apps />
            </div>
        @endif
    </x-slot>

    <x-script folder="modules" file="apps" />
</x-layouts.modules>
