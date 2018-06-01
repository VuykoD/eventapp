{{-- @php(dd(Session())) --}}
@if (isset($toastr_message) && isset($toastr_type))
<script>
(function($) {
    $(function() { // document.ready
        toastr.{{ $toastr_type }}("{{ addslashes($toastr_message) }}", '', {
            timeOut: 10000,
            extendedTimeOut: 0,
            closeButton: true,
            newestOnTop: true,
        });
    }); // end document.ready
})(jQuery);
</script>
@endif