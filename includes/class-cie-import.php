<?php

class CIE_Import {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_post_cie_import_content', array( $this, 'import_content' ) );
    }

    public function add_admin_menu() {
        add_submenu_page(
            'tools.php',
            'Import Content',
            'Import Content',
            'manage_options',
            'cie-import',
            array( $this, 'import_page' )
        );
    }

    public function import_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Import Content', 'content-import-export' ); ?></h1>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="cie_import_content">
                <?php wp_nonce_field( 'cie_import_nonce', 'cie_import_nonce' ); ?>
                <p><?php esc_html_e( 'Upload the exported content file:', 'content-import-export' ); ?></p>
                <p><input type="file" name="cie_import_file" required></p>
                <?php submit_button( __( 'Import', 'content-import-export' ) ); ?>
            </form>
        </div>
        <?php
    }

    public function import_content() {
        // Check nonce for security.
        check_admin_referer( 'cie_import_nonce', 'cie_import_nonce' );

        // Check if a file has been uploaded.
        if ( ! isset( $_FILES['cie_import_file'] ) || $_FILES['cie_import_file']['error'] != UPLOAD_ERR_OK ) {
            wp_die( __( 'No file uploaded or there was an upload error.', 'content-import-export' ) );
        }

        // Import logic goes here...

        wp_redirect( admin_url( 'tools.php?page=cie-import&imported=true' ) );
        exit;
    }
}
