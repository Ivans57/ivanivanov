@extends('app')

@section('content')
<article class="website-main-article home-article">
        <h2>{{ $article_title }}</h2>
        <p>{{ $article_body }}</p>
        
        @if (App::isLocale('en'))
        <div class="home-know-more-button" id="know-more-button">
            <a href='{{ action('AboutMeController@index') }}' class="home-know-more-button-link">@lang('homeContent.KnowMore')</a>
        <div>
        @else
        <div class="home-know-more-button" id="know-more-button">
            <a href='/ru/about-me' class="home-know-more-button-link">@lang('homeContent.KnowMore')</a>
        </div>
        @endif
</article>           
@stop
