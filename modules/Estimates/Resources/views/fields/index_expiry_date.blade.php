<x-slot name="first" class="font-bold truncate" override="class">
    @stack('expire_at_td_inside_start')
    <x-date :date="$expire_at" function="diffForHumans" />
    @stack('expire_at_td_inside_end')
</x-slot>
