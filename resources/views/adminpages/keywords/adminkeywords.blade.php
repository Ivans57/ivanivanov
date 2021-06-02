@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-keywords">
    @if ($all_keywords_amount > 1)
        <div class="admin-panel-keywords-search">
            {!! Form::text('keyword_search', null, 
                ['class' => 'admin-panel-keywords-search-input', 
                 'placeholder' => Lang::get('keywords.SearchByText').'...', 'name' => 'keyword_search', 'id' => 'keyword_search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                    ['class' => 'admin-panel-keywords-search-button', 'id' => 'keyword_search_button', 'title' => Lang::get('keywords.Search'), 
                     'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
        </div>
    @endif
    <div class="admin-panel-keywords-control-buttons">
        @include('adminpages.keywords.adminkeywords_controlbuttons')
    </div>
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-keywords-content">
        @include('adminpages.keywords.adminkeywords_content')
    </div>
</article>

@stop


