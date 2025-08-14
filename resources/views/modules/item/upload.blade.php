<x-layouts.modules>
    <x-slot name="title">{{ trans('modules.upload_title') }}</x-slot>
    <x-form method="POST" action="{{ route('apps.upload') }}" enctype="multipart/form-data">
        <x-form.input.file name="file" accept=".zip" required />
        <x-button type="submit">{{ trans('modules.upload_install') }}</x-button>
    </x-form>
</x-layouts.modules>

