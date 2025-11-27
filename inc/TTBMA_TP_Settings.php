<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBMA_TP_Settings')) {
		class TTBMA_TP_Settings {
			public function __construct() {
				add_action('ttbm_tour_pricing_inner', array($this, 'pricing'), 10, 1);
				add_action('ttbma_tp_item', array($this, 'pricing_item'), 10, 2);
				add_action('ttbm_settings_save', array($this, 'settings_save'), 20, 1);
			}
			public function pricing($tour_id) {
				$ticket_infos = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_ticket_type', array());
				if (sizeof($ticket_infos) > 0) {
					$tp_price_infos = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_tp_price_infos', array());
					$display_tp = TTBM_Global_Function::get_post_info($tour_id, 'ttbm_display_tp', 'off');
					$checked = $display_tp == 'off' ? '' : 'checked';
					$active = $display_tp == 'off' ? '' : 'mActive';
					?>
                    <section class="gptLayout">
                        <div class="alignCenter justifyBetween">
                            <h5><span class="mi mi-shopping-cart-add _mR_xs"></span> <?php esc_html_e('Tiered Pricing / Group Discount', 'ttbm-addon-tiered-pricing-and-group-discount'); ?></h5>
							<?php TTBM_Custom_Layout::switch_button('ttbm_display_tp', $checked); ?>
                        </div>
                        <div data-collapse="#ttbm_display_tp" class="<?php echo esc_attr($active); ?>">
                            <div class="divider"></div>
                            <div class="dLayout">
                                <div class="ttbm_settings_area">
                                    <div class="ovAuto">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th><?php esc_html_e('Tier Name', 'ttbm-addon-tiered-pricing-and-group-discount'); ?><span class="textRequired">&nbsp;*</span></th>
                                                <th><?php esc_html_e('Min People', 'ttbm-addon-tiered-pricing-and-group-discount'); ?><span class="textRequired">&nbsp;*</span></th>
                                                <th><?php esc_html_e('Max People', 'ttbm-addon-tiered-pricing-and-group-discount'); ?><span class="textRequired">&nbsp;*</span></th>
                                                <th><?php esc_html_e('Discount %', 'ttbm-addon-tiered-pricing-and-group-discount'); ?><span class="textRequired">&nbsp;*</span></th>
                                                <th class="textCenter"><?php esc_html_e('Action', 'ttbm-addon-tiered-pricing-and-group-discount'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody class="ttbm_sortable_area ttbm_item_insert">
											<?php
												if (sizeof($tp_price_infos) > 0) {
													foreach ($tp_price_infos as $field) {
														$this->pricing_item($field);
													}
												}
											?>
                                            </tbody>
                                        </table>
                                    </div>
									<?php TTBM_Custom_Layout::add_new_button(esc_html__('Add New Tiered Pricing', 'ttbm-addon-tiered-pricing-and-group-discount')); ?>
									<?php do_action('add_ttbm_hidden_table', 'ttbma_tp_item'); ?>
                                </div>
                            </div>
                        </div>
                    </section>
					<?php
				}
			}
			public function pricing_item($field = array()) {
				$field = $field ?: array();
				$price = array_key_exists('discount', $field) ? $field['discount'] : '';
				$labels = array_key_exists('label', $field) ? $field['label'] : '';
				$start_qty = array_key_exists('start_qty', $field) ? $field['start_qty'] : '';
				$end_qty = array_key_exists('end_qty', $field) ? $field['end_qty'] : '';
				?>
                <tr class="ttbm_remove_area">
                    <td>
                        <label>
                            <input type="text" class="formControl ttbm_name_validation" name="ttbma_tp_label[]" placeholder="" value="<?php echo esc_attr($labels); ?>"/>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="text" class="formControl ttbm_number_validation" name="ttbma_tp_start_qty[]" placeholder="0" value="<?php echo esc_attr($start_qty); ?>"/>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="text" class="formControl ttbm_number_validation" name="ttbma_tp_end_qty[]" placeholder="10" value="<?php echo esc_attr($end_qty); ?>"/>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="text" class="formControl ttbm_price_validation" name="ttbma_tp_percent[]" placeholder="5" value="<?php echo esc_attr($price); ?>"/>
                        </label>
                    </td>
                    <td>
						<?php TTBM_Custom_Layout::move_remove_button(); ?>
                    </td>
                </tr>
				<?php
			}
			public function settings_save($tour_id) {
				if (get_post_type($tour_id) == 'ttbm_tour') {
					$new_price = array();
					$label = isset($_POST['ttbma_tp_label']) ? array_map('sanitize_text_field', wp_unslash($_POST['ttbma_tp_label'])) : [];
					$start_qty = isset($_POST['ttbma_tp_start_qty']) ? array_map('sanitize_text_field', wp_unslash($_POST['ttbma_tp_start_qty'])) : [];
					$end_qty = isset($_POST['ttbma_tp_end_qty']) ? array_map('sanitize_text_field', wp_unslash($_POST['ttbma_tp_end_qty'])) : [];
					$price = isset($_POST['ttbma_tp_percent']) ? array_map('sanitize_text_field', wp_unslash($_POST['ttbma_tp_percent'])) : [];
					$count = count($start_qty);
					for ($i = 0; $i < $count; $i++) {
						if ($start_qty[$i] && $start_qty[$i] >= 0 && $end_qty[$i] && $end_qty[$i] >= 0 && $price[$i] && $price[$i] >= 0) {
							$new_price[$i]['label'] = $label[$i];
							$new_price[$i]['start_qty'] = $start_qty[$i];
							$new_price[$i]['end_qty'] = $end_qty[$i];
							$new_price[$i]['discount'] = $price[$i];
						}
					}
					update_post_meta($tour_id, 'ttbm_tp_price_infos', $new_price);
					$ttbm_display_tp = isset($_POST['ttbm_display_tp']) && sanitize_text_field(wp_unslash($_POST['ttbm_display_tp'])) ? 'on' : 'off';
					update_post_meta($tour_id, 'ttbm_display_tp', $ttbm_display_tp);
				}
			}
		}
		new TTBMA_TP_Settings();
	}