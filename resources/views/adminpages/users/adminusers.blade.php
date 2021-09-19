@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-users">
    <div class="admin-panel-users-control-buttons">
        @include('adminpages.users.adminusers_controlbuttons')
    </div>
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-users-content">
        @include('adminpages.users.adminusers_content')
    </div>
</article>

@stop


