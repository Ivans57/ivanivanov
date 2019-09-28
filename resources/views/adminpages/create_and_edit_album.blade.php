@extends('partial')

<!-- Some elements have data-... attributes. These attributes contain some phrases 
for messages might be called by javascript later. We need to keep these attributes, 
so we don't need to translate phrases with javascript. There might be some difficulties
to translate keywords via javascript as I am taking my keywords from the database-->
@section('partialcontent')
    <h3>Hello World!</h3>
    <select style="height: 30px; width: 200px; font-size: 17px;">
        @foreach ($albums as $album)
            <option>{!! $album->album_name !!}</option>                   
        @endforeach
    </select>
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/albums_create_edit_delete.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop