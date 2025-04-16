

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Изменить баланс')

@section('page_header')
    <h1 class="page-title">
        Изменить баланс
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div>Имя {{$data['userBalance']->name}}</div>
                <div>Email {{$data['userBalance']->email}}</div>
                <div>Баланс {{$data['userBalance']->balance}}</div>

                <div class="panel panel-bordered">

                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ route('users.update.balance') }}"
                            method="POST" enctype="multipart/form-data">


                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Тип операции</label>
                                <select class="form-control" name="type_id">
                                    @foreach($data['types'] as $type)
                                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <label class="control-label" for="amount">Сумма к начислению</label>
                            <input required="" type="number" class="form-control" name="amount" value="0.00">

                            <input type="hidden" name="user_id" value="{{$data['userBalance']->id}}">


                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')

@stop
