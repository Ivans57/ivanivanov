<div class="folders-and-articles-sorting">                        
    {!! Form::label('sort', Lang::get('keywords.Sorting').':', 
                   ['class' => 'folders-and-articles-sorting-label']) !!}           
    {!! Form::select('sort', array(
                     'sort_by_creation_desc' => Lang::get('keywords.LatestFirst'), 
                     'sort_by_creation_asc' => Lang::get('keywords.OldestFirst'), 
                     'sort_by_name_desc' => Lang::get('keywords.SortByNameDescending'), 
                     'sort_by_name_asc' => Lang::get('keywords.SortByNameAscending')), 
                      $sorting_mode, ['id' => 'sort', 
                     'class' => 'form-control folders-and-articles-sorting-controls folders-and-articles-sorting-select', 
                     'data-section' => $section, 'data-parent_keyword' => $parent_keyword, 'data-is_level_zero' => '0', 
                     'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                     'data-has_files' => ($articleAmount > 0) ? 'true' : 'false', 
                     'data-has_directories' => ($folderAmount > 0) ? 'true' : 'false']) !!}                    
</div>
<div class="folders-and-articles-wrapper">       
    @foreach ($folders_and_articles as $folder_or_article)
        <div class="article-folder-or-article">
            @if ($folder_or_article->type == 'folder')
                <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyWord."/page/1" : "/ru/articles/".$folder_or_article->keyWord."/page/1" }} class="article-folder-or-article-link">
                    <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/regular_folder_small.png')}}" alt="{{ $folder_or_article->caption }}"></div>
                    <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                </a>
            @else
                <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyWord : "/ru/articles/".$folder_or_article->keyWord }} class="article-folder-or-article-link">
                    <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/article.png') }}" alt="{{ $folder_or_article->caption }}"></div>
                    <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                </a>
            @endif
        </div>
    @endforeach       
</div>