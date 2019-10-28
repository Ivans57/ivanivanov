@extends('partial')

<!-- Some elements have data-... attributes. These attributes contain some phrases 
for messages might be called by javascript later. We need to keep these attributes, 
so we don't need to translate phrases with javascript. There might be some difficulties
to translate keywords via javascript as I am taking my keywords from the database-->
@section('partialcontent')
    <div class="admin-panel-slbums-create-notification-wrapper"></div>
    {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/albums/" : "/ru/admin/albums/", 'id' => 'admin_panel_create_edit_delete_album_form' ]) !!}
        <div class='admin-panel-albums-create-edit-album'>
            <div class="admin-panel-albums-create-edit-album-controls">              
                {!! Form::label('parent_album', Lang::get('keywords.ParentAlbum').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}
                {!! Form::select('parent_album',  $albums, null, ['class' => 'admin-panel-albums-create-edit-album-controls-input']) !!}
            </div>
            <div class="admin-panel-albums-create-edit-album-regulations"><span>@lang('keywords.AlbumKeywordRegulations')</span></div>
            <div class="admin-panel-albums-create-edit-album-controls">
                <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}</div>
                <div>{!! Form::text('keyword', null, ['class' => 'admin-panel-albums-create-edit-album-controls-input' ]) !!}</div>
            </div>
            <div class="admin-panel-albums-create-edit-album-controls">
                <div>{!! Form::label('album_name', Lang::get('keywords.AlbumName').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}</div>
                <div>{!! Form::text('album_name', null, ['class' => 'admin-panel-albums-create-edit-album-controls-input' ]) !!}</div>
            </div>
            <div class="admin-panel-albums-create-edit-album-controls">
                {!! Form::label('album_name', Lang::get('keywords.IsVisible').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}
                {!! Form::checkbox('is_visible', 1, false ) !!}
            </div>
            <div class="admin-panel-albums-create-edit-album-controls">
                {!! Form::button(Lang::get('keywords.Save'), ['class' => 'admin-panel-albums-create-edit-album-controls-button' ]) !!}
                {!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-albums-create-edit-album-controls-button' ]) !!}
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