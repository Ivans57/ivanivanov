@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    <div class='admin-panel-add-edit-delete-user-to-section'>
        <div class="admin-panel-add-edit-delete-user-to-section-controls">
            <div>{!! Form::label('users', Lang::get('keywords.SelectUser').':', 
                                ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-label']) !!}</div>
            <div>{!! Form::select('users', $users, null, ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-input']) !!}</div>
        </div>
        @if (($add_edit_or_delete == 'add') || ($add_edit_or_delete == 'edit'))
            <div class="admin-panel-add-edit-delete-user-to-section-controls">
                {!! Form::label('full_access', Lang::get('keywords.ProvideFullAccess').':', 
                               ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-label']) !!}
                {!! Form::checkbox('full_access', 1) !!}
            </div>
        @endif
        <div class="admin-panel-add-edit-delete-user-to-section-controls">
            @if ($add_edit_or_delete == 'add')
                {!! Form::submit(Lang::get('keywords.Add'), ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-button']) !!}
            @elseif ($add_edit_or_delete == 'edit')
                {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-button']) !!}
            @elseif ($add_edit_or_delete == 'delete')
                {!! Form::submit(Lang::get('keywords.Delete'), ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-button']) !!}
            @endif
            {!! Form::button(Lang::get('keywords.Cancel'), 
                            ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-button', 'id' => 'button_cancel']) !!}
        </div>
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