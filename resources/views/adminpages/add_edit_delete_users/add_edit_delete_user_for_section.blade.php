@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    @if ($add_edit_or_delete==='add')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users-add-edit-delete-for-section" : 
                                                        "/ru/admin/users-add-edit-delete-for-section"]) !!}
    @elseif ($add_edit_or_delete==='edit')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users-add-edit-delete-for-section" : 
                                                        "/ru/admin/users-add-edit-delete-for-section", 
                         'method' => 'PUT' ]) !!}
    @else
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users-add-edit-delete-for-section" : 
                                                        "/ru/admin/users-add-edit-delete-for-section", 
                         'method' => 'DELETE' ]) !!}
    @endif
        <div class='admin-panel-add-edit-delete-user-to-section'>
            {!! Form::hidden('section', $section, ['id' => 'section']); !!}
            @include('adminpages.add_edit_delete_users.add_edit_delete_user_fields')
        </div>
    {!! Form::close() !!}
@stop
@include('adminpages.add_edit_delete_users.add_edit_delete_user_js')