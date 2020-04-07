@extends('partial')

@section('partialcontent')
    <div class="admin-panel-albums-create-notification-wrapper">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class='admin-panel-albums-create-notification alert alert-danger alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>{!! $error !!}
                </div>
            @endforeach
        @endif
    </div>
    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/albums/" : "/ru/admin/albums/", 'id' => 'admin_panel_create_edit_delete_album_form' ]) !!}
        <div class='admin-panel-albums-create-edit-album'>
            <div class="admin-panel-albums-create-edit-album-controls">              
                {!! Form::label('included_in_album_with_id', Lang::get('keywords.ParentAlbum').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}
                {!! Form::select('included_in_album_with_id', $albums, null, ['class' => 'admin-panel-albums-create-edit-album-controls-input']) !!}
            </div>          
            <div class="admin-panel-albums-create-edit-album-controls">
                <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}</div>
                <div>{!! Form::text('keyword', null, ['class' => 'admin-panel-albums-create-edit-album-controls-input']) !!}</div>
            </div>
            <div class="admin-panel-albums-create-edit-album-regulations"><span>@lang('keywords.AlbumKeywordRegulations')</span></div>
            <div class="admin-panel-albums-create-edit-album-controls">
                <div>{!! Form::label('album_name', Lang::get('keywords.AlbumName').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}</div>
                <div>{!! Form::text('album_name', null, ['class' => 'admin-panel-albums-create-edit-album-controls-input' ]) !!}</div>
            </div>
            <div class="admin-panel-albums-create-edit-album-controls">
                {!! Form::label('is_visible', Lang::get('keywords.IsVisible').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}
                {!! Form::checkbox('is_visible', 1, false ) !!}
            </div>
            <div class="admin-panel-albums-create-edit-album-controls">
                {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-albums-create-edit-album-controls-button' ]) !!}
                {!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-albums-create-edit-album-controls-button', 
                    'id' => 'admin_panel_albums_create_edit_delete_album_controls_button_cancel' ]) !!}
            </div>           
        </div>       
    {!! Form::close() !!}
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