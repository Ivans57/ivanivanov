@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    <div>{!! Form::label('users', Lang::get('keywords.SelectUser').':', 
                            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}</div>
    <div>{!! Form::select('users', $users) !!}</div>
    <div class="admin-panel-create-edit-entity-controls">
        {!! Form::label('full_access', Lang::get('keywords.ProvideFullAccess').':', ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        {!! Form::checkbox('full_access', 1) !!}
    </div>
    <div class="admin-panel-create-edit-entity-controls">
        {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-create-edit-entity-controls-button']) !!}
        {!! Form::button(Lang::get('keywords.Cancel'), 
                        ['class' => 'admin-panel-create-edit-entity-controls-button', 'id' => 'button_cancel']) !!}
    </div>
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