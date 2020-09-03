<div class="website-language-and-title">
    <div class="website-language-select">
        <div>
            <a href='{{ action('HomeController@index') }}'>
                @if (App::isLocale('en'))
                    <img src="{{ URL::asset('images/icons/uk.png') }}" alt="uk" title=@lang('langLinks.English') 
                         class="website-en-laguage-select-button website-laguage-select-button-pressed" id="en-laguage-button">
                @else
                    <img src="{{ URL::asset('images/icons/uk.png') }}" alt="uk" title=@lang('langLinks.English') 
                         class="website-en-laguage-select-button" id="en-laguage-button">
                @endif
            </a>
        </div>
        <div>
            <a href='/ru'>
                @if (App::isLocale('en'))
                    <img src="{{ URL::asset('images/icons/rus.png') }}" alt="rus" title=@lang('langLinks.Russian') 
                         class="website-rus-laguage-select-button" id="rus-laguage-button">
                @else
                    <img src="{{ URL::asset('images/icons/rus.png') }}" alt="rus" title=@lang('langLinks.Russian') 
                         class="website-rus-laguage-select-button website-laguage-select-button-pressed" id="rus-laguage-button">
                @endif
            </a>
        </div>    
    </div>   
    <div class="website-title">
        <h1>@lang('keywords.PersonalWebPageOfIvanIvanov')</h1>
    </div>
</div>
<nav class="website-menu">              
    <!-- Below we can we perform a check for a localization and depends on it we choose a proper action for selected link -->
    <!-- In the first case we a going directly to a necessary controller. default (English) language settings will be considered -->
    <!-- In the second case we use a link so we will go through the whole route and current language setting could take an effect -->
    <!-- To understand this please pay attention to a web route file! -->
    <!-- !!!Important!!! In the future peace of code of navigation bar needs to be located only on app page, 
    because we are repeating the same code on each page. This is not good! -->
    <!-- I wrapped my <div> by <a> tags to make pointer to change its view while it is hovering over this <div> -->
    <div class="website-menu-compact-view" id="menu-compact-view"><div></div></div>                    
    @foreach ($main_links as $main_link)
        <div class="website-menu-normal-view-button">
            @if ($main_link->isActive == true)
                <a href='{{ $main_link->webLinkName }}' class="website-menu-button-link website-menu-button-pressed">{{ $main_link->linkName }}</a>
            @else
                <a href='{{ $main_link->webLinkName }}' class="website-menu-button-link">{{ $main_link->linkName }}</a>
            @endif
        </div>
    @endforeach                       
</nav>