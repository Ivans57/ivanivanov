<div class="folders-and-articles-sorting">                        
    {!! Form::label('sort', Lang::get('keywords.Sorting').':', 
                   ['class' => 'folders-and-articles-sorting-label']) !!}           
    {!! Form::select('sort', array(
                     'sort_by_creation_desc' => Lang::get('keywords.LatestFirst'), 
                     'sort_by_creation_asc' => Lang::get('keywords.OldestFirst'), 
                     'sort_by_name_desc' => Lang::get('keywords.SortByNameDescending'), 
                     'sort_by_name_asc' => Lang::get('keywords.SortByNameAscending')), 
                      $sorting_method_and_mode, ['id' => 'sort', 
                     'class' => 'form-control folders-and-articles-sorting-controls folders-and-articles-sorting-select', 
                     'data-section' => $section, 'data-is_level_zero' => '0', 
                     'data-localization' => App::isLocale('en') ? 'en' : 'ru']) !!}                    
</div>
<div class="folders-and-articles-wrapper">       
    @foreach ($folders_or_articles as $folder_or_article)
        <div class="article-folder-or-article">
            @if ($what_to_search == 'folders')
                <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyword."/page/1" : "/ru/articles/".$folder_or_article->keyword."/page/1" }} 
                    class="article-folder-or-article-link">
                    <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/regular_folder_small.png')}}" alt="{{ $folder_or_article->name }}"></div>
                    <div class="article-folder-or-article-title"><p>{{ $folder_or_article->name }}</p></div>
                </a>
            @else
                <a href={{ App::isLocale('en') ? "/articles/article/".$folder_or_article->keyword : "/ru/articles/article/".$folder_or_article->keyword }} 
                    class="article-folder-or-article-link">
                    <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/article.png') }}" alt="{{ $folder_or_article->name }}"></div>
                    <div class="article-folder-or-article-title"><p>{{ $folder_or_article->name }}</p></div>
                </a>
            @endif
        </div>
    @endforeach       
</div>