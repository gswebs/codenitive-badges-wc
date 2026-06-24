<?php
if (!defined('ABSPATH')) exit;

class CODENIT_WC_Settings {

    public function __construct() {
        add_action('admin_menu', [$this,'menu']);
        add_action('admin_init', [$this,'settings']);
        add_action('admin_enqueue_scripts', [$this,'admin_assets']);
        add_action('wp_ajax_gsbwc_preview', [$this,'ajax_preview']);
    }

    public function menu(){
        add_options_page('GS Badges', 'GS Badges', 'manage_options', 'gsbwc', [$this,'page']);
    }

    private function render_attribute_hook_row($index, $attribute, $hook, $priority = 10, $custom_hook = ''){
        $taxes = wc_get_attribute_taxonomies();
        
        $hooks = [
            'woocommerce_before_single_product'  => 'Single Product: woocommerce_before_single_product',
            'woocommerce_before_single_product_summary'   => 'Single Product: woocommerce_before_single_product_summary',
            'woocommerce_single_product_summary'=> 'Single Product: woocommerce_single_product_summary',
            'woocommerce_before_add_to_cart_form'       => 'Single Product: woocommerce_before_add_to_cart_form',
            'woocommerce_after_add_to_cart_button' => 'Single Product: woocommerce_after_add_to_cart_button',
            'woocommerce_after_add_to_cart_form'=> 'Single Product: woocommerce_after_add_to_cart_form',
            'woocommerce_product_meta_start'=> 'Single Product: woocommerce_product_meta_start',
            'woocommerce_product_meta_end' => 'Single Product: woocommerce_product_meta_end',
            'woocommerce_after_single_product_summary' => 'Single Product: woocommerce_after_single_product_summary',
            'woocommerce_after_single_product' => 'Single Product: woocommerce_after_single_product',
    
            'woocommerce_before_shop_loop_item'=> 'Archive Product: woocommerce_before_shop_loop_item',
            'woocommerce_before_shop_loop_item_title' => 'Archive Product: woocommerce_before_shop_loop_item_title',
            'woocommerce_shop_loop_item_title' => 'Archive Product: woocommerce_shop_loop_item_title',
            'woocommerce_after_shop_loop_item_title' => 'Archive Product: woocommerce_after_shop_loop_item_title',
            'woocommerce_after_shop_loop_item' => 'Archive Product: woocommerce_after_shop_loop_item',
    
            'custom_hook' => 'Custom Hook',
        ];
    
        echo '<div class="gsbwc-row">';
    
        // Attribute
        echo '<select name="gsbwc_attributes_hooks['.$index.'][attribute]">';
        echo '<option value="">-- Select Attribute --</option>';
        foreach($taxes as $t){
            $val = 'pa_'.$t->attribute_name;
            echo '<option value="'.esc_attr($val).'" '.selected($attribute,$val,false).'>'.esc_html($t->attribute_label).'</option>';
        }
        echo '</select>';
    
        // Hook
        echo '<select class="gsbwc-hook-select" data-index="'.$index.'" name="gsbwc_attributes_hooks['.$index.'][hook]">';
        foreach($hooks as $val=>$label){
            echo '<option value="'.esc_attr($val).'" '.selected($hook,$val,false).'>'.esc_html($label).'</option>';
        }
        echo '</select>';
    
        // Custom hook input (shown only if selected)
        $custom_style = ($hook === 'custom_hook') ? "" : "display:none;";
        echo '<input type="text" placeholder="Custom action hook" 
                name="gsbwc_attributes_hooks['.$index.'][custom_hook]" 
                class="gsbwc-custom-hook-'.$index.'"
                value="'.esc_attr($custom_hook).'" 
                style="margin-left:10px; width:200px; '.$custom_style.'">';
    
        // Priority
        echo '<input type="number" name="gsbwc_attributes_hooks['.$index.'][priority]" value="'.esc_attr($priority).'" min="1" style="width:80px;margin-left:10px;">';
    
        echo '<button type="button" class="button gsbwc-remove-row" style="margin-left:10px;">Remove</button>';
        echo '</div>';
    }

