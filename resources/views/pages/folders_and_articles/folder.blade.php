@extends('app')

@section('content')

<article class="{{ $articleAmount < 1 ? "website-main-article articles-article-folders" : "website-main-article articles-article-folders-and-articles" }}">       
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/articles" : "/ru/articles" }} class="path-panel-text">@lang('keywords.Articles')</a>
        <span class="path-panel-text"> /</span>
        @if ($parents > 0)
            <!--The component below is based on paginator component-->
            @component('path_panel', ['parents' => $parents, 'is_admin_panel' => false])
                @slot('section')
                    {{ $section }}
                @endslot
            @endcomponent                
        @endif
    </div>
    <!-- The class below is only for js purposes -->
    <div>
        @include('pages.folders_and_articles.folder_folder_title')
    </div>
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-albums-or-articles-content">
        @include('pages.folders_and_articles.folder_content')
    </div>   
</article>
@stop