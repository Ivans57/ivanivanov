{!! Form::hidden('search_is_on', '0', ['id' => 'search_is_on']); !!}
@if ($total_number_of_items > 0)
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
        @if ($articleAmount > 0 && $folderAmount > 0)                   
            {!! Form::label('folders_first', Lang::get('keywords.FoldersFirst').':', ['class' => 'folders-and-articles-sorting-label']) !!}               
            {!! Form::radio('directories_or_files_first', 'folders_first', 
                           (($directories_or_files_first === 'folders_first') ? true : false), ['id' => 'folders_first', 
                            'class' => 'folders-and-articles-sorting-controls']); !!}                    
            {!! Form::label('articles_first', Lang::get('keywords.ArticlesFirst').':', ['class' => 'folders-and-articles-sorting-label']) !!}               
            {!! Form::radio('directories_or_files_first', 'articles_first', 
                           (($directories_or_files_first === 'articles_first') ? true : false), ['id' => 'articles_first', 
                            'class' => 'folders-and-articles-sorting-controls']); !!}
                                       
            {!! Form::select('directories_or_files_first', array(
                             'folders_first' => Lang::get('keywords.FoldersFirst'),
                             'articles_first' => Lang::get('keywords.ArticlesFirst')),
                              $directories_or_files_first, ['id' => 'directories_or_files_first', 
                              'class' => 'form-control folders-and-articles-sorting-controls folders-and-articles-sorting-select']) !!}
        @endif          
    </div>
    @if ($articleAmount < 1)       
        <div class="external-folders-wrapper">
            <div class="folders-wrapper">       
                @foreach ($folders_and_articles as $folder_or_article)
                    <div class="folder-item" title='{{ $folder_or_article->caption }}'>
                        <div class="folder-body">
                            <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyWord."/page/1" : 
                               "/ru/articles/".$folder_or_article->keyWord."/page/1" }}>
                                <img src="{{ URL::asset('images/icons/regular_folder.png') }}" alt="{{ $folder_or_article->caption }}" class="article-folder">
                            </a>                   
                        </div>
                        <div class="folder-title">
                            <h3 class="article-folder-title">{{ $folder_or_article->caption }}</h3>
                        </div>
                    </div>
                @endforeach       
            </div>
        </div>
        @if ($total_number_of_items > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('multy_entity_paginator', ['pagination_info' => $pagination_info])
            @endcomponent
        @endif        
    @else
        <div class="folders-and-articles-wrapper">       
            @foreach ($folders_and_articles as $folder_or_article)
                <div class="article-folder-or-article">
                    @if ($folder_or_article->type == 'folder')
                        <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyWord."/page/1" : "/ru/articles/".$folder_or_article->keyWord."/page/1" }} 
                           class="article-folder-or-article-link">
                            <div class="article-folder-or-article-picture">
                                <img src="{{ URL::asset('images/icons/regular_folder_small.png')}}" alt="{{ $folder_or_article->caption }}">
                            </div>
                            <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                        </a>
                    @else
                        <a href={{ App::isLocale('en') ? "/articles/article/".$folder_or_article->keyWord : "/ru/articles/article/".$folder_or_article->keyWord }} 
                           class="article-folder-or-article-link">
                            <div class="article-folder-or-article-picture">
                                <img src="{{ URL::asset('images/icons/article.png') }}" alt="{{ $folder_or_article->caption }}">
                            </div>
                            <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                        </a>
                    @endif
                </div>
            @endforeach       
        </div>
        @if ($total_number_of_items > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->{{ $is_admin_panel }}
            @component('multy_entity_paginator', ['pagination_info' => $pagination_info, 'section' => $section, 
                       'parent_keyword' => $folderName, 'is_admin_panel' => $is_admin_panel,
                       'sorting_mode' => $sorting_mode, 'directories_or_files_first' => $directories_or_files_first])
            @endcomponent
        @endif      
    @endif
@else
    <div class="empty-folders-text-wrapper">
        <p>@lang('keywords.EmptyFolder')</p>
    </div>     
@endif