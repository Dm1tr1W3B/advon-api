
@if($dataType->{$row->field})
<p class="text-success">{{$dataType->{$row->field} }}</p>
@else
    <p class="text-danger">{{($row->details->empty)}}</p>
@endif

