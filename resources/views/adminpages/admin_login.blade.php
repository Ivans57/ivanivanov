@extends('adminpages.admin_auth')

@section('admin_login')
    @include('adminpages.create_edit_errors')
    {!! Form::open([ 'method' => 'POST', 'url' => App::isLocale('en') ? "/admin/" : "/ru/admin/" ]) !!}
        {{ csrf_field() }}    
        <h1>@lang('keywords.Authorization')</h1>
        <div class="admin-panel-auth-wrapper-caption-fields-wrapper">
            <div>    
                <div>{!! Form::label('name', Lang::get('keywords.UserName'), ['class' => 'admin-panel-auth-wrapper-fields-controls-label']) !!}</div>  
                <div>{!! Form::text('name', null, ['class' => 'admin-panel-auth-wrapper-fields-controls-input']) !!}</div>
                <div>{!! Form::label('password', Lang::get('keywords.Password'), ['class' => 'admin-panel-auth-wrapper-fields-controls-label']) !!}</div>
                <div>{!! Form::password('password', ['class' => 'admin-panel-auth-wrapper-fields-controls-input']) !!}</div>
            </div>
        </div>
        <div class="admin-panel-auth-wrapper-button-wrapper">
            {!! Form::submit(Lang::get('keywords.Login'), ['class' => 'btn btn-primary admin-panel-auth-wrapper-button']) !!}
        </div>
            
    {!! Form::close() !!}
@stop