@if(isset($options->model) && isset($options->type))

    @if(class_exists($options->model))

        @php $relationshipField = $row->field; @endphp

        @if($options->type == 'belongsTo')

            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;
                    $model = app($options->model);
                    $query = $model::where($options->key,$relationshipData->{$options->column})->first();
                @endphp

                @if(isset($query))
                    <div data-field-name="{{ $query->name }}">
                        <img src="@if( !filter_var($query->photo_url, FILTER_VALIDATE_URL)){{ Voyager::image( $query->photo_url ) }}@else{{ $query->photo_url }}@endif"
                             data-file-name="{{ $query->photo_url }}"
                             style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
                    </div>
                @else
                    <p>{{ __('voyager::generic.no_results') }}</p>
                @endif
            @else
                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;
                    $model = app($options->model);
                    $query = $model::where($options->key,$relationshipData->{$options->column})->first();

                @endphp
                @if(isset($query))

                    <div data-field-name="{{ $query->name }}">
                        <a href="#" class="voyager-x remove-single-image" style="position:absolute;"></a>

                        <img src="@if( !filter_var($query->photo_url, FILTER_VALIDATE_URL)){{ Voyager::image( $query->photo_url ) }}@else{{ $query->photo_url }}@endif"
                             data-file-name="{{ $query->photo_url }}"
                             data-id="{{ $dataTypeContent->id }}"
                             data-photo-id="{{ $relationshipData->{$options->column} }}"

                             style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
                    </div>
                @endif
                <input type="hidden" name="image_exists" value="{{ $relationshipData->{$options->column} ? 1 : 0 }}">

                <input @if($row->required == 1 && !isset($relationshipData->{$options->column})) required @endif type="file" name="{{ $row->field }}" accept="image/*">
            @endif

        @endif
    @endif

@endif


