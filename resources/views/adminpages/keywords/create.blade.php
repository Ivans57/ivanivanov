@extends('partial')

@section('partialcontent')

    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords" : "/ru/admin/keywords", 'id' => 'admin-panel-create-keyword-form' ]) !!}
    
        <div class='admin-panel-keywords-create-edit-keyword'> 
            <div class="admin-panel-keywords-create-edit-keyword-controls">
                <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}</div>
                <div>{!! Form::text('keyword', null, ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input']) !!}</div>
            </div>
            <div class="admin-panel-keywords-create-edit-keyword-controls">
                <div>{!! Form::label('text', Lang::get('keywords.Text').':', ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}</div>
                <div>{!! Form::text('text', null, ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input']) !!}</div>
            </div>
            <div class="admin-panel-keywords-create-edit-keyword-controls">
                {!! Form::button(Lang::get('keywords.Save'), ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button
                admin-panel-keywords-create-edit-keyword-controls-button-save']) !!}
                {!! Form::button(Lang::get('keywords.Cancel'), 
                ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button
                admin-panel-keywords-create-edit-keyword-controls-button-cancel']) !!}
            </div>
        </div>
    
    {!! Form::close() !!}

@stop