<?php
if (!defined('ABSPATH')) exit;

add_action('init', function(){

    $enabled = get_option('gsbwc_enabled_taxonomies', array());
    if (empty($enabled)) return;

    foreach ($enabled as $tax) {

        add_action("{$tax}_add_form_fields", 'gsbwc_term_fields_add');
        add_action("{$tax}_edit_form_fields", 'gsbwc_term_fields_edit');
        add_action("created_{$tax}", 'gsbwc_save_term_fields');
        add_action("edited_{$tax}", 'gsbwc_save_term_fields');
    }

});

function gsbwc_term_fields_add() {
    ?>
    <div class="form-field">
        <label>Badge Background</label>
        <input type="color" name="gsbwc_bg" value="#000000" />
    </div>

    <div class="form-field">
        <label>Badge Text Color</label>
        <input type="color" name="gsbwc_color" value="#ffffff" />
    </div>

    <div class="form-field">
        <label>Badge Icon (Dashicon Class)</label>
        <input type="text" name="gsbwc_icon" placeholder="dashicons-star-filled" />
    </div>
    <?php
}

function gsbwc_term_fields_edit($term) {
    $bg = get_term_meta($term->term_id, 'gsbwc_bg', true);
    $color = get_term_meta($term->term_id, 'gsbwc_color', true);
    $icon = get_term_meta($term->term_id, 'gsbwc_icon', true);
    ?>
    <tr class="form-field">
        <th>Badge Background</th>
        <td><input type="color" name="gsbwc_bg" value="<?php echo esc_attr($bg ?: '#000000'); ?>" /></td>
    </tr>

    <tr class="form-field">
        <th>Badge Text Color</th>
        <td><input type="color" name="gsbwc_color" value="<?php echo esc_attr($color ?: '#ffffff'); ?>" /></td>
    </tr>

    <tr class="form-field">
        <th>Icon</th>
        <td><input type="text" name="gsbwc_icon" value="<?php echo esc_attr($icon); ?>" /></td>
    </tr>
    <?php
}

function gsbwc_save_term_fields($term_id) {
    update_term_meta($term_id, 'gsbwc_bg', sanitize_hex_color($_POST['gsbwc_bg']));
    update_term_meta($term_id, 'gsbwc_color', sanitize_hex_color($_POST['gsbwc_color']));
    update_term_meta($term_id, 'gsbwc_icon', sanitize_text_field($_POST['gsbwc_icon']));
}
