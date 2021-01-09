@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-keywords">
    <div class="admin-panel-keywords-control-buttons">
        <div class="admin-panel-keywords-add-keyword-wrapper">
            <div class="admin-panel-keywords-add-keyword-button">
                <a href={{ App::isLocale('en') ? "/admin/keywords/create" : "/ru/admin/keywords/create" }} 
                class="admin-panel-keywords-add-keyword-button-link" data-fancybox data-type="iframe">
                    @lang('keywords.AddKeyword')
                </a>   
            </div>
        </div>
        <div class="admin-panel-keywords-edit-delete-control-buttons">
            <div>    
                {!! Form::button(Lang::get('keywords.Edit'), 
                [ 'class' => 'admin-panel-keywords-edit-delete-control-button 
                admin-panel-keywords-edit-delete-control-button-disabled', 
                'id' => 'keywords_button_edit', 'disabled' ]) !!}
            </div>
            <div>
                {!! Form::button(Lang::get('keywords.Delete'), 
                [ 'class' => 'admin-panel-keywords-edit-delete-control-button 
                admin-panel-keywords-edit-delete-control-button-disabled', 
                'id' => 'keywords_button_delete', 'disabled' ]) !!}
            </div>           
        </div>
    </div>
    @if ($all_keywords_amount > 1)
        <div>
            {!! Form::text('keyword_search', null, 
                ['class' => 'some-class', 
                 'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'keyword_search', 'id' => 'keyword_search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                    ['class' => 'some-class', 'id' => 'keyword_search_button', 'title' => Lang::get('keywords.FindInDataBase'), 
                     'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
        </div>
    @endif
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-keywords-content">
        @include('adminpages.adminkeywords_content')
    </div>
</article>

@stop


