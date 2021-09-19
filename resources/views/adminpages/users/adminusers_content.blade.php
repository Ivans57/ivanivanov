@if ($users->count() > 0)
    @include('adminpages.users.adminusers_data')
@else
    <div class="admin-panel-users-empty-text-wrapper">
        <p>@lang('keywords.EmptySection')</p>
    </div>
@endif