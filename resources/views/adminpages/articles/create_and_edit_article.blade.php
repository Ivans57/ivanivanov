@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">  
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/admin/articles" : "/ru/admin/articles" }} class="path-panel-text">@lang('keywords.Articles')</a>
        <span class="path-panel-text"> /</span>
        @if ($parents > 0)    
            <!--The component below is based on paginator component-->
            @component('path_panel', ['parents' => $parents, 'is_admin_panel' => true])
                @slot('section')
                    {{ $section }}
                @endslot
            @endcomponent
        @endif
    </div>
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'method' => 'POST', 
                         'url' => App::isLocale('en') ? "/admin/article/" : "/ru/admin/article/",
                         'data-localization' => App::isLocale('en') ? "en" : "ru",
                         'data-section' => $section,
                         'data-mode' => $mode,
                         'id' => 'admin_panel_create_edit_entity_form',
                         'enctype' => 'multipart/form-data' ]) !!}
    @else
        {!! Form::model($edited_article, [ 'method' => 'PUT',
                                             'url' => App::isLocale('en') ? "/admin/article/".$edited_article->keyword : 
                                             "/ru/admin/article/".$edited_article->keyword,
                                             'data-localization' => App::isLocale('en') ? "en" : "ru",
                                             'data-section' => $section,
                                             'data-mode' => $mode,
                                             'id' => 'admin_panel_create_edit_entity_form' ]) !!}
    @endif
    <div>
        <h2>{{ $headTitle }}</h2>
    </div>
        @component('adminpages/articles/create_edit_article_fields', ['parent_id' => $parent_id, 'parent_name' => $parent_name, 
                                                                      'parent_keyword' => $parent_keyword,    
                                                                      'section' => $section, 'create_or_edit' => $create_or_edit])
            @slot('old_keyword')
                <!-- We need to pass an old keyword to validation because we need to compare it with a new keyword to avoid any misunderstanding 
                when do keyword uniqueness check. When we edit existing record we might change something without changing a keyword. 
                If we don't compare new keyword with its previous value, the system might think keyword 
                is not unique as user is trying to assign already existing keyword. -->
                {!! Form::hidden('old_keyword', $create_or_edit==='create' ? null : $edited_article->keyword, ['id' => 'old_keyword']) !!}
            @endslot
        @endcomponent                 
        {!! Form::close() !!}
</article>
@stop
