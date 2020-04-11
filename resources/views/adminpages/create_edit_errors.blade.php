<div class="admin-panel-albums-create-notification-wrapper">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class='admin-panel-albums-create-notification alert alert-danger alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>{!! $error !!}
            </div>
        @endforeach
    @endif
</div>