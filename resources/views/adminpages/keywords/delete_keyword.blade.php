@extends('partial')

@section('partialcontent')
    <!--Need to find out is it possible to pass id using just a variable in
    url, so we can get rid of the hidden field.
    The same approcah can be used for update as well.-->
    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/".$keyword : "/ru/admin/keywords/".$keyword, 'method' => 'DELETE']) !!}      
        <div class='admin-panel-keywords-delete-keyword'>
            <div class="admin-panel-keywords-delete-keyword-message"><h3>@lang('keywords.DeleteKeyword')?</h3></div>
            <div class="admin-panel-keywords-delete-keyword-controls">
                {!! Form::submit(Lang::get('keywords.Delete'), ['class' => 'admin-panel-keywords-delete-keyword-controls-button' ]) !!}               
                {!! Form::button(Lang::get('keywords.Cancel'), 
                ['class' => 'admin-panel-keywords-delete-keyword-controls-button', 'id' => 'keywords_button_cancel']) !!}
            </div>
        </div>
    
    {!! Form::close() !!}
   
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/keyword_create_edit_delete.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop
