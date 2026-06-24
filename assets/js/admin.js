jQuery(function ($) {
    'use strict';

    // AJAX Preview
    $(document).on('click', '#gsbwc-preview', function (e) {
        e.preventDefault();

        $.post(GSBWC.ajax, {
            action: 'gsbwc_preview',
            nonce: GSBWC.nonce
        }, function (res) {
            $('#gsbwc-preview-area').html(res);
        });
    });

    // Select2
    if ($.fn.selectWoo) {
        $('#gsbwc_enabled_taxonomies').selectWoo({
            placeholder: 'Select attributes to enable term colors',
            width: '400px'
        });
    }

    let rowIndex = $('.gsbwc-row').length;

    // Add Attribute row
    $(document).on('click', '#gsbwc-add-row', function (e) {
        e.preventDefault();

        const $lastRow = $('.gsbwc-row:last');
        const $newRow = $lastRow.clone();

        $newRow.find('select, input').each(function () {
            const oldName = $(this).attr('name');

            if (oldName) {
                const newName = oldName.replace(
                    /gsbwc_attributes_hooks\[\d+\]/,
                    'gsbwc_attributes_hooks[' + rowIndex + ']'
                );

                $(this).attr('name', newName);
            }

            $(this).val('');
        });

        $newRow.find('.gsbwc-hook-select').attr('data-index', rowIndex);

        $newRow.find('input[name*="[custom_hook]"]')
            .removeAttr('class')
            .addClass('gsbwc-custom-hook-' + rowIndex)
            .css('display', 'none');

        $newRow.insertAfter('.gsbwc-row:last');

        rowIndex++;
    });

    // Remove Attribute row
    $(document).on('click', '.gsbwc-remove-row', function (e) {
        e.preventDefault();

        if ($('.gsbwc-row').length > 1) {
            $(this).closest('.gsbwc-row').remove();
        } else {
            $(this).closest('.gsbwc-row').find('select, input').val('');
            $(this).closest('.gsbwc-row').find('input[name*="[custom_hook]"]').hide();
        }
    });

    // Show custom hook input
    $(document).on('change', '.gsbwc-hook-select', function () {
        const $row = $(this).closest('.gsbwc-row');
        const $input = $row.find('input[name*="[custom_hook]"]');

        if ($(this).val() === 'custom_hook') {
            $input.show();
        } else {
            $input.hide().val('');
        }
    });
});