@extends('app')

@section('content')
<article class="website-main-article articles-article">
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        @if (App::isLocale('en'))
            <a href='/articles' class="path-panel-text"> @lang('keywords.Articles')</a>
        @else
            <a href='/ru/articles' class="path-panel-text"> @lang('keywords.Articles')</a>
        @endif
        <span class="path-panel-text"> /</span>
        @foreach ($articleParents as $articleParent)
            @if (App::isLocale('en'))
                <a href='/articles/{{ $articleParent->keyWord }}/page/1' class="path-panel-text">{{ $articleParent->name }}</a>
            @else
                <a href='/ru/articles/{{ $articleParent->keyWord }}/page/1' class="path-panel-text">{{ $articleParent->name }}</a>
            @endif
                <span class="path-panel-text"> /</span>
        @endforeach
    </div>
    <div>
       <h2>{{ $headTitle }}</h2>
    </div>
    <div class="article-wrapper">
        <div class="article-description">
            <p>{{ $article_description }}</p>
        </div>
        <div class="article-body">
            <p>{!! $article_body !!}</p>
        </div>
        <div class="article-author">
            <p>@lang('keywords.ArticleAuthor'): <b>{{ $article_author }}</b></p>
        </div>
        <div class="article-source">
            <p>@lang('keywords.ArticleSource'): <b>{{ $article_source }}</b></p>
        </div>
        <div class="article-creation-time">
            <p>@lang('keywords.DateAndTime'): <b>{{ $created_at }}</b></p>
        </div>
    </div>
</article>
@stop