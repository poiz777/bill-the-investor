@if ($message = Session::get('success'))
<div class="alert alert-success alert-block pz-alert">
    <strong>{{ $message }}</strong><span class="fa fa-close btn-close pull-right"></span>
</div>
@endif


@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
    <strong>{{ $message }}</strong><span class="fa fa-close btn-close pull-right"></span>
</div>
@endif


@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
    <strong>{{ $message }}</strong> <span class="fa fa-close btn-close pull-right"></span>
</div>
@endif


@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
    <strong>{{ $message }}</strong><span class="fa fa-close btn-close pull-right"></span>
</div>
@endif
