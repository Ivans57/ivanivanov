@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    {!! Form::hidden('action', $action, ['id' => 'action']) !!}
    <!--Hidden fields below are required only when user is deleting an item. -->   
    @if ($action == 'update' || $action == 'destroy')
        {!! Form::hidden('parent_keyword_and_section', $parent_keyword, ['id' => 'parent_keyword_and_section', 'data-section' => $section, 
                                                                             'data-localization' => App::isLocale('en') ? "en" : "ru"]) !!}
    @endif                                                                     
    @if ($action == 'update')
        {!! Form::hidden('search_is_on', $search_is_on, ['id' => 'search_is_on']) !!}
    @endif                                                                     
    @if ($action == 'destroy')
        {!! Form::hidden('parent_directory_is_empty', $parent_directory_is_empty, ['id' => 'parent_directory_is_empty']) !!}
    @endif
@stop

@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/form_close.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop