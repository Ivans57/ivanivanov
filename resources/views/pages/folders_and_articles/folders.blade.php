@extends('app')

@section('content')
<article class="website-main-article articles-article-folders">
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-albums-or-articles-content">
        @include('pages.folders_and_articles.folders_content')
    </div>
</article>
@stop