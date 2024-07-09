<?php

class CIE_Export {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_post_cie_export_content', array( $this, 'export_content' ) );
    }

    public function add_admin_menu() {
        add_submenu_page(
            'tools.php',
            'Export Content',
            'Export Content',
            'manage_options',
            'cie-export',
            array( $this, 'export_page' )
        );
    }

    public function export_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Export Content', 'content-import-export' ); ?></h1>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="cie_export_content">
                <?php wp_nonce_field( 'cie_export_nonce', 'cie_export_nonce' ); ?>
                <p><?php esc_html_e( 'Select the content you want to export:', 'content-import-export' ); ?></p>
                <p>
                    <input type="checkbox" name="content_types[]" value="posts" checked> <?php esc_html_e( 'Posts', 'content-import-export' ); ?><br>
                    <input type="checkbox" name="content_types[]" value="pages" checked> <?php esc_html_e( 'Pages', 'content-import-export' ); ?><br>
                    <input type="checkbox" name="content_types[]" value="comments" checked> <?php esc_html_e( 'Comments', 'content-import-export' ); ?><br>
                    <input type="checkbox" name="content_types[]" value="custom_fields" checked> <?php esc_html_e( 'Custom Fields', 'content-import-export' ); ?><br>
                    <input type="checkbox" name="content_types[]" value="terms" checked> <?php esc_html_e( 'Terms', 'content-import-export' ); ?><br>
                    <input type="checkbox" name="content_types[]" value="menus" checked> <?php esc_html_e( 'Navigation Menus', 'content-import-export' ); ?><br>
                    <input type="checkbox" name="content_types[]" value="custom_posts" checked> <?php esc_html_e( 'Custom Posts', 'content-import-export' ); ?>
                </p>
                <?php submit_button( __( 'Export', 'content-import-export' ) ); ?>
            </form>
        </div>
        <?php
    }

    public function export_content() {
        // Check nonce for security.
        check_admin_referer( 'cie_export_nonce', 'cie_export_nonce' );

        // Get selected content types.
        $content_types = isset( $_POST['content_types'] ) ? array_map( 'sanitize_text_field', $_POST['content_types'] ) : array();

        if ( empty( $content_types ) ) {
            wp_die( __( 'No content types selected.', 'content-import-export' ) );
        }

        // Export logic goes here...

        // Temporary file creation
        $file = tempnam(sys_get_temp_dir(), 'cie_export_');
        $zip = new ZipArchive();
        if ($zip->open($file, ZipArchive::CREATE) === TRUE) {
            // Add content files to the ZIP archive...
            $zip->close();
        } else {
            wp_die( __( 'Failed to create export file.', 'content-import-export' ) );
        }

        // Download file and remove it after download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="content-export.zip"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        unlink($file);

        exit;
    }
}
