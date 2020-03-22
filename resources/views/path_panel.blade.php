@if ($is_admin_panel == true)
    @foreach ($parents as $parent)
        <a href={{ App::isLocale('en') ? "/admin/".$section."/".$parent->keyWord."/page/1" : 
            "/ru/admin/".$section."/".$parent->keyWord."/page/1" }} class="path-panel-text">{{ $parent->name }}</a>
        <span class="path-panel-text"> /</span>
    @endforeach 
@else
    @foreach ($parents as $parent)
        <a href={{ App::isLocale('en') ? "/".$section."/".$parent->keyWord."/page/1" : 
            "/ru/".$section."/".$parent->keyWord."/page/1" }} class="path-panel-text">{{ $parent->name }}</a>
        <span class="path-panel-text"> /</span>
    @endforeach
@endif