@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    {!! Form::text('parent_keyword_and_section', $parent_keyword, ['id' => 'parent_keyword_and_section', 'data-section' => $section]) !!}
    {!! Form::text('parent_directory_is_empty', $parent_directory_is_empty, ['id' => 'parent_directory_is_empty']) !!}
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