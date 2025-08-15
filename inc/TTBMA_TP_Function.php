<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBMA_TP_Function')) {
		class TTBMA_TP_Function {
			public function __construct() {
				add_action('ttbm_booking_panel_inside_form', [$this, 'booking_panel_inside_form'], 10, 2);
				add_filter('ttbm_total_price_filter', [$this, 'total_price_filter'], 10, 4);
			}
			public function booking_panel_inside_form($tour_id) {
				$tp_price_infos = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_tp_price_infos', array());
				$display_tp = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_display_tp', 'off');
				if ($display_tp == 'on' && sizeof($tp_price_infos) > 0) {
					?>
                    <div class="gptLayout ttbm_tier_price_chart">
                        <h6 class="_mB_xs"><?php esc_html_e('Group Discount Tiers', 'ttbm-addon-tiered-pricing-and-group-discount'); ?></h6>
                        <div class="flexEqual _gap_xs">
							<?php foreach ($tp_price_infos as $tp_price_info) {
								$discount = array_key_exists('discount', $tp_price_info) ? $tp_price_info['discount'] : '';
								$labels = array_key_exists('label', $tp_price_info) ? $tp_price_info['label'] : '';
								$start_qty = array_key_exists('start_qty', $tp_price_info) ? $tp_price_info['start_qty'] : '';
								$end_qty = array_key_exists('end_qty', $tp_price_info) ? $tp_price_info['end_qty'] : '';
								?>
                                <div class="dLayout" data-start-qty="<?php echo esc_attr($start_qty); ?>" data-end-qty="<?php echo esc_attr($end_qty); ?>" data-discount="<?php echo esc_attr($discount); ?>">
                                    <p class="_textCenter"><?php echo esc_html($start_qty . ' - ' . $end_qty) . ' ' . esc_html__('Peoples', 'ttbm-addon-tiered-pricing-and-group-discount'); ?></p>
                                    <p class="_textCenter"><?php echo esc_html($labels); ?></p>
                                    <p class="_textCenter_textTheme"><?php echo esc_html($discount . ' % ') . ' ' . esc_html__('Discount', 'ttbm-addon-tiered-pricing-and-group-discount'); ?></p>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
//echo '<pre>';print_r($tp_price_infos);echo '</pre>';
				}
			}
			public function total_price_filter($total_price, $tour_id, $total_qty) {
				$tp_price_infos = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_tp_price_infos', array());
				$display_tp = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_display_tp', 'off');
				if ($display_tp == 'on' && sizeof($tp_price_infos) > 0 && $total_qty > 0) {
					foreach ($tp_price_infos as $tp_price_info) {
						$discount = array_key_exists('discount', $tp_price_info) ? $tp_price_info['discount'] : '';
						$start_qty = array_key_exists('start_qty', $tp_price_info) ? $tp_price_info['start_qty'] : '';
						$end_qty = array_key_exists('end_qty', $tp_price_info) ? $tp_price_info['end_qty'] : '';
						if ($discount && $start_qty && $end_qty && $start_qty <= $total_qty && $end_qty >= $total_qty) {
							$total_price = $total_price - $total_price * $discount / 100;
						}
					}
				}
				return $total_price;
			}
		}
		new TTBMA_TP_Function();
	}