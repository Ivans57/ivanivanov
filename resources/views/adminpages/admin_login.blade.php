@extends('adminpages.admin_auth')

@section('admin_login')
    @include('adminpages.create_edit_errors')
        {!! Form::open([ 'method' => 'POST', 'url' => App::isLocale('en') ? "/admin/" : "/ru/admin/" ]) !!}
            {{ csrf_field() }}
            
            <h1>@lang('keywords.Authorization')</h1>
            
            <div>
                {!! Form::label('name', Lang::get('keywords.UserName')) !!}
                {!! Form::text('name') !!}
            </div>
            <div style="height:20px;"></div>
            <div>
                {!! Form::label('password', Lang::get('keywords.Password')) !!}
                {!! Form::password('password') !!}
            </div>
            <div style="height:20px;"></div>
            <div>
                {!! Form::submit(Lang::get('keywords.Login')) !!}
            </div>
            
        {!! Form::close() !!}
@stop