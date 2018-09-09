@extends('app')

@section('content')
<article class="website-main-article articles-article">
    <div>
       <h2>{{ $headTitle }}</h2>
    </div>
    <section class="article-wrapper">
        <div class="article-description">
            <p>{{ $article_description }}</p>
        </div>
        <div class="article-body">
            <p>{!! $article_body !!}</p>
        </div>
        <div class="article-author">
            <p>@lang('articleContent.Author'): <b>{{ $article_author }}</b></p>
        </div>
        <div class="article-source">
            <p>@lang('articleContent.Source'): <b>{{ $article_source }}</b></p>
        </div>
        <div class="article-creation-time">
            <p>@lang('articleContent.Time'): <b>{{ $created_at }}</b></p>
        </div>
    </section>
</article>
@stop