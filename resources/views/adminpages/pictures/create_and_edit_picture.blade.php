@extends('partial')

<!-- Some elements have data-... attributes. These attributes contain some phrases 
for messages might be called by javascript later. We need to keep these attributes, 
so we don't need to translate phrases with javascript. There might be some difficulties
to translate keywords via javascript as I am taking my keywords from the database-->
@section('partialcontent')
    @include('adminpages.create_edit_errors')
    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/pictures" : "/ru/admin/pictures", 'method' => 'POST', 'enctype' => "multipart/form-data" ]) !!}
        <div>
        <div>{!! Form::label('select_file', 'Select File for Upload') !!}</div>
        <div>{!! Form::file('select_file') !!}</div>
        <div>{!! Form::submit('Upload', ['name' => 'upload']) !!}</div>
    </div>
    {!! Form::close() !!}
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/keyword_create_edit_delete.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop