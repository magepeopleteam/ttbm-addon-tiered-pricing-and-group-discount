<?php
	/**
	 * Plugin Name: Tiered Pricing and Group Discount addon for WpTravelly
	 * Plugin URI: http://mage-people.com
	 * Description: A Tiered Pricing and Group Discount addon for WpTravelly by MagePeople.
	 * Version: 1.0.0
	 * Author: MagePeople Team
	 * Author URI: http://www.mage-people.com/
	 * Text Domain: ttbm-addon-tiered-pricing-and-group-discount
	 * Domain Path: /languages/
	 */
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBM_Tiered_Pricing_Addon')) {
		class TTBM_Tiered_Pricing_Addon {
			public function __construct() {
				$this->load_plugin();
			}
			private function load_plugin(): void {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
				if (!defined('TTBM_ADDON_TIERED_DIR')) {
					define('TTBM_ADDON_TIERED_DIR', dirname(__FILE__));
				}
				if (!defined('TTBM_ADDON_TIERED_URL')) {
					define('TTBM_ADDON_TIERED_URL', plugins_url() . '/' . plugin_basename(dirname(__FILE__)));
				}
				if (is_plugin_active('tour-booking-manager/tour-booking-manager.php')) {
					require_once TTBM_ADDON_TIERED_DIR . '/inc/TTBM_Addon_Tiered_Dependencies.php';
				}
			}
		}
		new TTBM_Tiered_Pricing_Addon();
	}