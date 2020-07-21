@extends('partial')

<!-- Some elements have data-... attributes. These attributes contain some phrases 
for messages might be called by javascript later. We need to keep these attributes, 
so we don't need to translate phrases with javascript. There might be some difficulties
to translate keywords via javascript as I am taking my keywords from the database-->
@section('partialcontent')
    @include('adminpages.create_edit_errors')
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/" : "/ru/admin/keywords/"]) !!}
    @else
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/keywords/".$keyword : "/ru/admin/keywords/".$keyword, 'method' => 'PUT' ]) !!}
    @endif
            {!! Form::hidden('old_keyword', $create_or_edit==='create' ? null : $keyword) !!}
            <div class='admin-panel-keywords-create-edit-keyword'>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', 
                            ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}
                    </div>
                    <div>{!! Form::text('keyword', $create_or_edit==='create' ? null : $keyword, 
                        ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input']) !!}
                    </div>
                </div>
                <div class="admin-panel-keywords-create-edit-keyword-regulations"><span>@lang('keywords.KeywordRegulations')</span></div>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    <div>{!! Form::label('text', Lang::get('keywords.Text').':', ['class' => 'admin-panel-keywords-create-edit-keyword-controls-label']) !!}</div>
                    <div>{!! Form::textarea('text', $create_or_edit==='create' ? null : $text, 
                            ['class' => 'admin-panel-keywords-create-edit-keyword-controls-input', 'rows' => 3]) !!}
                    </div>
                </div>
                <div class="admin-panel-keywords-create-edit-keyword-controls">
                    {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button']) !!}
                    {!! Form::button(Lang::get('keywords.Cancel'), 
                    ['class' => 'admin-panel-keywords-create-edit-keyword-controls-button', 'id' => 'button_cancel']) !!}
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