@extends('partial')

@section('partialcontent')
    
    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/{keyword_id}" : "/ru/admin/keywords/{keyword_id}", 'method' => 'delete', 'id' => 'admin_panel_delete_keyword_form' ]) !!}
        
        <div class='admin-panel-keywords-delete-keyword'>
            {!! Form::hidden('keyword_id', $keyword_id) !!}
            <div><h3>Are you sure you want to delete the keyword?</h3></div>
            <div class="admin-panel-keywords-delete-keyword-controls">
                {!! Form::button(Lang::get('keywords.Delete'), ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button
                admin-panel-keywords-create-edit-keyword-controls-button-save' ]) !!}

                {!! Form::button(Lang::get('keywords.Cancel'), 
                ['class' => 'admin-panel-keywords-delete-keyword-controls-button
                admin-panel-keywords-delete-keyword-controls-button-cancel']) !!}
            </div>
        </div>
    
    {!! Form::close() !!}
   
@stop

