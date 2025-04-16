

@if(isset($options->model) && isset($options->type))

    @if(class_exists($options->model))

        @php $relationshipField = $row->field; @endphp

        @if($options->type == 'belongsToMany')

            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;

                    $selected_values = isset($relationshipData) ? $relationshipData->belongsToMany($options->model, $options->pivot_table, $options->foreign_pivot_key ?? null, $options->related_pivot_key ?? null, $options->parent_key ?? null, $options->key)->get()->map(function ($item, $key) use ($options) {
            			return $item->{$options->label};
            		})->all() : array();

                @endphp

                @if($view == 'browse')
                    @php
                        $string_values = implode(", ", $selected_values);
                        if(mb_strlen($string_values) > 25){ $string_values = mb_substr($string_values, 0, 25) . '...'; }
                    @endphp
                    @if(empty($selected_values))
                        <p>{{ __('voyager::generic.no_results') }}</p>
                    @else
                        <p>{{ $string_values }}</p>
                    @endif
                @else

                    @if(empty($selected_values))
                        <p>{{ __('voyager::generic.no_results') }}</p>
                    @else
                        <ul>
                            @foreach($selected_values as $selected_value)
                                <li>{{ $selected_value }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endif

            @else

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;

                  $selected_values = isset($relationshipData) ? $relationshipData->belongsToMany($options->model, $options->pivot_table, $options->foreign_pivot_key ?? null, $options->related_pivot_key ?? null, $options->parent_key ?? null, $options->key)->get(): array();
                  $selected_values = old($relationshipField, $selected_values);
                  $end_id=0;
                    @endphp

                    @if(!$row->required)
                        <option value="">{{__('voyager::generic.none')}}</option>
                    @endif
                <div class="custom-parameters container row">
                    <div class=" row">
                    @foreach($selected_values as $relationshipOption)

                        <div class="col-6" style="margin-bottom: 0; margin-top:10px;" row-id="{{$loop->index}}">

                            <div class="img col-xs-3" style="margin-bottom:0; margin-top: 10px">
                                <img width="200" src="{{isset($relationshipOption->{$options->label}) ? asset('storage/'.$relationshipOption->{$options->label} ) : ''}}">
                                <input type="hidden" name="{{ $row->field }}[{{ $end_id }}][image_exists]" value="{{ Voyager::image('storage/'.$dataTypeContent->{$row->field}) ? 1 : 0 }}">
                                <input type="hidden" name="{{ $row->field }}[{{ $end_id }}][id]" value="{{isset($relationshipOption->{$options->key}) ? $relationshipOption->{$options->key} : '' }}">

                            </div>
                            @if(isset($row->details->fields))
                                @foreach($row->details->fields as $fieldname=>$value)
                                    <div class="col-xs-2" style="margin-bottom:0;">
                                        <input type="{{$value->type}}" class="form-control" name="{{ $row->field }}[{{ $end_id }}][{{$fieldname}}]"
                                               value="{{$relationshipOption->{$fieldname} }}"
                                               placeholder="{{$value->display_name}}"
                                               id="{{$fieldname}}"/>
                                    </div>
                                @endforeach
                            @endif
                            <div class="col-xs-1" style="margin-bottom:0;">
                                <button type="button" class="btn old_btn btn-xs" style="margin-top:0px;"><i
                                        class="voyager-trash"></i></button>
                            </div>
                        </div>
                        @php
                            $end_id = $loop->index + 1;
                        @endphp
                    @endforeach
                </div>
                        <div class="form-group row" row-id="{{ $end_id }}">
                            <div class="col-xs-3" style="margin-bottom:0;">
                                <input type="file" class="form-control" name="{{ $row->field }}[{{ $end_id }}][image]"
                                       id="photo"/>
                            </div>

                            @if(isset($row->details->fields))
                                @foreach($row->details->fields as $fieldname=>$value)
                                    <div class="col-xs-3" style="margin-bottom:0;">
                                        <input type="{{$value->type}}" class="form-control" name="{{ $row->field }}[{{ $end_id }}][{{$fieldname}}]" value=""
                                               placeholder="{{$value->display_name}}"
                                               id="{{$fieldname}}"/>
                                    </div>
                                @endforeach
                            @endif
                            <div class="col-xs-1" style="margin-bottom:0;">
                                <button type="button" class="btn btn-success btn-xs" style="margin-top:0px;"><i
                                        class="voyager-plus"></i>
                                </button>
                            </div>
                        </div>


{{--                        <input type="hidden" name="keyvaluejson" value="{{$row->field}}"/>--}}
                </div>


            @endif

        @endif

    @else

        cannot make relationship because {{ $options->model }} does not exist.

    @endif

@endif


{{--@php--}}
{{--    $relationshipData = (isset($data)) ? $data : $dataTypeContent;--}}

{{--             dd($relationshipOptions);--}}
{{--    $end_id=0;--}}
{{--@endphp--}}
{{--@if(isset($view) && ($view == 'browse' || $view == 'read'))--}}
{{--    @if($view == 'browse')--}}
{{--        @if($query)--}}
{{--            Count: {{$query_count}}--}}
{{--            @endif--}}
{{--    @endif--}}
{{--    @if($view == 'read')--}}
{{--        <div>--}}
{{--            @foreach($query as $parameter)--}}
{{--                <div style="display: inline-flex;" >--}}
{{--                    <div style="float: left;">--}}
{{--                    <img style="float: right ; " src="{{asset('storage/'.$parameter->photo_small_url)}}.png">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    @endif--}}
{{--@else--}}
{{--    <div class="custom-parameters">--}}
{{--        @if($query)--}}
{{--        @foreach($query as $parameter)--}}

{{--            <div class="form-group row" row-id="{{$loop->index}}">--}}

{{--                <div class="img col-xs-3" style="margin-bottom:0;">--}}
{{--                    <img src="{{asset('storage/'.$parameter->photo_small_url )}}.png">--}}
{{--                    <input type="hidden" name="{{ $row->field }}[{{ $end_id }}][image_exists]" value="{{ Voyager::image('storage/'.$dataTypeContent->{$row->field}) ? 1 : 0 }}">--}}
{{--                    <input type="hidden" name="{{ $row->field }}[{{ $end_id }}][id]" value="{{$parameter->{$options->column} }}">--}}

{{--                </div>--}}
{{--                @if(isset($row->details->fields))--}}
{{--                    @foreach($row->details->fields as $fieldname=>$value)--}}
{{--                        <div class="col-xs-3" style="margin-bottom:0;">--}}
{{--                            <input type="{{$value->type}}" class="form-control" name="{{ $row->field }}[{{ $end_id }}][{{$fieldname}}]"--}}
{{--                                   value="{{$parameter->{$fieldname} }}"--}}
{{--                                   placeholder="{{$value->display_name}}"--}}
{{--                                   id="{{$fieldname}}"/>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--                <div class="col-xs-2" style="margin-bottom:0;">--}}
{{--                    <button type="button" class="btn old_btn btn-xs" style="margin-top:0px;"><i--}}
{{--                            class="voyager-trash"></i></button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            @php--}}
{{--                $end_id = $loop->index + 1;--}}
{{--            @endphp--}}
{{--        @endforeach--}}
{{--        @endif--}}
{{--        <div class="form-group row" row-id="{{ $end_id }}">--}}
{{--            <div class="col-xs-3" style="margin-bottom:0;">--}}
{{--                <input type="file" class="form-control" name="{{ $row->field }}[{{ $end_id }}][image]"--}}
{{--                       id="photo"/>--}}
{{--            </div>--}}

{{--            @if(isset($row->details->fields))--}}
{{--                @foreach($row->details->fields as $fieldname=>$value)--}}
{{--            <div class="col-xs-3" style="margin-bottom:0;">--}}
{{--                <input type="{{$value->type}}" class="form-control" name="{{ $row->field }}[{{ $end_id }}][{{$fieldname}}]" value=""--}}
{{--                    placeholder="{{$value->display_name}}"--}}
{{--                       id="{{$fieldname}}"/>--}}
{{--            </div>--}}
{{--                @endforeach--}}
{{--            @endif--}}
{{--            <div class="col-xs-1" style="margin-bottom:0;">--}}
{{--                <button type="button" class="btn btn-success btn-xs" style="margin-top:0px;"><i--}}
{{--                        class="voyager-plus"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}


{{--        <input type="hidden" name="keyvaluejson" value="{{$row->field}}"/>--}}
{{--    </div>--}}
{{--@endif--}}

<script>

    function editNameCount(el) {
        var str = el.getAttribute('name');
        var old_id = parseInt(el.parentNode.parentNode.getAttribute('row-id'));
        new_str = str.substring(0, str.indexOf('[') + 1)
            + (old_id + 1)
            + str.substring(str.indexOf(']'), str.length);
        return (new_str);
    }

    function addRow() {
        var new_row = this.parentNode.parentNode.cloneNode(true);
        @if(isset($row->details->fields))
        @foreach($row->details->fields as $fieldname=>$value)
        new_row.querySelector("#{{$fieldname}}").setAttribute('name', editNameCount(new_row.querySelector("#{{$fieldname}}")));
        new_row.querySelector("#{{$fieldname}}").value = '';
        @endforeach
        @endif
        new_row.querySelector("#photo").setAttribute('name', editNameCount(new_row.querySelector("#photo")));
        new_row.querySelector("#photo").value = '';
        new_row.setAttribute('row-id', parseInt(this.parentNode.parentNode.getAttribute('row-id')) + 1)

        this.classList.remove('btn-success');
        this.innerHTML = '<i class="voyager-trash"></i>';
        new_row.querySelector('.btn-success').onclick = this.onclick;
        this.onclick = removeRow;
        this.parentNode.parentNode.parentNode.appendChild(new_row);
    };

    function removeRow() {
        this.parentNode.parentNode.remove();
    }

    var buttons = document.querySelectorAll('.custom-parameters .old_btn');
    for (var i = 0; i < buttons.length; i++) buttons[i].onclick = removeRow;
    var suc_buttons = document.querySelectorAll('.custom-parameters .btn-success');
    suc_buttons[suc_buttons.length - 1].onclick = addRow;

</script>

