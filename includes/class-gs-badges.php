<?php
if (!defined('ABSPATH')) exit;

class CODENIT_WC_Badges {

    public function __construct(){
        add_action('wp', [$this,'register_attribute_hooks']);
        add_action('wp_enqueue_scripts', [$this,'frontend_css']);
    }

    public function register_attribute_hooks(){
        
        $pairs = get_option('gsbwc_attributes_hooks', []);
        
        if(empty($pairs)) return;

        foreach($pairs as $pair){
            $attribute = $pair['attribute'] ?? '';
            $hook = $pair['hook'] ?? '';
            $priority = isset($pair['priority']) ? intval($pair['priority']) : 10;
        
            if($attribute && $hook){
                add_action($hook, function() use ($attribute){
                    $this->output_attribute_badges($attribute);
                }, $priority);
            }
        }

    }

    public function output_attribute_badges($attribute){
        if (get_option('gsbwc_enable_frontend','yes') !== 'yes') return;

        global $product;

        if (!is_a($product, 'WC_Product')) {
            $product = wc_get_product(get_the_ID());
        }

        if (!$product) return;

        $terms = wp_get_post_terms($product->get_id(), $attribute);
        if (empty($terms)) return;
        
        echo '<div class="gsbwc-badges '.esc_attr($attribute).'">';
        foreach ($terms as $t){
           
            $bg = get_term_meta($t->term_id, 'gsbwc_bg', true);
            $color = get_term_meta($t->term_id, 'gsbwc_color', true);
            $icon = get_term_meta($t->term_id, 'gsbwc_icon', true);
            
            $style = '';
            if ($bg) $style .= "background:$bg;";
            if ($color) $style .= "color:$color;";

            echo '<span class="gsbwc-badge gsbwc-badge-'.esc_attr($t->slug).'" style="'.esc_attr($style).'">';
            if ($icon) {
                echo '<span class="dashicons '.esc_attr($icon).'" style="margin-right:4px;"></span>';
            }
            echo esc_html($t->name) . '</span>';

        }
        echo '</div>';
    }

    public function frontend_css(){
        $bg = get_option('gsbwc_bgcolor', '#000');
        $color = get_option('gsbwc_color', '#fff');

        $css = "
            .gsbwc-badge {
                background: {$bg};
                color: {$color};
                padding: 2px 5px;
                margin-right: 4px;
                border-radius: 4px;
                display: inline-block;
                margin-block: 10px;
            }
        ";

        wp_register_style('gsbwc-frontend', false);
        wp_enqueue_style('gsbwc-frontend');
        wp_add_inline_style('gsbwc-frontend', $css);
    }
}
