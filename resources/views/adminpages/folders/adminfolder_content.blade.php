{!! Form::hidden('search_is_on', '0', ['id' => 'search_is_on']); !!}
@if ($allArticlesAmount > 0 || $allFoldersAmount > 0)
    <div class="admin-panel-articles-sorting">
        {!! Form::label('show_only_visible', Lang::get('keywords.ShowOnlyVisible').':', 
                       ['class' => 'admin-panel-articles-sorting-label']); !!}
        {!! Form::checkbox('show_only_visible', $show_invisible, $show_invisible == 'all' ? false : true, 
                          ['id' => 'show_only_visible', 'class' => 'admin-panel-articles-sorting-controls', 
                           'data-localization' => (App::isLocale('en') ? 'en' : 'ru'),
                           'data-section' => $section, 'data-is_level_zero' => '0', 'data-parent_keyword' => $parent_keyword,
                           'data-old_sorting_method_and_mode' => $sorting_method_and_mode,
                           'data-old_directories_or_files_first' => $directories_or_files_first]); !!}                              
        @if ($articleAmount > 0 && $folderAmount > 0)                   
            {!! Form::label('folders_first', Lang::get('keywords.FoldersFirst').':', 
                           ['class' => 'admin-panel-articles-sorting-label']) !!}               
            {!! Form::radio('directories_or_files_first', 'folders_first', 
                           (($directories_or_files_first === 'folders_first') ? true : false), ['id' => 'folders_first', 
                            'class' => 'admin-panel-articles-sorting-controls']); !!}                    
            {!! Form::label('articles_first', Lang::get('keywords.ArticlesFirst').':', 
                           ['class' => 'admin-panel-articles-sorting-label']) !!}               
            {!! Form::radio('directories_or_files_first', 'articles_first', 
                           (($directories_or_files_first === 'articles_first') ? true : false), ['id' => 'articles_first', 
                            'class' => 'admin-panel-articles-sorting-controls']); !!}                    
        @endif     
    </div>
@endif
@if ($total_number_of_items > 0)
    @include('adminpages.folders.adminfolder_data')
    @if ($total_number_of_items > $items_amount_per_page)   
        <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
        @component('multy_entity_paginator', ['pagination_info' => $pagination_info, 'section' => $section, 
                   'parent_keyword' => $parent_keyword, 'is_admin_panel' => $is_admin_panel, 
                   'show_invisible' => $show_invisible, 'sorting_mode' => $sorting_mode, 
                   'directories_or_files_first' => $directories_or_files_first])
        @endcomponent
    @endif
@else
    <div class="admin-panel-articles-empty-folders-text-wrapper">
        <p>@lang('keywords.EmptySection')</p>
    </div>
@endif