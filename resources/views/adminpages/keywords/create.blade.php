@extends('partial')

<!-- Some elements have data-... attributes. These attributes contain some phrases 
for messages might be called by javascript later. We need to keep these attributes, 
so we don't need to translate phrases with javascript. There might be some difficulties
to translate keywords via javascript as I ma taking my keywords from the database-->
@section('partialcontent')
    <div class="admin-panel-keywords-create-notification-wrapper"></div>
    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords" : "/ru/admin/keywords", 'id' => 'admin-panel-create-keyword-form' ]) !!}
        
        <div class='admin-panel-keywords-create-edit-keyword'> 
            <div class="admin-panel-keywords-create-edit-keyword-controls">
                <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}</div>
                <div>{!! Form::text('keyword', null, ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input admin-panel-keywords-create-edit-keyword-controls-input-keyword',
                    'maxlength' => 50, 'id' => 'keyword_input', 'data-message' => Lang::get('keywords.TooManyCharactersInInputField'), 
                    'data-keywords' => $keywords, 'data-uniqueness' => Lang::get('keywords.KeywordNotUnique'), 
                    'data-spaces' => Lang::get('keywords.KeywordShouldNotHaveSpaces'), 
                    'data-symbols' => Lang::get('keywords.ProhibitedSymbols') ]) !!}</div>
            </div>
            <div class="admin-panel-keywords-create-edit-keyword-controls">
                <div>{!! Form::label('text', Lang::get('keywords.Text').':', ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}</div>
                <div>{!! Form::textarea('text', null, ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input admin-panel-keywords-create-edit-keyword-controls-input-text', 'rows' => 3]) !!}</div>
            </div>
            <div class="admin-panel-keywords-create-edit-keyword-controls">
                {!! Form::button(Lang::get('keywords.Save'), ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button
                admin-panel-keywords-create-edit-keyword-controls-button-save', 'data-message' => Lang::get('keywords.EmptyFields') ]) !!}
                {!! Form::button(Lang::get('keywords.Cancel'), 
                ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button
                admin-panel-keywords-create-edit-keyword-controls-button-cancel']) !!}
            </div>
        </div>
    
    {!! Form::close() !!}

    
    
@stop