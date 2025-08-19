<div class="sm:col-span-6 mb-8">
    <div class="relative">
        <label for="notes" class="text-black text-sm font-medium">
            {{ trans_choice('general.notes', 2) }}
        </label>

        <x-form.group.select
            name="account_id"
            placeholder="{{ trans('general.form.select.field', ['field' => trans('dynamic-document-notes::general.account_details')]) }}"
            :options="$accounts"
            change="typeof onDynamicFormParams === 'function' ? onDynamicFormParams('{{ route('dynamic-document-notes.account.note') }}', {
                account_id: 'this.form.account_id'
            }) : empty_function"
            not-required
            form-group-class="w-2/4"
        />

        <x-form.group.textarea
            name="notes"
            :value="$notes"
            v-model="form.notes"
            placeholder="{{ trans('general.form.enter', ['field' => trans_choice('general.notes', 2)]) }}"
            not-required
            override="w-full text-sm px-0 py-2.5 mt-1 border-light-gray text-black placeholder-light-gray disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple border-0 bg-transparent rounded-none resize-none"
            form-group-class="border-b pb-2 mb-3.5"
            rows="3"
            textarea-auto-height
        />
    </div>
</div>

<script>
    empty_function = function() {};
</script>