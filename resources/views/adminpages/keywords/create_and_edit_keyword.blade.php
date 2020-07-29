@extends('create_edit_delete_window')

@section('create_edit_delete_window_content')
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/" : "/ru/admin/keywords/"]) !!}
    @else
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/".$keyword : "/ru/admin/keywords/".$keyword, 'method' => 'PUT' ]) !!}
    @endif
            {!! Form::hidden('old_keyword', $create_or_edit==='create' ? null : $keyword) !!}
            <div class='admin-panel-create-edit-entity'>
                <div class="admin-panel-create-edit-entity-controls">
                    <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', 
                            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
                    </div>
                    <div>{!! Form::text('keyword', $create_or_edit==='create' ? null : $keyword, 
                        ['class' => 'admin-panel-create-edit-entity-controls-input']) !!}
                    </div>
                </div>
                <div class="admin-panel-create-edit-entity-regulations"><span>@lang('keywords.KeywordRegulations')</span></div>
                <div class="admin-panel-create-edit-entity-controls">
                    <div>{!! Form::label('text', Lang::get('keywords.Text').':', ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}</div>
                    <div>{!! Form::textarea('text', $create_or_edit==='create' ? null : $text, 
                            ['class' => 'admin-panel-create-edit-entity-controls-input', 'rows' => 3]) !!}
                    </div>
                </div>
                <div class="admin-panel-create-edit-entity-controls">
                    {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-create-edit-entity-controls-button']) !!}
                    {!! Form::button(Lang::get('keywords.Cancel'), 
                    ['class' => 'admin-panel-create-edit-entity-controls-button', 'id' => 'button_cancel']) !!}
                </div>
            </div>    
        {!! Form::close() !!}   
@stop
@section('scripts')
    <!-- Scripts -->
    @component('pages/body_scripts')
        @slot('js')
            <script type="text/javascript" src="{{ URL::asset('js/pop_up_window_cancel.js') }}"></script>
        @endslot
    @endcomponent
    <!-- End of scripts -->
@stop