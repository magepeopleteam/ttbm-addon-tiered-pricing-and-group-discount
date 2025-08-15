<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'TTBM_Addon_Tiered_Dependencies' ) ) {
		class TTBM_Addon_Tiered_Dependencies {
			public function __construct() {
				add_action( 'init', array( $this, 'language_load' ) );
				$this->load_file();
				add_action( 'ttbm_admin_script', array( $this, 'admin_script' ) );
				add_action( 'add_ttbm_registration_enqueue', array( $this, 'ttbm_registration_enqueue' ) );
			}
			public function language_load() {
				$plugin_dir = basename( dirname( __DIR__ ) ) . "/languages/";
				load_plugin_textdomain( 'ttbm-addon-tiered-pricing-and-group-discount', false, $plugin_dir );
			}
			private function load_file() {
				require_once TTBM_ADDON_TIERED_DIR . '/inc/TTBMA_TP_Settings.php';
				require_once TTBM_ADDON_TIERED_DIR . '/inc/TTBMA_TP_Function.php';
			}
			public function admin_script() {
				wp_enqueue_style( 'ttbma_admin_tp', TTBM_ADDON_TIERED_URL . '/assets/ttbma_admin_tp.css', array(), time() );
				wp_enqueue_script( 'ttbma_admin_tp', TTBM_ADDON_TIERED_URL . '/assets/ttbma_admin_tp.js', array( 'jquery' ), time() );
			}
			public function ttbm_registration_enqueue() {
				wp_enqueue_style( 'ttbma_tp', TTBM_ADDON_TIERED_URL . '/assets/ttbma_tp.css', array(), time() );
				wp_enqueue_script( 'ttbma_tp', TTBM_ADDON_TIERED_URL . '/assets/ttbma_tp.js', array( 'jquery' ), time() );
			}
		}
		new TTBM_Addon_Tiered_Dependencies();
	}