<?php
/**
 * Plugin Name: Content Import Export
 * Plugin URI: https://geniuscreations.com.ng
 * Description: A plugin to import and export all content including posts, pages, comments, custom fields, terms, navigation menus, and custom posts.
 * Author: Blessing Fasina
 * Author URI: https://geniuscreations.com.ng
 * Version: 1.0
 * Text Domain: content-import-export
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants.
define( 'CIE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CIE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files.
require_once CIE_PLUGIN_DIR . 'includes/class-cie-export.php';
require_once CIE_PLUGIN_DIR . 'includes/class-cie-import.php';
require_once CIE_PLUGIN_DIR . 'includes/class-cie-admin.php';

// Initialize the plugin.
function cie_init() {
    $cie_export = new CIE_Export();
    $cie_import = new CIE_Import();
    $cie_admin = new CIE_Admin();
}
add_action( 'plugins_loaded', 'cie_init' );
