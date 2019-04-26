@extends('partial')

@section('partialcontent')
    
    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/{keyword_id}" : "/ru/admin/keywords/{keyword_id}", 
    'method' => 'delete', 'id' => 'admin_panel_create_keyword_form', 'data-processing_option' => 'delete' ]) !!}
        
        <div class='admin-panel-keywords-delete-keyword'>
            {!! Form::hidden('keyword_id', $keyword_id) !!}
            <div class="admin-panel-keywords-delete-keyword-message"><h3>@lang('keywords.DeleteKeyword')?</h3></div>
            <div class="admin-panel-keywords-delete-keyword-controls">
                {!! Form::button(Lang::get('keywords.Delete'), ['class' => 'admin-panel-keywords-delete-keyword-controls-button
                admin-panel-keywords-delete-keyword-controls-button-delete' ]) !!}

                {!! Form::button(Lang::get('keywords.Cancel'), 
                ['class' => 'admin-panel-keywords-delete-keyword-controls-button
                admin-panel-keywords-delete-keyword-controls-button-cancel']) !!}
            </div>
        </div>
    
    {!! Form::close() !!}
   
@stop

