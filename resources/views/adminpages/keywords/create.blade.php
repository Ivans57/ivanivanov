@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    @if (App::isLocale('en'))
    {!! Form::open(['url' => 'admin/keywords']) !!}
    @else
    {!! Form::open(['url' => 'ru/admin/keywords']) !!}
    @endif
        <div>
            <div>{!! Form::label('keyword', 'Keyword:') !!}</div>
            <div>{!! Form::text('keyword', null, ['class' => 'some-class']) !!}</div>
        </div>
        <div>
            <div>{!! Form::label('text', 'Text:') !!}</div>
            <div>{!! Form::text('text', null, ['class' => 'some-class']) !!}</div>
        </div>
        <div>
            {!! Form::submit('Save', ['class' => 'some-class one-more-class']) !!}
        </div>
    {!! Form::close() !!}
</article>
@stop
