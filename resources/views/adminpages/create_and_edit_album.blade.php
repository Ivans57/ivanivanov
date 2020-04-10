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
    @if ($create_or_edit==='create')
        {!! Form::open([ 'url' => App::isLocale('en') ? "/admin/albums/" : "/ru/admin/albums/", 'id' => 'admin_panel_create_edit_delete_album_form' ]) !!}
    @else
        {!! Form::model($edited_album, [ 'method' => 'PUT', 'url' => App::isLocale('en') ? "/admin/albums/".$edited_album->keyword : "/ru/admin/albums/".$edited_album->keyword, 'id' => 'admin_panel_create_edit_delete_album_form' ]) !!}
    @endif
            <div class='admin-panel-albums-create-edit-album'>
                <!-- We need to pass an old keyword to validation because we need to compare it with a new keyword to avoid any misunderstanding 
                when do keyword uniqueness check. When we edit existing record we might change something without changing a keyword. 
                If we don't compare new keyword with its previous value, the system might think keyword 
                is not unique as user is trying to assign already existing keyword. -->
                {!! Form::hidden('old_keyword', $create_or_edit==='create' ? '0' : $edited_album->keyword) !!}
                <div class="admin-panel-albums-create-edit-album-controls">              
                    {!! Form::label('included_in_album_with_id', Lang::get('keywords.ParentAlbum').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}
                    {!! Form::select('included_in_album_with_id', $albums, $parent_id, ['class' => 'admin-panel-albums-create-edit-album-controls-input']) !!}
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
                    {!! Form::checkbox('is_visible', 1, null ) !!}
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