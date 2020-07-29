@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    <!--Need to find out is it possible to pass id using just a variable in
    url, so we can get rid of the hidden field.
    The same approcah can be used for update as well.-->
    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/".$keyword : "/ru/admin/keywords/".$keyword, 'method' => 'DELETE']) !!}      
        <div class='admin-panel-delete-entity'>
            <div class="admin-panel-delete-entity-message"><h3>@lang('keywords.DeleteKeyword')?</h3></div>
            <div class="admin-panel-delete-entity-controls">
                {!! Form::submit(Lang::get('keywords.Delete'), ['class' => 'admin-panel-delete-entity-controls-button' ]) !!}               
                {!! Form::button(Lang::get('keywords.Cancel'), 
                ['class' => 'admin-panel-delete-entity-controls-button', 'id' => 'button_cancel']) !!}
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
