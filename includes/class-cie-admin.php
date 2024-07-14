<?php

class CIE_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
    }

    public function add_admin_menu() {
        add_menu_page(
            'Content Import Export',
            'Content Import Export',
            'manage_options',
            'cie-admin',
            array( $this, 'admin_page' ),
            'dashicons-migrate'
        );
    }

    public function admin_page() {
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'export';
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Content Import Export Settings', 'content-import-export' ); ?></h1>
            <h2 class="nav-tab-wrapper">
                <a href="?page=cie-admin&tab=export" class="nav-tab <?php echo $active_tab == 'export' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Export Content', 'content-import-export' ); ?></a>
                <a href="?page=cie-admin&tab=import" class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Import Content', 'content-import-export' ); ?></a>
            </h2>
            <div class="tab-content">
                <?php
                if ( $active_tab == 'export' ) {
                    require_once CIE_PLUGIN_DIR . 'includes/class-cie-export.php';
                    $cie_export = new CIE_Export();
                    $cie_export->export_page();
                } else {
                    require_once CIE_PLUGIN_DIR . 'includes/class-cie-import.php';
                    $cie_import = new CIE_Import();
                    $cie_import->import_page();
                }
                ?>
            </div>
        </div>
        <?php
    }
}
?>
