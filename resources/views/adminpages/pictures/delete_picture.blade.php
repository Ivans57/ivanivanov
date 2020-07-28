@extends('partial')

@section('partialcontent')
    {!! Form::open([ 'method' => 'DELETE', 'url' => App::isLocale('en') ? "/admin/pictures/".$keyword : "/ru/admin/pictures/".$keyword ]) !!}        
        <div class='admin-panel-delete-directory'>
            {!! Form::hidden('keyword', $keyword) !!}
            <div class="admin-panel-delete-directory-message"><h3>
                {!! Lang::get('keywords.DeletePicture') !!}?
            </h3></div>
            <div class="admin-panel-delete-directory-controls">
                <div>{!! Form::submit(Lang::get('keywords.Delete'), ['class' => 'admin-panel-delete-directory-controls-button' ]) !!}</div>

                <div>{!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-delete-directory-controls-button', 
                        'id' => 'button_cancel' ]) !!}</div>                      
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
