$(function() {
    $('.btn.btn-ajax').on('click', function() {
        var btn = $(this);
        var selected = btn.find('.btn-ajax-selected').first();
        var notSelected = btn.find("[data-btn-ajax-type]:not(.btn-ajax-selected)");
        var selectedUrl  = selected.attr("data-btn-ajax-url");
        var selectedType = selected.attr("data-btn-ajax-type");
        btn.attr("disabled", true);
        $.ajax({
            url: selectedUrl,
            type: selectedType,
            success: function (result) {
                if(notSelected.length == 1) {
                    notSelected.first().addClass('btn-ajax-selected');
                    selected.removeClass('btn-ajax-selected');
                }
                if(selected.attr("data-btn-ajax-success") == "reload") {
                    location.reload();
                }
            }
        }).done(function() {
            btn.attr("disabled", false);
        });
    });
});
