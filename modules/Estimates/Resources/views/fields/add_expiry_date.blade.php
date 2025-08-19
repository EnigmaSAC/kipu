<x-form.group.date
    name="expire_at"
    label="{{ trans('estimates::general.expiry_date') }}"
    icon="calendar_today"
    value="{{ $expire_at }}"
    show-date-format="{{ company_date_format() }}"
    date-format="Y-m-d"
    autocomplete="off"
    period="{{ setting('estimates.estimate.approval_terms') }}"
    min-date="form.issued_at"
    min-date-dynamic="min_due_date"
    data-value-min
    form-group-class="sm:col-span-2"
/>
