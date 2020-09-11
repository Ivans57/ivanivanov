@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    {!! Form::open([ 'method' => 'DELETE', 'url' => App::isLocale('en') ? "/admin/".$section."/".$entity_types_and_keywords : 
                    "/ru/admin/".$section."/".$entity_types_and_keywords ]) !!}        
        <div class='admin-panel-delete-entity'>
            {!! Form::hidden('keyword', $entity_types_and_keywords) !!}
            <div class="admin-panel-delete-entity-message"><h3>
                {!! ($section === 'albums') ? Lang::get('keywords.DeleteAlbum').'?' : Lang::get('keywords.DeleteFolder').'?' !!}
            </h3></div>
            <div class="admin-panel-delete-entity-regulations"><span>
                {!! ($section === 'albums') ? Lang::get('keywords.AlbumDeleteRules') : Lang::get('keywords.FolderDeleteRules') !!}
            </span></div>
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
