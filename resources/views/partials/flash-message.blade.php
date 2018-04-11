<div class="flash-message">
@foreach(['danger', 'warning', 'success', 'info'] as $msg)
@if(session()->has($msg))
    <p class="alert alert-{{ $msg }}">{!! session($msg) !!}</p>
@endif
@endforeach
</div>
