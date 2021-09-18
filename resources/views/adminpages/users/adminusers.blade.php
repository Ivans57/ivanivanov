@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    <div class="admin-panel-keywords-control-buttons">
        @include('adminpages.users.adminusers_controlbuttons')
    </div>
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-keywords-content">
        @include('adminpages.users.adminusers_content')
    </div>
</article>

@stop


