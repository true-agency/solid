<script>
    window.SITE_URL = '{{ url('/') }}';
</script>

<?php /** Vendor scripts **/ ?>
<script src="{{ asset('assets/vendor/jquery-1.11.0.min.js') }}"></script>
<script src="{{ asset('assets/js/dist/vendor.min.js') }}"></script>

<?php /** Application scripts **/ ?>
<script src="{{ asset('assets/js/dist/main.min.js') }}"></script>

@section('extended-scripts')  
@show