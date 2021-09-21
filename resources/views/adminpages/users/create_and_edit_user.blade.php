@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users/" : "/ru/admin/users/"]) !!}
    @else
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/users/".$keyword : "/ru/admin/users/".$keyword, 'method' => 'PUT' ]) !!}
    @endif
            {!! Form::hidden('old_keyword', $create_or_edit==='create' ? null : $keyword) !!}
            <div class='admin-panel-create-edit-entity'>
                <div class="admin-panel-create-edit-entity-controls">
                    <div>{!! Form::label('username', Lang::get('keywords.UserName').':', 
                            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
                    </div>
                    <div>{!! Form::text('username', $create_or_edit==='create' ? null : $keyword, 
                        ['class' => 'admin-panel-create-edit-entity-controls-input']) !!}
                    </div>
                </div>
                <div class="admin-panel-create-edit-entity-regulations"><span>@lang('keywords.UserNameRegulations')</span></div>
                <div class="admin-panel-create-edit-entity-controls">
                    <div>{!! Form::label('email', Lang::get('keywords.Email').':', 
                            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
                    </div>
                    <div>{!! Form::email('email', $create_or_edit==='create' ? null : $section, 
                        ['class' => 'admin-panel-create-edit-entity-controls-input']) !!}
                    </div>
                </div>
                <!-- As users css is slightly different from other sections css for create and edit, I will add what is different right in html. -->
                <div class="admin-panel-create-edit-entity-controls" style="margin-bottom: 2px;">
                    <div>{!! Form::label('password', Lang::get('keywords.Password').':', 
                                        ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}</div>
                    <!-- The password field below uses simple html, because for some reason cannot apply css to Form inputs with type password.-->
                    <div><input type="password" class="admin-panel-create-edit-entity-controls-input" id="password" name="password"></div>
                </div>
                <div class="admin-panel-create-edit-entity-regulations"><span>@lang('keywords.PasswordRegulations')</span></div>
                <div class="admin-panel-create-edit-entity-controls" style="margin-bottom: 40px;">
                    <div>{!! Form::label('password_confirmation', Lang::get('keywords.ConfirmPassword').':', 
                                        ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}</div>
                    <!-- The password field below uses simple html, because for some reason cannot apply css to Form inputs with type password.-->
                    <div><input type="password" class="admin-panel-create-edit-entity-controls-input" id="password_confirmation" 
                                name="password_confirmation"></div>
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