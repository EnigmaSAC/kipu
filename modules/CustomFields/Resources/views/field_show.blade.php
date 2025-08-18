@if (!isset($field->show) || (isset($field->show) && $field->show == 'always') || (isset($field->show) && $field->show == 'if_filled' && !empty($field_value)))
    @if($show_type == 'default')
        <strong>
            {{ $field->name }}:
        </strong>
        <span class="float-right">
            {{ $field_value }}
        </span>
        <br>
        <br>
    @elseif($show_type == 'items')
        <br>
        <small>
            {{ $field->name }}:{{ $field_value }}
        </small>
    @elseif($show_type == 'transactions')
        @switch($template)
            @case('second')
                <table>
                    <tr>
                        <td class="font-semibold" style="width:30%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                            {{ $field->name }}
                        </td>

                        <td style="width:70%; margin: 0px; padding: 0 0 8px 0; font-size: 12px;">
                            {{ $field_value }}
                        </td>
                    </tr>
                </table>
                @break
            @case('third')
                <tr>
                    <td valign="top" class="font-semibold" style="width: 30%; margin: 0px; padding: 0; font-size: 12px; line-height: 24px;">
                        {{ $field->name }}
                    </td>

                    <td valign="top" style="width:70%; margin: 0px; padding: 0; font-size: 12px; border-bottom:1px solid; line-height: 24px;">
                        {{ $field_value }}
                    </td>
                </tr>
                @break
            @default
                <tr>
                    <td valign="top" style="width: 30%; margin: 0px; padding: 8px 4px 0 0; font-size: 12px; font-weight:600;">
                        {{ $field->name }}:
                    </td>

                    <td valign="top" class="border-bottom-dashed-black" style="width:70%; margin: 0px; padding:  8px 0 0 0; font-size: 12px;">
                        {{ $field_value }}
                    </td>
                </tr>
        @endswitch
    @elseif($show_type == 'informations')
        <div class="flex flex-col text-sm mb-5">
            <div class="font-medium">{{ $field->name }}</div>
            <span>{{ $field_value }}</span>
        </div>
    @elseif($show_type == 'print')
        <p class="mb-0">
            <span class="font-semibold spacing w-numbers">
                {{ $field->name }}:
            </span>
            <span class="float-right spacing">
                {{ $field_value }}
            </span>
        </p>
    @endif
@endif
