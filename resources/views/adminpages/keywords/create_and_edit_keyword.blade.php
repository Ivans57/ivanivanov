@extends('partial')

<!-- Some elements have data-... attributes. These attributes contain some phrases 
for messages might be called by javascript later. We need to keep these attributes, 
so we don't need to translate phrases with javascript. There might be some difficulties
to translate keywords via javascript as I am taking my keywords from the database-->
@section('partialcontent')
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/" : "/ru/admin/keywords/", 'id' => 'admin_panel_create_edit_delete_keyword_form') !!}
    @else
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/".$keyword : "/ru/admin/keywords/".$keyword, 
                        'id' => 'admin_panel_create_edit_delete_keyword_form', 'method' => 'PUT' ]) !!}
    @endif
            {!! Form::hidden('old_keyword', $create_or_edit==='create' ? null : $keyword_to_edit_keyword, ['id' => 'old_keyword']) !!}
            <div class='admin-panel-keywords-create-edit-keyword'>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', 
                            ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}
                    </div>
                    <div>{!! Form::text('keyword', $create_or_edit==='create' ? null : $keyword_to_edit_keyword, 
                        ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input', 'id' => 'keyword_input']) !!}
                    </div>
                </div>
                <div class="admin-panel-keywords-create-edit-keyword-regulations"><span>@lang('keywords.KeywordRegulations')</span></div>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    <div>{!! Form::label('text', Lang::get('keywords.Text').':', ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}</div>
                    <div>{!! Form::textarea('text', $create_or_edit==='create' ? null : $keyword_to_edit_text, 
                            ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input', 'rows' => 3, 'id' => 'text_input']) !!}
                    </div>
                </div>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    @if ($create_or_edit==='create')
                        {!! Form::button(Lang::get('keywords.Save'), ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button',
                        'id' => 'admin_panel_keywords_create_edit_keyword_controls_button_save']) !!}
                    @else
                        {!! Form::button(Lang::get('keywords.Save'), ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button',
                        'id' => 'admin_panel_keywords_create_edit_keyword_controls_button_update']) !!}
                    @endif
                    {!! Form::button(Lang::get('keywords.Cancel'), 
                    ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button', 
                    'id' => 'admin_panel_keywords_create_edit_delete_keyword_controls_button_cancel']) !!}
                </div>
            </div>
    
        {!! Form::close() !!}
   
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/keyword_create_edit.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop