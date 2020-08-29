<div class='admin-panel-create-edit-article'>
    {{ $old_keyword }}
    {!! Form::hidden('folder_id', 
                    $parent_id, ['id' => 'included_in_directory_with_id']) !!}              
    <div class="admin-panel-create-edit-article-controls">              
        <div>
            {!! Form::label('included_in_directory_with_name', 
            Lang::get('keywords.ParentFolder').':', 
            ['class' => 'admin-panel-create-edit-article-controls-label']) !!}
        </div>
        @component('adminpages/create_edit_parent_search', ['parent_name' => $parent_name])
        @endcomponent
        <div id="directory_list_container" data-previous_page="{{ Lang::get('keywords.PreviousPage') }}"
             data-next_page="{{ Lang::get('keywords.NextPage') }}" 
             data-root="{{ Lang::get('keywords.Articles') }}">
        </div> 
    </div>    
    <div class="admin-panel-create-edit-article-controls">
        <div>
            {!! Form::label('article_keyword', Lang::get('keywords.ArticleKeyword').':', 
            ['class' => 'admin-panel-create-edit-article-controls-label']) !!}
        </div>
        <div>{!! Form::text('article_keyword', null, 
            ['class' => 'admin-panel-create-edit-article-controls-input', 'title' => Lang::get('keywords.MaximumLengthFifty')]) !!}</div>
    </div>                
    <div class="admin-panel-create-edit-article-regulations"><span>@lang('keywords.ArticleKeywordRegulations')</span></div>
    <div class="admin-panel-create-edit-article-controls">
        <div>{!! Form::label('article_title', Lang::get('keywords.ArticleTitle').':', 
             ['class' => 'admin-panel-create-edit-article-controls-label']) !!}</div>
        <div>{!! Form::text('article_title', null, 
            ['class' => 'admin-panel-create-edit-article-controls-input', 'title' => Lang::get('keywords.MaximumLengthFifty')]) !!}</div>
    </div>
    <div class="admin-panel-create-edit-article-controls">
        <div>
            {!! Form::label('article_description', Lang::get('keywords.ArticleDescription').':', 
            ['class' => 'admin-panel-create-edit-article-controls-label']) !!}
        </div>
        <div>
            {!! Form::textarea('article_description', null, 
            ['class' => 'admin-panel-create-edit-article-controls-input', 'rows' => 7, 
            'title' => Lang::get('keywords.MaximumLengthOneThousand')]) !!}
        </div>
    </div>
    <div class="admin-panel-create-edit-article-controls">
        <div>
            {!! Form::label('article_body', Lang::get('keywords.ArticleText').':', 
            ['class' => 'admin-panel-create-edit-article-controls-label']) !!}
        </div>
        <div>
            {!! Form::textarea('article_body', null, 
            ['class' => 'admin-panel-create-edit-article-controls-input', 'id' => 'article_create_edit_text_area', 'rows' => 35]) !!}
        </div>
    </div>
    <div class="admin-panel-create-edit-article-controls">
        <div>
            {!! Form::label('article_author', Lang::get('keywords.ArticleAuthor').':', 
            ['class' => 'admin-panel-create-edit-article-controls-label']) !!}
        </div>
        <div>{!! Form::text('article_author', null, ['class' => 'admin-panel-create-edit-article-controls-input-short']) !!}</div>
    </div>
    <div class="admin-panel-create-edit-article-controls">
        <div>
            {!! Form::label('article_source', Lang::get('keywords.ArticleSource').':', 
            ['class' => 'admin-panel-create-edit-article-controls-label']) !!}
        </div>
        <div>{!! Form::text('article_source', null, ['class' => 'admin-panel-create-edit-article-controls-input-short']) !!}</div>
    </div>
    <div class="admin-panel-create-edit-article-controls">
        {!! Form::label('is_visible', Lang::get('keywords.IsVisible').':', ['class' => 'admin-panel-create-edit-article-controls-label']) !!}
        {!! Form::checkbox('is_visible', 1) !!}
    </div>
    <div class="admin-panel-create-edit-article-controls">
        {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-create-edit-article-controls-button', 
        'name' => 'save', 'id' => 'button_submit' ]) !!}
        {!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-create-edit-article-controls-button', 
            'id' => 'button_cancel', 'data-parent_keyword' => $parent_keyword ]) !!}
    </div>           
</div>