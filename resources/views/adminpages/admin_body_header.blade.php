<div class="admin-panel-language-and-title">
    <div class="admin-panel-language-select">
        <div>
            <a href='{{ action('AdminController@index') }}'>
                @if (App::isLocale('en'))
                    <p class="admin-panel-en-language-select-button admin-panel-laguage-select-button-pressed">@lang('keywords.EnglishVersion')</p>
                @else
                    <p class="admin-panel-en-language-select-button">@lang('keywords.EnglishVersion')</p>
                @endif
            </a>
        </div>
        <div class="admin-panel-language-select-button-separator">
            <p>|</p>
        </div>
        <div>
            <a href='/ru/admin'>
                @if (App::isLocale('en'))
                    <p class="admin-panel-rus-language-select-button">@lang('keywords.RussianVersion')</p>
                @else
                    <p class="admin-panel-rus-language-select-button admin-panel-laguage-select-button-pressed">@lang('keywords.RussianVersion')</p>
                @endif
            </a>
        </div>   
    </div>   
    <div class="admin-panel-title">
        <h1>@lang('keywords.AdministrationPanel')</h1>
    </div>
</div>
<nav class="admin-panel-menu">
    @if ($keywordsLinkIsActive)
        @if (App::isLocale('en'))
            <a class="admin-panel-menu-link admin-panel-keywords-menu-link admin-panel-keywords-menu-link-pressed" 
               href='/admin/keywords'>@lang('keywords.Keywords')</a>
        @else
            <a class="admin-panel-menu-link admin-panel-keywords-menu-link admin-panel-keywords-menu-link-pressed" 
               href='/ru/admin/keywords'>@lang('keywords.Keywords')</a>
        @endif
    @else
        @if (App::isLocale('en'))
            <a class="admin-panel-menu-link admin-panel-keywords-menu-link" href='/admin/keywords'>@lang('keywords.Keywords')</a>
        @else
            <a class="admin-panel-menu-link admin-panel-keywords-menu-link" href='/ru/admin/keywords'>@lang('keywords.Keywords')</a>
        @endif
    @endif
    @foreach ($main_links as $main_link)
        @if ($main_link->isActive == true)
            <a href='{{ $main_link->adminWebLinkName }}' class="admin-panel-menu-link admin-panel-menu-link-pressed">{{ $main_link->linkName }}</a>
        @else
            <a href='{{ $main_link->adminWebLinkName }}' class="admin-panel-menu-link">{{ $main_link->linkName }}</a>
        @endif
    @endforeach    
</nav>