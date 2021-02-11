@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    <!-- Before opening form_close need to assign variables to meet conditions for javascript to open root folder for articles after delete.-->
    {!! Form::open(['method' => 'DELETE', 'url' => (App::isLocale('en') ? "/": "/ru/")."admin/".$section."/".$section."/".$entity_types_and_keywords."/".
                                                   (($search_is_on === "0") ? $parent_keyword : 0)."/".$search_is_on]) !!}        
        <div class='admin-panel-delete-entity'>
            {!! Form::hidden('entity_types_and_keywords', $entity_types_and_keywords) !!}
            {!! Form::hidden('parent_keyword_and_section', (($search_is_on === "0") ? $parent_keyword : "0"), ['id' => 'parent_keyword_and_section', 'data-section' => $section]) !!}
            @if ($plural_or_singular==='singular')
                <div class="admin-panel-delete-entity-message"><h3>
                    {!! ($section === 'albums') ? Lang::get('keywords.DeleteAlbum').'?' : Lang::get('keywords.DeleteFolder').'?' !!}
                </h3></div>
                <div class="admin-panel-delete-entity-regulations"><span>
                    {!! ($section === 'albums') ? Lang::get('keywords.AlbumDeleteRules') : Lang::get('keywords.FolderDeleteRules') !!}
                </span></div>
            @else
                <div class="admin-panel-delete-entity-message"><h3>
                    {!! ($section === 'albums') ? Lang::get('keywords.DeleteAlbums').'?' : Lang::get('keywords.DeleteFolders').'?' !!}
                </h3></div>
                <div class="admin-panel-delete-entity-regulations"><span>
                    {!! ($section === 'albums') ? Lang::get('keywords.AlbumsDeleteRules') : Lang::get('keywords.FoldersDeleteRules') !!}
                </span></div>
            @endif
            <div class="admin-panel-delete-entity-controls">
                <div>{!! Form::submit(Lang::get('keywords.Delete'), ['class' => 'admin-panel-delete-entity-controls-button' ]) !!}</div>

                <div>{!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-delete-entity-controls-button', 
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
