<div class="admin-panel-albums-picture-and-album-header-row">
    <div class="admin-panel-albums-picture-and-album-header-field" id="albums_all_items_select_wrapper" 
                title='{{ Lang::get("keywords.SelectAll") }}' 
                data-select='{{ Lang::get("keywords.SelectAll") }}' data-unselect='{{ Lang::get("keywords.UnselectAll") }}'>
        {!! Form::checkbox('albums_all_items_select', 'value', false, ['id' => 'albums_all_items_select', 
                           'class' => 'admin-panel-albums-picture-and-album-header-checkbox']); !!}
    </div>
    <div class="admin-panel-albums-picture-and-album-header-field admin-panel-albums-picture-and-album-header-field-name">
        <div class="admin-panel-albums-picture-and-album-header-text-and-caret-wrapper">
            <div class="admin-panel-albums-picture-and-album-header-text">
                @if ($search_is_on === "0")
                    <p>@lang('keywords.Name')</p>
                @else
                    <p>@lang('keywords.PathAndName')</p>
                @endif
            </div>
            @if ($all_items_amount > 1)
                <div class="admin-panel-albums-picture-and-album-header-caret">
                    @if ($sorting_asc_or_desc["Name"][0] == "desc")
                        <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                              "admin-panel-albums-picture-and-album-header-caret-used" : 
                              "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                              id="sort_by_name" data-sorting_mode="desc" data-search_is_on={{ $search_is_on }}
                              title='{{ Lang::get("keywords.SortByNameDesc") }}'></span>
                    @else
                        <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                              "admin-panel-albums-picture-and-album-header-caret-used" : 
                              "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                              id="sort_by_name" data-sorting_mode="asc" data-search_is_on={{ $search_is_on }}
                              title='{{ Lang::get("keywords.SortByNameAsc") }}'></span>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="admin-panel-albums-picture-and-album-header-field">
        <div class="admin-panel-albums-picture-and-album-header-text-and-caret-wrapper">
            <div class="admin-panel-albums-picture-and-album-header-text">
                <p>@lang('keywords.DateAndTimeCreated')</p>
            </div>
            @if ($all_items_amount > 1)
                <div class="admin-panel-albums-picture-and-album-header-caret">
                    @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                        <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                              "admin-panel-albums-picture-and-album-header-caret-used" : 
                              "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                              id="sort_by_creation" data-sorting_mode="desc" data-search_is_on={{ $search_is_on }}
                              title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                    @else
                        <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                              "admin-panel-albums-picture-and-album-header-caret-used" : 
                              "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                              id="sort_by_creation" data-sorting_mode="asc" data-search_is_on={{ $search_is_on }}
                              title='{{ Lang::get("keywords.SortByCreationDateAndTimeAsc") }}'></span>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="admin-panel-albums-picture-and-album-header-field">
        <div class="admin-panel-albums-picture-and-album-header-text-and-caret-wrapper">
            <div class="admin-panel-albums-picture-and-album-header-text">
                <p>@lang('keywords.DateAndTimeUpdate')</p>
            </div>
            @if ($all_items_amount > 1)
                <div class="admin-panel-albums-picture-and-album-header-caret">
                    @if ($sorting_asc_or_desc["Update"][0] == "desc")
                        <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                              "admin-panel-albums-picture-and-album-header-caret-used" : 
                              "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                              id="sort_by_update" data-sorting_mode="desc" data-search_is_on={{ $search_is_on }}
                              title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                    @else
                        <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                              "admin-panel-albums-picture-and-album-header-caret-used" : 
                              "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                              id="sort_by_update" data-sorting_mode="asc" data-search_is_on={{ $search_is_on }}
                              title='{{ Lang::get("keywords.SortByUpdateDateAndTimeAsc") }}'></span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>