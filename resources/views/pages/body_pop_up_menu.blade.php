<!-- I have excluded pop-up-menu from website-wrapper because when we
use it, the content of website-wrapper which is on the background 
should be static (with fixed position), but at the same time our 
mobile view menu if necessary should be scrolled (with absolute 
position).-->
<div class="pop-up-menu">
    <div class="pop-up-menu-top">
        <!-- Drawing image of cross to close our pop-up menu. -->
        <svg width="20px" height="20px" class="pop-up-menu-close" id="pop-up-menu-close">
            <!-- <rect width="20" height="20" fill="none" stroke="#333333" stroke-width="2" /> -->
            <line x1="0" y1="0" x2="20" y2="20" stroke="#333333" stroke-width="2" />
            <line x1="0" y1="20" x2="20" y2="0" stroke="#333333" stroke-width="2" />
        </svg>
    </div>
    <div class="pop-up-menu-body">
        <h1>@lang('mainTitle.PersonalWebPageOfIvanIvanov')</h1>
        <ul>
            @foreach ($main_links as $main_link)
                <li>
                    @if ($main_link->isActive == true)
                        <a href='{{ $main_link->webLinkName }}' class="pop-up-menu-link pop-up-menu-link-active">{{ $main_link->linkName }}</a>
                    @else
                        <a href='{{ $main_link->webLinkName }}' class="pop-up-menu-link">{{ $main_link->linkName }}</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>