@extends('partial')

@section('partialcontent')
    {!! Form::open([ 'method' => 'DELETE', 
    'url' => App::isLocale('en') ? "/admin/".$section."/".$keyword : "/ru/admin/".$section."/".$keyword, 
    'id' => 'admin_panel_create_edit_delete_directory_form' ]) !!}
        
        <div class='admin-panel-delete-directory'>
            {!! Form::hidden('keyword', $keyword) !!}
            <div class="admin-panel-delete-directory-message"><h3>
                {!! ($section === 'albums') ? Lang::get('keywords.DeleteAlbum').'?' : Lang::get('keywords.DeleteFolder').'?' !!}
            </h3></div>
            <div class="admin-panel-delete-directory-regulations"><span>
                {!! ($section === 'albums') ? Lang::get('keywords.AlbumDeleteRules') : Lang::get('keywords.FolderDeleteRules') !!}
            </span></div>
            <div class="admin-panel-delete-directory-controls">
                <div>{!! Form::submit(Lang::get('keywords.Delete'), ['class' => 'admin-panel-delete-directory-controls-button' ]) !!}</div>

                <div>{!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-delete-directory-controls-button', 
                        'id' => 'directory_cancel_button' ]) !!}</div>
                        
            </div>
        </div>    
    {!! Form::close() !!}
   
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/directory_create_edit_delete.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop
