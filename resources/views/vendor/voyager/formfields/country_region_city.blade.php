@if ($row->field=='country')
    @php
        $route='admin.getcountries';
    @endphp
@elseif($row->field=='region')
    @php
        $route='admin.getregions';
    @endphp
@elseif($row->field=='city')
    @php
        $route='admin.getcities';
    @endphp
@endif

<select name="{{$row->field}}"


        class="form-control @if(isset($options->taggable) && $options->taggable === 'on') select2-taggable @else select2-ajax @endif"
        data-get-items-route="{{route($route)}}"
        data-get-items-field="{{$row->field}}"
        @if(!is_null($dataTypeContent->getKey())) data-id="{{$dataTypeContent->getKey()}}" @endif
        data-method="{{ !is_null($dataTypeContent->getKey()) ? 'edit' : 'add' }}"
        @if(isset($options->taggable) && $options->taggable === 'on')
{{--        data-route="{{ route('voyager.'.\Illuminate\Support\Str::slug($options->table).'.store') }}"--}}
        data-label="{{$options->label}}"
        data-error-message="{{__('voyager::bread.error_tagging')}}"
@endif
    >


</select>


@push('javascript')
<script>
    $(document).ready(function () {
        let oldTitle = 0;
        $('.select2').on('DOMSubtreeModified', function () {
            let select = $('[id^="select2-{{$row->field}}"]')[0]
            if (!!select) {
                let vselect = $('[name^="{{$row->field}}"]')[0]

                let title = vselect.value

                if (title) {
                    if (title !== oldTitle) {
                        $.get(
                            '{{url('/admin/updateContent')}}',
                            {
                                key: '{{$row->field}}',
                                value: title,
                                field_id: '{{$row->field}}',
                            },
                        ).then(res => {
                            console.log(res)
                        }).catch(res => {
                            console.log(res)
                        });
                        oldTitle = title
                        console.log(title)

                    }
                }
            }
        });

    });
</script>
@endpush
