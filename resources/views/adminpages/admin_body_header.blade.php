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
    @for ($i = 0; $i < count($main_ap_links); $i++)
        @if ($main_ap_links[$i]->isActive == true)
            <a href='{{ $main_ap_links[$i]->adminWebLinkName }}' 
               class="admin-panel-menu-link admin-panel-menu-link-pressed admin-panel-ap-only-menu-link-pressed 
               {{ ($i < (count($main_ap_links)-1)) ? '' : 'admin-panel-ap-only-menu-link-last' }}">
                {{ $main_ap_links[$i]->linkName }}</a>
        @else
            <a href='{{ $main_ap_links[$i]->adminWebLinkName }}' 
               class="admin-panel-menu-link admin-panel-ap-only-menu-link 
               {{ ($i < (count($main_ap_links)-1)) ? '' : 'admin-panel-ap-only-menu-link-last' }}">
                {{ $main_ap_links[$i]->linkName }}</a>
        @endif       
    @endfor
    <div class="admin-panel-menu-separator"></div>
    @foreach ($main_ws_links as $main_ws_link)
        @if ($main_ws_link->isActive == true)
            <a href='{{ $main_ws_link->adminWebLinkName }}' class="admin-panel-menu-link admin-panel-menu-link-pressed">{{ $main_ws_link->linkName }}</a>
        @else
            <a href='{{ $main_ws_link->adminWebLinkName }}' class="admin-panel-menu-link">{{ $main_ws_link->linkName }}</a>
        @endif
    @endforeach
</nav>