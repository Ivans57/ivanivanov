@if ($users->count() > 0)
    @include('adminpages.users.adminusers_data')
@else
    <div class="admin-panel-keywords-empty-text-wrapper">
        <p>@lang('keywords.EmptySection')</p>
    </div>
@endif