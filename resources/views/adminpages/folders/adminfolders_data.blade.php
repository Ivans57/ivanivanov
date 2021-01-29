<!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
in case we don't have full page-->
<div class="admin-panel-articles-external-articles-and-folders-wrapper">
    <div class="admin-panel-articles-articles-and-folders-wrapper">
        @include('adminpages.folders.adminfolders_data_header')
        @foreach ($folders as $folder)
            <div class="admin-panel-articles-article-and-folder-body-row">
                <div class="admin-panel-articles-article-and-folder-body-field">
                     {!! Form::checkbox('item_select', 1, false, 
                                       ['data-keyword' => $folder->keyword, 'data-parent_keyword' => $parent_keyword,
                                        'data-entity_type' => 'directory',  'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                                        'class' => 'admin-panel-articles-article-and-folder-checkbox']); !!}
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field admin-panel-articles-article-and-folder-body-field-name">
                    <a href='{{ App::isLocale('en') ? "/admin/articles/".$folder->keyword."/page/1" : 
                                "/ru/admin/articles/".$folder->keyword."/page/1" }}'>
                        <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                            <div>
                                <img src="{{ ($folder->is_visible==1) ? URL::asset('images/icons/regular_folder_small.png') : 
                                              URL::asset('images/icons/regular_folder_small_bnw.png') }}">                                
                            </div>
                            <div class="admin-panel-articles-article-and-folder-title">
                                <p>{{ $folder->folder_name }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <div class="admin-panel-articles-article-and-folder-body-field-content">
                        <p>{{ $folder->created_at }}</p>
                    </div>
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <div class="admin-panel-articles-article-and-folder-body-field-content">
                        <p>{{ $folder->updated_at }}</p>
                    </div>
                </div>    
            </div>
        @endforeach
    </div>
</div>