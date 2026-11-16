jQuery(document).ready(function (){
    jQuery('#frm_ssv').on("submit", function (event) {
        
        event.preventDefault();
        let formData = new FormData(this);
        jQuery.ajax({
            url: csv-object.ajax_url,
            data: formData,
            dataType: "json",
            method: "POST",
            processData: false,
            contentType: false,
            success: function (){
                
            }
        });
    });
});