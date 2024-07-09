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
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Content Import Export Settings', 'content-import-export' ); ?></h1>
            <p><?php esc_html_e( 'Use the Export and Import options from the Tools menu.', 'content-import-export' ); ?></p>
        </div>
        <?php
    }
}
