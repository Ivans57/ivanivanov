@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-albums">
    <div class="admin-panel-albums-control-buttons">
        @include('adminpages.albums.adminalbums_controlbuttons')
    </div>   
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-albums-content">
        @include('adminpages.albums.adminalbums_content')
    </div>
</article>

@stop
