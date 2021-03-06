<?php
/**
 * Email Downloads.
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/emails/plain/email-downloads.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.4.0
 */

defined( 'ABSPATH' ) || exit;

echo esc_html( wc_strtoupper( __( 'Downloads', 'classic-commerce' ) ) ) . "\n\n";

foreach ( $downloads as $download ) {
	foreach ( $columns as $column_id => $column_name ) {
		echo wp_kses_post( $column_name ) . ': ';

		if ( has_action( 'woocommerce_email_downloads_column_' . $column_id ) ) {
			do_action( 'woocommerce_email_downloads_column_' . $column_id, $download, $plain_text );
		} else {
			switch ( $column_id ) {
				case 'download-product':
					echo esc_html( $download['product_name'] );
					break;
				case 'download-file':
					echo esc_html( $download['download_name'] ) . ' - ' . esc_url( $download['download_url'] );
					break;
				case 'download-expires':
					if ( ! empty( $download['access_expires'] ) ) {
						echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) );
					} else {
						esc_html_e( 'Never', 'classic-commerce' );
					}
					break;
			}
		}
		echo "\n";
	}
	echo "\n";
}
echo '=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=';
echo "\n\n";
