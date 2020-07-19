@extends('partial')

@section('partialcontent')
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'method' => 'POST', 
                         'url' => App::isLocale('en') ? "/admin/".$section."/" : "/ru/admin/".$section."/",
                         'data-localization' => App::isLocale('en') ? "en" : "ru",
                         'data-section' => $section,
                         'id' => 'admin_panel_create_edit_directory_form',
                         'enctype' => 'multipart/form-data' ]) !!}
    @else
        {!! Form::model($edited_directory, [ 'method' => 'PUT', 
                                             'url' => App::isLocale('en') ? "/admin/".$section."/".$edited_picture->keyword : 
                                             "/ru/admin/".$section."/".$edited_picture->keyword,
                                             'data-localization' => App::isLocale('en') ? "en" : "ru",
                                             'data-section' => $section,
                                             'id' => 'admin_panel_create_edit_directory_form' ]) !!}
    @endif
        @component('adminpages/pictures/create_edit_picture_fields', ['parent_id' => $parent_id, 
                                                                      'parent_name' => $parent_name, 'section' => $section])
            @slot('old_keyword')
                <!-- We need to pass an old keyword to validation because we need to compare it with a new keyword to avoid any misunderstanding 
                when do keyword uniqueness check. When we edit existing record we might change something without changing a keyword. 
                If we don't compare new keyword with its previous value, the system might think keyword 
                is not unique as user is trying to assign already existing keyword. -->
                {!! Form::hidden('old_keyword', $create_or_edit==='create' ? null : $edited_directory->keyword, ['id' => 'old_keyword']) !!}
            @endslot
        @endcomponent                 
        {!! Form::close() !!}
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/directory_create_edit.js') }}"></script>
            <script type="text/javascript" src="{{ URL::asset('js/temporary.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop