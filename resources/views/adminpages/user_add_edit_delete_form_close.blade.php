@extends('create_edit_delete_window')

@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/user_add_edit_delete_form_close.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop