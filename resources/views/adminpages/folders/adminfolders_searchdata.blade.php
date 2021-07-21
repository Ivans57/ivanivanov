<!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
in case we don't have full page-->
<div class="admin-panel-articles-external-articles-and-folders-wrapper">
    <div class="admin-panel-articles-articles-and-folders-wrapper">
        @include('adminpages.folders.adminfolders_data_header')
        @foreach ($folders_or_articles as $folder_or_article)
            <div class="admin-panel-articles-article-and-folder-body-row">
                <div class="admin-panel-articles-article-and-folder-body-field">
                     {!! Form::checkbox('item_select', 1, false, 
                                       ['data-keyword' => $folder_or_article->keyword, 'data-parent_keyword' => $folder_or_article->parent_keyword,
                                        'data-entity_type' => ($what_to_search === 'folders') ? 'directory' : 'file',  
                                        'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                                        'class' => 'admin-panel-articles-article-and-folder-checkbox']); !!}
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field admin-panel-articles-article-and-folder-body-field-name">
                    @if ($what_to_search === 'folders')
                        <a href='{{ App::isLocale('en') ? "/admin/articles/".$folder_or_article->keyword."/page/1" : 
                                    "/ru/admin/articles/".$folder_or_article->keyword."/page/1" }}'>
                            <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                <div>
                                    <img src="{{ ($folder_or_article->is_visible==1) ? URL::asset('images/icons/regular_folder_small.png') : 
                                                  URL::asset('images/icons/regular_folder_small_bnw.png') }}">                                
                                </div>
                                <div class="admin-panel-articles-article-and-folder-title">
                                    <p>{{ $folder_or_article->name }}</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <a href='{{ App::isLocale('en') ? "/admin/article/".$folder_or_article->parent_keyword."/edit/".$folder_or_article->keyword : 
                                   "/ru/admin/article/".$folder_or_article->parent_keyword."/edit/".$folder_or_article->keyword }}'>
                            <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                <div>
                                    <img src="{{ URL::asset('images/icons/article.png') }}" style="{{ ($folder_or_article->is_visible==1) ? 
                                         'opacity:1' : 'opacity:0.45' }}">
                                </div>
                                <div class="admin-panel-articles-article-and-folder-title">
                                    <p>{{ $folder_or_article->name }}</p>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <div class="admin-panel-articles-article-and-folder-body-field-content">
                        <p>{{ $folder_or_article->created_at }}</p>
                    </div>
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <div class="admin-panel-articles-article-and-folder-body-field-content">
                        <p>{{ $folder_or_article->updated_at }}</p>
                    </div>
                </div>    
            </div>
        @endforeach
    </div>
</div>