@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/".$section."/" : "/ru/admin/".$section."/",
                         'data-localization' => App::isLocale('en') ? "en" : "ru",
                         'data-section' => $section,
                         'data-mode' => $mode,
                         'id' => 'admin_panel_create_edit_entity_form' ]) !!}
    @else
        {!! Form::model($edited_directory, [ 'method' => 'PUT', 
                                         'url' => App::isLocale('en') ? "/admin/".$section."/".$edited_directory->keyword."/".$parent_keyword."/".$search_is_on : 
                                         "/ru/admin/".$section."/".$edited_directory->keyword."/".$parent_keyword."/".$search_is_on,
                                         'data-localization' => App::isLocale('en') ? "en" : "ru",
                                         'data-section' => $section,
                                         'data-mode' => $mode,
                                         'id' => 'admin_panel_create_edit_entity_form' ]) !!}
    @endif
        @component('adminpages/directory/create_edit_directory_fields', ['parent_id' => $parent_id, 
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
            <script type="text/javascript" src="{{ URL::asset('js/parent_search_and_select.js') }}"></script>
            <script type="text/javascript" src="{{ URL::asset('js/pop_up_window_cancel.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop