define(['jquery'], function($) {
    $(document).ready(function() {
        $("#categorySelector").change(function() {
            window.location.href = $("#categorySelector").val();
        });
    });
});