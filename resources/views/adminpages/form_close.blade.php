@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')

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