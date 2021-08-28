@extends('adminpages.admin_auth')

@section('admin_login')
    @include('adminpages.create_edit_errors')
        {!! Form::open([ 'method' => 'POST', 'url' => "/admin/" ]) !!}
            {{ csrf_field() }}
            
            <h1>Login</h1>
            
            <div>
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name') !!}
            </div>
            <div style="height:20px;"></div>
            <div>
                {!! Form::label('password', 'Password') !!}
                {!! Form::password('password') !!}
            </div>
            <div style="height:20px;"></div>
            <div>
                {!! Form::submit('Login') !!}
            </div>
            
        {!! Form::close() !!}
@stop