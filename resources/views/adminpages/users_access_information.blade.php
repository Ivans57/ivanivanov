<div class="admin-panel-user-access-information">
    <div class="admin-panel-user-access-information-full-access-users">
        <span>{{ Lang::get('keywords.FullAccessUsers') }}</span>:
        @if ($sizeof_full_access_users_names > 0)
            @foreach ($full_access_user_names as $full_access_user_name)
                {{ $full_access_user_name }}
            @endforeach
        @else
            -
        @endif
    </div>
    <div class="admin-panel-user-access-information-limited-access-users">
        <span>{{ Lang::get('keywords.LimitedAccessUsers') }}</span>:
        @if ($sizeof_limited_access_users_names > 0)
            @foreach ($limited_access_user_names as $limited_access_user_name)
                {{ $limited_access_user_name }}
            @endforeach
        @else
            -
        @endif
    </div>
</div>