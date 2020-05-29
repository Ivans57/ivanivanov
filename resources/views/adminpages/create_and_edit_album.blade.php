@extends('partial')

@section('partialcontent')
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/albums/" : "/ru/admin/albums/",
                         'data-localization' => App::isLocale('en') ? "en" : "ru",
                         'id' => 'admin_panel_create_edit_delete_album_form' ]) !!}
    @else
        {!! Form::model($edited_album, [ 'method' => 'PUT', 
                                         'url' => App::isLocale('en') ? "/admin/albums/".$edited_album->keyword : 
                                         "/ru/admin/albums/".$edited_album->keyword,
                                         'data-localization' => App::isLocale('en') ? "en" : "ru",
                                         'id' => 'admin_panel_create_edit_delete_album_form' ]) !!}
    @endif
        @component('adminpages/create_edit_album_folder_fields', ['albums' => $albums, 'parent_id' => $parent_id, 'parent_name' => $parent_name])
            @slot('old_keyword')
                <!-- We need to pass an old keyword to validation because we need to compare it with a new keyword to avoid any misunderstanding 
                when do keyword uniqueness check. When we edit existing record we might change something without changing a keyword. 
                If we don't compare new keyword with its previous value, the system might think keyword 
                is not unique as user is trying to assign already existing keyword. -->
                {!! Form::hidden('old_keyword', $create_or_edit==='create' ? null : $edited_album->keyword, ['id' => 'old_keyword']) !!}
            @endslot
        @endcomponent                 
        {!! Form::close() !!}
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <!-- The first line below we need to make small images working (e.g. magnifying glass on search button). -->
            <script src="https://kit.fontawesome.com/de385ec762.js" crossorigin="anonymous"></script>
            <script type="text/javascript" src="{{ URL::asset('js/albums_create_edit_delete.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop