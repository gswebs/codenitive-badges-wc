jQuery(function($){
    $('#gsbwc-preview').on('click', function(){
        $.post(GSBWC.ajax,{action:'gsbwc_preview'},function(res){
            $('#gsbwc-preview-area').html(res);
        });
    });
});

jQuery(document).ready(function($){

    // Show input if custom hook is selected
    $(document).on('change', '.gsbwc-hook-select', function(){
        let index = $(this).data('index');
        let value = $(this).val();

        let input = $('.gsbwc-custom-hook-' + index);
        if(value === 'custom_hook'){
            input.show();
        } else {
            input.hide().val('');
        }
    });
    
    $('#gsbwc_enabled_taxonomies').select2({
        placeholder: "Select attributes to enable term colors",
        width: 'resolve'
    });

});
