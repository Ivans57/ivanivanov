@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/pop_up_window_cancel.js') }}"></script>
            @if ($add_edit_or_delete==='edit')
                <script type="text/javascript" src="{{ URL::asset('js/user_edit_within_section.js') }}"></script>
            @endif
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop