@extends('partial')

<!-- Some elements have data-... attributes. These attributes contain some phrases 
for messages might be called by javascript later. We need to keep these attributes, 
so we don't need to translate phrases with javascript. There might be some difficulties
to translate keywords via javascript as I ma taking my keywords from the database-->
@section('partialcontent')
    <div class="admin-panel-keywords-create-notification-wrapper"></div>
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/" : "/ru/admin/keywords/", 'id' => 'admin_panel_create_edit_delete_keyword_form', 'data-processing_option' => $create_or_edit ]) !!}
    @else
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/".$keyword_to_edit_id : "/ru/admin/keywords/".$keyword_to_edit_id, 'id' => 'admin_panel_create_edit_delete_keyword_form', 'data-processing_option' => $create_or_edit ]) !!}
    @endif
            <div class='admin-panel-keywords-create-edit-keyword'>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}</div>
                    <div>{!! Form::text('keyword', $create_or_edit==='create' ? null : $keyword_to_edit_keyword, ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input',
                        'maxlength' => 50, 'id' => 'keyword_input', 'data-message' => Lang::get('keywords.TooManyCharactersInInputField'), 
                        'data-keywords' => $keywords, 'data-uniqueness' => Lang::get('keywords.KeywordNotUnique'), 
                        'data-spaces' => Lang::get('keywords.KeywordShouldNotHaveSpaces'), 
                        'data-symbols' => Lang::get('keywords.ProhibitedSymbols') ]) !!}</div>
                </div>
                <div class="admin-panel-keywords-create-edit-keyword-regulations"><span>@lang('keywords.KeywordRegulations')</span></div>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    <div>{!! Form::label('text', Lang::get('keywords.Text').':', ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}</div>
                    <div>{!! Form::textarea('text', $create_or_edit==='create' ? null : $keyword_to_edit_text, ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input', 'rows' => 3, 'id' => 'text_input']) !!}</div>
                </div>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    @if ($create_or_edit==='create')
                        {!! Form::button(Lang::get('keywords.Save'), ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button',
                        'id' => 'admin_panel_keywords_create_edit_keyword_controls_button_save',
                        'data-message' => Lang::get('keywords.EmptyFields') ]) !!}
                    @else
                        {!! Form::button(Lang::get('keywords.Save'), ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button',
                        'id' => 'admin_panel_keywords_create_edit_keyword_controls_button_update', 
                        'data-message' => Lang::get('keywords.EmptyFields') ]) !!}
                    @endif
                    {!! Form::button(Lang::get('keywords.Cancel'), 
                    ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button', 
                    'id' => 'admin_panel_keywords_create_edit_delete_keyword_controls_button_cancel']) !!}
                </div>
            </div>
    
        {!! Form::close() !!}
   
@stop