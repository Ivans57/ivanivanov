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
            {!! Form::hidden('directory', $directory, ['id' => 'directory']); !!}
            <div class="admin-panel-add-edit-delete-user-to-section-controls">
                <div>{!! Form::label('users', Lang::get('keywords.SelectUser').':', 
                                    ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-label']) !!}</div>
                @if ($add_edit_or_delete === 'add' || $add_edit_or_delete === 'delete')
                    <div>{!! Form::select('users', $users, null, ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-input', 
                                          $users_array_size == 0 ? 'disabled' : 'enabled']) !!}</div>
                @elseif ($add_edit_or_delete === 'edit') 
                    <select name='users' class='admin-panel-add-edit-delete-user-to-section-controls-input' id='users' 
                            {{ $users_array_size == 0 ? 'disabled' : 'enabled' }}>
                        @foreach($users as $key => $value)
                            <option value="{{ $key }}" data-access='{{ $value->access }}'>{{ $value->name }}</option>
                        @endforeach
                    </select>                      
                @endif
            </div>
            @include('adminpages.add_edit_delete_users.add_edit_delete_user_fields')
        </div>
    {!! Form::close() !!}
@stop
@include('adminpages.add_edit_delete_users.add_edit_delete_user_js')