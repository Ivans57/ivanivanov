@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    {!! Form::open(['method' => 'DELETE', 'url' => (App::isLocale('en') ? "/": "/ru/")."admin/".$section."/".$section."/".$entity_types_and_keywords."/".
                                                   (($search_is_on === "0") ? $parent_keyword : 0)."/".$search_is_on]) !!}       
        <div class='admin-panel-delete-entity'>
            {!! Form::hidden('entity_types_and_keywords', $entity_types_and_keywords) !!}
            {!! Form::hidden('parent_keyword_and_section', (($search_is_on === "0") ? $parent_keyword : "0"), ['id' => 'parent_keyword_and_section', 'data-section' => $section]) !!}
            @if ($plural_or_singular==='singular')
                <div class="admin-panel-delete-entity-message"><h3 style="color:#cf1b0e;">@lang('keywords.DeleteArticle')?</h3></div>
            @else
                <div class="admin-panel-delete-entity-message"><h3 style="color:#cf1b0e;">@lang('keywords.DeleteArticles')?</h3></div>
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
