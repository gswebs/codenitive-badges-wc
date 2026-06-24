<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CODENIT_WC_Badges {

	public function __construct() {
		add_action( 'wp', array( $this, 'register_attribute_hooks' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_css' ) );
	}

	public function register_attribute_hooks() {
		$pairs = get_option( 'gsbwc_attributes_hooks', array() );

		if ( empty( $pairs ) || ! is_array( $pairs ) ) {
			return;
		}

		foreach ( $pairs as $pair ) {
			$attribute   = isset( $pair['attribute'] ) ? sanitize_text_field( $pair['attribute'] ) : '';
			$hook        = isset( $pair['hook'] ) ? sanitize_text_field( $pair['hook'] ) : '';
			$custom_hook = isset( $pair['custom_hook'] ) ? sanitize_key( $pair['custom_hook'] ) : '';
			$priority    = isset( $pair['priority'] ) ? absint( $pair['priority'] ) : 10;

			if ( 'custom_hook' === $hook && ! empty( $custom_hook ) ) {
				$hook = $custom_hook;
			}

			if ( empty( $attribute ) || empty( $hook ) ) {
				continue;
			}

			add_action(
				$hook,
				function() use ( $attribute ) {
					$this->output_attribute_badges( $attribute );
				},
				$priority
			);
		}
	}

	public function output_attribute_badges( $attribute ) {
		if ( 'yes' !== get_option( 'gsbwc_enable_frontend', 'yes' ) ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			$product = wc_get_product( get_the_ID() );
		}

		if ( ! $product ) {
			return;
		}

		$terms = wp_get_post_terms( $product->get_id(), $attribute );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		echo '<div class="gsbwc-badges ' . esc_attr( $attribute ) . '">';

		foreach ( $terms as $term ) {
			$bg    = get_term_meta( $term->term_id, 'gsbwc_bg', true );
			$color = get_term_meta( $term->term_id, 'gsbwc_color', true );
			$icon  = get_term_meta( $term->term_id, 'gsbwc_icon', true );

			$style = '';

			if ( $bg ) {
				$style .= 'background:' . sanitize_hex_color( $bg ) . ';';
			}

			if ( $color ) {
				$style .= 'color:' . sanitize_hex_color( $color ) . ';';
			}

			echo '<span class="gsbwc-badge gsbwc-badge-' . esc_attr( $term->slug ) . '" style="' . esc_attr( $style ) . '">';

			if ( $icon ) {
				echo '<span class="dashicons ' . esc_attr( $icon ) . '" style="margin-right:4px;"></span>';
			}

			echo esc_html( $term->name );
			echo '</span>';
		}

		echo '</div>';
	}

	public function frontend_css() {
		$bg    = sanitize_hex_color( get_option( 'gsbwc_bgcolor', '#000000' ) );
		$color = sanitize_hex_color( get_option( 'gsbwc_color', '#ffffff' ) );

		if ( ! $bg ) {
			$bg = '#000000';
		}

		if ( ! $color ) {
			$color = '#ffffff';
		}

		$css = "
			.gsbwc-badge {
				background: {$bg};
				color: {$color};
				padding: 2px 5px;
				margin-right: 4px;
				border-radius: 4px;
				display: inline-block;
				margin-block: 3px;
			}
		";

		wp_register_style( 'gsbwc-frontend', false, array(), '1.0.6' );
		wp_enqueue_style( 'gsbwc-frontend' );
		wp_add_inline_style( 'gsbwc-frontend', $css );
	}
}