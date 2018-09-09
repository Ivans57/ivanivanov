@extends('admin')

@section('admincontent')
<div class="admin-panel-main-path-wrapper">
    <div class="admin-panel-main-path">
        <p>This is main path panel.</p>
    </div>
</div>
<div class="admin-panel-main-article-wrapper">
    <article class="admin-panel-main-article admin-panel-home-article">
        <h2>You can edit home page here</h2>
        {!! Form::open() !!}
            <div>
                <div>{!! Form::label('title', 'Title:') !!}</div>
                <div>{!! Form::text('title', null, ['class' => 'some-class']) !!}</div>
            </div>
            <div>
                <div>{!! Form::label('body', 'Body:') !!}</div>
                <div>{!! Form::textarea('body', null, ['class' => 'some-class']) !!}</div>
            </div>
            <div>
                {!! Form::submit('Save', ['class' => 'some-class one-more-class']) !!}
            </div>
        {!! Form::close() !!}
    </article>
</div>
@stop