    public function settings(){
        register_setting('gsbwc_settings','gsbwc_enable_frontend');
        register_setting('gsbwc_settings','gsbwc_taxonomy');
        register_setting('gsbwc_settings','gsbwc_bgcolor');
        register_setting('gsbwc_settings','gsbwc_color');

        register_setting(
            'gsbwc_settings',
            'gsbwc_attributes_hooks',
            array(
                'type'      => 'array',
                'default'   => array(),
                'sanitize_callback' => function($input){
                    $output = array();
                    if (is_array($input)) {
                        foreach ($input as $i => $row) {
                            if (!empty($row['attribute']) && !empty($row['hook'])) {
                
                                $hook = sanitize_text_field($row['hook']);
                                $custom_hook = '';
                
                                if ($hook === 'custom_hook' && !empty($row['custom_hook'])) {
                                    $custom_hook = sanitize_key($row['custom_hook']);
                                    $hook = $custom_hook; // override
                                }
                
                                $output[$i] = array(
                                    'attribute'   => sanitize_text_field($row['attribute']),
                                    'hook'        => $hook,
                                    'custom_hook' => $custom_hook,
                                    'priority'    => isset($row['priority']) ? intval($row['priority']) : 10,
                                );
                            }
                        }
                    }
                    return $output;
                }

            )
        );

        register_setting(
            'gsbwc_settings',
            'gsbwc_enabled_taxonomies',
            array(
                'type' => 'array',
                'default' => array(),
                'sanitize_callback' => function($val){
                    return array_map('sanitize_text_field', (array) $val);
                }
            )
        );
        
        add_settings_section('gsbwc_main','Badge Settings',null,'gsbwc');

        add_settings_field('enable','Enable Output',function(){
            $v=get_option('gsbwc_enable_frontend','yes');
            echo '<input type="checkbox" name="gsbwc_enable_frontend" value="yes" '.checked('yes',$v,false).'>';
        },'gsbwc','gsbwc_main');
        
        add_settings_field(
            'gsbwc_attributes_hooks',
            'Attributes & Badge Locations',
            function() {
                $data = get_option('gsbwc_attributes_hooks', array());
        
                // Render existing rows
                if (!empty($data)) {
                    foreach ($data as $i => $item) {
                        $this->render_attribute_hook_row(
                            $i,
                            $item['attribute'],
                            $item['hook'],
                            $item['priority'] ?? 10,
                            $item['custom_hook'] ?? ''
                        );
                    }
                }
        
                // Empty row for new
                $next = is_array($data) ? count($data) : 0;
                $this->render_attribute_hook_row($next, '', '', 10, '');
                echo '<p><button type="button" class="button" id="gsbwc-add-row">Add Attribute</button></p>';
            },
            'gsbwc',
            'gsbwc_main'
        );

        add_settings_field('bg','Badge BG Color',function(){
            $v=get_option('gsbwc_bgcolor','#000');
            echo '<input type="color" name="gsbwc_bgcolor" value="'.$v.'">';
        },'gsbwc','gsbwc_main');

        add_settings_field('color','Badge Text Color',function(){
            $v=get_option('gsbwc_color','#fff');
            echo '<input type="color" name="gsbwc_color" value="'.$v.'">';
        },'gsbwc','gsbwc_main');
        
         add_settings_field(
            'gsbwc_enabled_taxonomies',
            'Enable Term Colors For',
            function() {
        
                $taxes = wc_get_attribute_taxonomies();
                $saved = get_option('gsbwc_enabled_taxonomies', array());
        
                echo '<select name="gsbwc_enabled_taxonomies[]" id="gsbwc_enabled_taxonomies" multiple style="width:400px;">';
        
                if ($taxes) {
                    foreach ($taxes as $tax) {
                        $val = 'pa_' . $tax->attribute_name;
                        echo '<option value="'.$val.'" '.selected(in_array($val,$saved), true, false).'>'.$tax->attribute_label.'</option>';
                    }
                }
        
                echo '</select>';
        
                echo '<p class="description">Choose which attributes should have term-level colors, icons, and overrides.</p>';
            },
            'gsbwc',
            'gsbwc_main'
        );
        
    }

    public function page(){
        echo '<div class="wrap"><h1>GS Badges Settings</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('gsbwc_settings');
        do_settings_sections('gsbwc');
        submit_button();
        echo '</form>';

        echo '<h2>AJAX Preview</h2>';
        echo '<button id="gsbwc-preview" class="button button-primary">Preview Badges</button>';
        echo '<div id="gsbwc-preview-area" style="margin-top:10px;"></div>';
        echo '</div>';
    }

    public function admin_assets($hook) {
        if ($hook !== 'settings_page_gsbwc') return;
    
        wp_enqueue_script('select2');
        wp_enqueue_style('select2');
    
        wp_enqueue_script(
            'gsbwc-admin',
            plugins_url('../assets/js/admin.js', __FILE__),
            array('jquery','select2'),
            '1.0.3',
            true
        );
    
        wp_localize_script(
            'gsbwc-admin',
            'GSBWC',
            array(
                'ajax' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('gsbwc_nonce')
            )
        );
    }

    public function ajax_preview(){
        $bg=get_option('gsbwc_bgcolor','#000');
        $color=get_option('gsbwc_color','#fff');
        echo '<span style="padding:6px 10px;background:'.$bg.';color:'.$color.';border-radius:4px;">Sample Badge</span>';
        wp_die();
    }
}
