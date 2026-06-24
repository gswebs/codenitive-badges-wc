<?php
/*
* Plugin Name: Codenitive Badges for WooCommerce
* Plugin URI:  https://wordpress.org/plugins/codenitive-badges-wc
* Description: Advanced product badges using attributes, multiple badges, styling, and AJAX preview.
* Version: 1.0.0
* Requires at least: 5.6
* Requires PHP:      7.4
* Author:            CodeNitive
* Author URI:        https://codenitive.com
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:       codenitive-badges-wc
*
* @package codenitive-badges-wc
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('CODENIT_WC_BADGES_DIR', plugin_dir_path(__FILE__));
define( 'CODENIT_WC_BADGES_URL', plugin_dir_url( __FILE__ ) );

// Load classes
require_once CODENIT_WC_BADGES_DIR . 'includes/class-gs-badges.php';
require_once CODENIT_WC_BADGES_DIR . 'includes/class-gs-settings.php';
require_once CODENIT_WC_BADGES_DIR . 'includes/class-gs-term-fields.php';

new CODENIT_WC_Settings();
new CODENIT_WC_Badges();

// Uninstall cleanup
register_uninstall_hook(__FILE__, 'gsbwc_uninstall');
function gsbwc_uninstall(){
    delete_post_meta_by_key('_gsbwc_badge_terms');
}
