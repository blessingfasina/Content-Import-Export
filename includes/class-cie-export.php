<?php

class CIE_Export {

    public function __construct() {
        add_action( 'admin_post_cie_export_content', array( $this, 'export_content' ) );
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

        $export_data = array();

        // Export logic for each content type.
        if ( in_array( 'posts', $content_types ) ) {
            $export_data['posts'] = get_posts( array( 'numberposts' => -1 ) );
        }
        if ( in_array( 'pages', $content_types ) ) {
            $export_data['pages'] = get_pages();
        }
        if ( in_array( 'comments', $content_types ) ) {
            $export_data['comments'] = get_comments();
        }
        if ( in_array( 'custom_fields', $content_types ) ) {
            // Fetch custom fields logic.
        }
        if ( in_array( 'terms', $content_types ) ) {
            $export_data['terms'] = get_terms( array( 'hide_empty' => false ) );
        }
        if ( in_array( 'menus', $content_types ) ) {
            $export_data['menus'] = wp_get_nav_menus();
        }
        if ( in_array( 'custom_posts', $content_types ) ) {
            // Fetch custom post types logic.
            $custom_post_types = get_post_types( array( '_builtin' => false ), 'objects' );
            foreach ( $custom_post_types as $post_type ) {
                $export_data['custom_posts'][ $post_type->name ] = get_posts( array( 'post_type' => $post_type->name, 'numberposts' => -1 ) );
            }
        }

        // Create JSON file.
        $json_data = json_encode( $export_data );

        // Create ZIP file.
        $zip = new ZipArchive();
        $zip_file = tempnam( sys_get_temp_dir(), 'cie_export_' ) . '.zip';
        if ( $zip->open( $zip_file, ZipArchive::CREATE ) === TRUE ) {
            $zip->addFromString( 'content.json', $json_data );
            $zip->close();
        } else {
            wp_die( __( 'Failed to create export file.', 'content-import-export' ) );
        }

        // Download ZIP file and remove it after download.
        header( 'Content-Type: application/zip' );
        header( 'Content-Disposition: attachment; filename="content-export.zip"' );
        header( 'Content-Length: ' . filesize( $zip_file ) );
        readfile( $zip_file );
        unlink( $zip_file );

        exit;
    }
}
