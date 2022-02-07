@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    @if ($add_edit_or_delete==='add')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users-add-edit-delete-for-directory" : 
                                                        "/ru/admin/users-add-edit-delete-for-directory"]) !!}
    @elseif ($add_edit_or_delete==='edit')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users-add-edit-delete-for-directory" : 
                                                        "/ru/admin/users-add-edit-delete-for-directory", 
                         'method' => 'PUT' ]) !!}
    @else
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users-add-edit-delete-for-directory" : 
                                                        "/ru/admin/users-add-edit-delete-for-directory", 
                         'method' => 'DELETE' ]) !!}
    @endif
        <div class='admin-panel-add-edit-delete-user-to-section'>
            {!! Form::hidden('section', $section, ['id' => 'section']); !!}
            {!! Form::hidden('directory', $directory, ['id' => 'directory']); !!}
            @include('adminpages.add_edit_delete_users.add_edit_delete_user_fields')
        </div>
    {!! Form::close() !!}
@stop
@include('adminpages.add_edit_delete_users.add_edit_delete_user_js')