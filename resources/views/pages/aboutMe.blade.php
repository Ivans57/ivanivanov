@extends('app')

@section('content')
<!-- Because we need to show one article in this section, the same classes as for normal articles will be applied. -->
<article class="website-main-article articles-article">
    <div class="article-wrapper">
        <div class="article-body">
            <p>{!! $article_body !!}</p>
        </div>
    </div>
</article>
@stop