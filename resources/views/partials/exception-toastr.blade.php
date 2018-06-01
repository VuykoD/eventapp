{{-- @php(dd(Session())) --}}
@if (session()->get('exception_thrown'))
<script>
(function($) {
    $(function() { // document.ready
        toastr.error("{{ addslashes(session()->get('exception_thrown')) }}", "Error", {
            timeOut: 0,
            extendedTimeOut: 0,
            closeButton: true,
            newestOnTop: true,
        });
    }); // end document.ready
})(jQuery);
</script>
@endif