/**
 * Allows you to add data-method="METHOD to links to automatically inject a form
 * with the method on click
 *
 * Example: <a href="{{route('customers.destroy', $customer->id)}}"
 * data-method="delete" name="delete_item">Delete</a>
 *
 * Injects a form with that's fired on click of the link with a DELETE request.
 * Good because you don't have to dirty your HTML with delete forms everywhere.
 */
function addDeleteForms() {
    $('[data-method=delete]').append(function () {
        if (! $(this).find('form').length > 0)
            return "\n" +
                "<form action='" + $(this).attr('href') + "' method='POST' name='delete_item' style='display:none'>\n" +
                "   <input type='hidden' name='_method' value='" + $(this).attr('data-method') + "'>\n" +
                "   <input type='hidden' name='_token' value='" + $('meta[name="csrf-token"]').attr('content') + "'>\n" +
                "</form>\n";
        else
            return "";
    })
        .removeAttr('href')
        .attr('style', 'cursor:pointer;')
        .attr('onclick', '$(this).find("form").submit();');
}

function addCompleteForms() {
    $('[data-swal=complete_event]').append(function () {
        if (! $(this).find('form').length > 0)
            return "\n" +
                "<form action='" + $(this).attr('href') + "' method='GET' name='complete_event' style='display:none'>\n" +
                "</form>\n";
        else
            return "";
    })
        .removeAttr('href')
        .attr('style', 'cursor:pointer;')
        .attr('onclick', '$(this).find("form").submit();');
}

function addPostponeEventForms() {
    $('[data-swal=postpone_event]').append(function () {
        if (! $(this).find('form').length > 0)
            return "\n" +
                "<form action='" + $(this).attr('href') + "' method='GET' name='postpone_event' style='display:none'>\n" +
                "</form>\n";
        else
            return "";
    })
        .removeAttr('href')
        .attr('style', 'cursor:pointer;')
        .attr('onclick', '$(this).find("form").submit();');
}

function addPendingEventForms() {
    $('[data-swal=pending_event]').append(function () {
        if (! $(this).find('form').length > 0)
            return "\n" +
                "<form action='" + $(this).attr('href') + "' method='GET' name='pending_event' style='display:none'>\n" +
                "</form>\n";
        else
            return "";
    })
        .removeAttr('href')
        .attr('style', 'cursor:pointer;')
        .attr('onclick', '$(this).find("form").submit();');
}

function addCancelEventForms() {
    $('[data-swal=cancel_event]').append(function () {
        if (! $(this).find('form').length > 0)
            return "\n" +
                "<form action='" + $(this).attr('href') + "' method='GET' name='cancel_event' style='display:none'>\n" +
                "</form>\n";
        else
            return "";
    })
        .removeAttr('href')
        .attr('style', 'cursor:pointer;')
        .attr('onclick', '$(this).find("form").submit();');
}

function init_forms() {
    addDeleteForms();
    addCompleteForms();
    addPostponeEventForms();
    addPendingEventForms();
    addCancelEventForms();
}

/**
 * Place any jQuery/helper plugins in here.
 */
$(function(){
    /**
     * Place the CSRF token as a header on all pages for access in AJAX requests
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Add the data-method="delete" forms to all delete links
     */
    init_forms();

    /**
     * This is for delete buttons that are loaded via AJAX in datatables, they will not work right
     * without this block of code
     */
    $(document).ajaxComplete(function(){
        init_forms();
    });

    /**
     * Generic confirm form delete using Sweet Alert
     */
    $('body').on('submit', 'form[name=delete_item]', function(e){
        e.preventDefault();
        var form = this;
        var link = $('a[data-method="delete"]');
        var cancel = (link.attr('data-trans-button-cancel')) ? link.attr('data-trans-button-cancel') : "Cancel";
        var confirm = (link.attr('data-trans-button-confirm')) ? link.attr('data-trans-button-confirm') : "Yes, delete";
        var title = (link.attr('data-trans-title')) ? link.attr('data-trans-title') : "Warning";
        var text = (link.attr('data-trans-text')) ? link.attr('data-trans-text') : "Are you sure you want to delete this item?";

        swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            cancelButtonText: cancel,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirm,
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed)
                form.submit();
        });
    });


    $('body').on('submit', 'form[name=complete_event]', function(e){
        e.preventDefault();
        var form = this;
        var cancel = "Cancel";
        var confirm = "Complete Event";
        var title = "Warning";
        var text = "Are you sure you want to mark this event as completed?";

        swal({
            title: title,
            text: text,
            type: "warning",
            showCancelButton: true,
            cancelButtonText: cancel,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirm,
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed)
                window.location.href = $(form).attr('action');
        });
    });


    $('body').on('submit', 'form[name=cancel_event]', function(e){
        e.preventDefault();
        var form = this;
        var cancel = "Cancel";
        var confirm = "Cancel Event";
        var title = "Warning";
        var text = "Are you sure you want to cancel this event?";

        swal({
            title: title,
            text: text,
            type: "warning",
            showCancelButton: true,
            cancelButtonText: cancel,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirm,
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed)
                window.location.href = $(form).attr('action');
        });
    });


    $('body').on('submit', 'form[name=postpone_event]', function(e){
        e.preventDefault();
        var form = this;
        var cancel = "Cancel";
        var confirm = "Postpone Event";
        var title = "Warning";
        var text = "Are you sure you want to postpone this event?";

        swal({
            title: title,
            text: text,
            type: "warning",
            showCancelButton: true,
            cancelButtonText: cancel,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirm,
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed)
                window.location.href = $(form).attr('action');
        });
    });

        $('body').on('submit', 'form[name=pending_event]', function(e){
        e.preventDefault();
        var form = this;
        var cancel = "Cancel";
        var confirm = "Pending Event";
        var title = "Warning";
        var text = "Are you sure you want to pending this event?";

        swal({
            title: title,
            text: text,
            type: "warning",
            showCancelButton: true,
            cancelButtonText: cancel,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirm,
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed)
                window.location.href = $(form).attr('action');
        });
    });

    /**
     * Bind all bootstrap tooltips
     */
    $("[data-toggle=\"tooltip\"]").tooltip();

    /**
     * Bind all bootstrap popovers
     */
    $("[data-toggle=\"popover\"]").popover();

    /**
     * This closes the popover when its clicked away from
     */
    $('body').on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
});