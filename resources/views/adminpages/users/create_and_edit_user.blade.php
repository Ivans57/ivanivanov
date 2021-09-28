@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users/" : "/ru/admin/users/"]) !!}
    @else
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users/".$name : "/ru/admin/users/".$name, 'method' => 'PUT' ]) !!}
    @endif
            {!! Form::hidden('old_username', $create_or_edit==='create' ? null : $name) !!}
            {!! Form::hidden('old_email', $create_or_edit==='create' ? null : $email) !!}
            <div class='admin-panel-create-edit-entity'>
                <div class="admin-panel-create-edit-entity-controls">
                    <div>{!! Form::label('name', Lang::get('keywords.UserName').':', 
                            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
                    </div>
                    <div>{!! Form::text('name', $create_or_edit==='create' ? null : $name, 
                        ['class' => 'admin-panel-create-edit-entity-controls-input']) !!}
                    </div>
                </div>
                <div class="admin-panel-create-edit-entity-regulations"><span>@lang('keywords.UserNameRegulations')</span></div>
                <div class="admin-panel-create-edit-entity-controls">
                    <div>{!! Form::label('email', Lang::get('keywords.Email').':', 
                            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
                    </div>
                    <div>{!! Form::email('email', $create_or_edit==='create' ? null : $email, 
                        ['class' => 'admin-panel-create-edit-entity-controls-input']) !!}
                    </div>
                </div>
                <!-- As users css is slightly different from other sections css for create and edit, I will add what is different right in html. -->
                <div class="admin-panel-create-edit-entity-controls" style="margin-bottom: 2px;">
                    <div>{!! Form::label('password', $create_or_edit==='create' ? Lang::get('keywords.Password').':' : 
                                          Lang::get('keywords.NewPassword').':', 
                                        ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}</div>
                    <!-- The password field below uses simple html, because for some reason cannot apply css to Form inputs with type password.-->
                    <div><input type="password" class="admin-panel-create-edit-entity-controls-input" id="password" name="password"></div>
                </div>
                <div class="admin-panel-create-edit-entity-regulations"><span>@lang('keywords.PasswordRegulations')</span></div>
                <div class="admin-panel-create-edit-entity-controls" style="margin-bottom: 20px;">
                    <div>{!! Form::label('password_confirmation', $create_or_edit==='create' ? Lang::get('keywords.ConfirmPassword').':' : 
                                          Lang::get('keywords.ConfirmNewPassword').':', 
                                        ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}</div>
                    <!-- The password field below uses simple html, because for some reason cannot apply css to Form inputs with type password.-->
                    <div><input type="password" class="admin-panel-create-edit-entity-controls-input" id="password_confirmation" 
                                name="password_confirmation"></div>
                </div>
                <div class="admin-panel-create-edit-entity-controls" style="margin-bottom: 40px;">
                    <div class='admin-panel-create-edit-entity-controls-label-wrapper-for-status-select-for-user'>
                        {!! Form::label('status', Lang::get('keywords.Status').':', 
                                        ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
                    </div>
                    <div class='admin-panel-create-edit-entity-controls-input-status-select-wrapper-for-user'>
                        {!! Form::select('status', array(1 => Lang::get('keywords.Active'), 0 => Lang::get('keywords.Inactive')), 
                                         $create_or_edit==='create' ? 1 : $status); !!}
                    </div>
                </div>
                <div class="admin-panel-create-edit-entity-controls">
                    {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-create-edit-entity-controls-button']) !!}
                    {!! Form::button(Lang::get('keywords.Cancel'), 
                    ['class' => 'admin-panel-create-edit-entity-controls-button', 'id' => 'button_cancel']) !!}
                </div>
            </div>    
        {!! Form::close() !!}   
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/pop_up_window_cancel.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop