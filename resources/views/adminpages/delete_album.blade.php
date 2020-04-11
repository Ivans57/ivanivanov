@extends('partial')

@section('partialcontent')
    {!! Form::open([ 'method' => 'DELETE', 'url' => App::isLocale('en') ? "/admin/albums/".$keyword : "/ru/admin/albums/".$keyword, 'id' => 'admin_panel_create_edit_delete_album_form' ]) !!}
        
        <div class='admin-panel-albums-delete-album'>
            {!! Form::hidden('keyword', $keyword) !!}
            <div class="admin-panel-albums-delete-album-message"><h3>@lang('keywords.DeleteAlbum')?</h3></div>
            <div class="admin-panel-albums-delete-album-regulations"><span>@lang('keywords.AlbumDeleteRules')</span></div>
            <div class="admin-panel-albums-delete-album-controls">
                <div>{!! Form::submit(Lang::get('keywords.Delete'), ['class' => 'admin-panel-albums-delete-album-controls-button' ]) !!}</div>

                <div>{!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-albums-delete-album-controls-button', 
                        'id' => 'admin_panel_albums_create_edit_delete_album_controls_button_cancel' ]) !!}</div>
                        
            </div>
        </div>
    
    {!! Form::close() !!}
   
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/albums_create_edit_delete.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop
