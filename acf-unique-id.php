<?php
/**
 * Adds unique fields to ACF
 * 
 * @author    Stefano Fasoli <stefanofasoli17@gmail.com>
 *
 * Plugin Name:  ACF Unique ID
 * Description:  Generate unique UUIDs easily. Forked from philipnewcomer/ACF-Unique-ID-Field
 * Version:      1.0.0
 * Author:       Stefano Fasoli
 * Requires PHP: 8.0
 * WP version:   6.1.0
*/
defined('ABSPATH') or die;

class unique_id_field extends acf_field {

	public function initialize() 
	{
		$this->name     = 'unique_id';
		$this->label    = 'Unique ID';
		$this->category = 'basic';
	}

	/**
	 * Render the HTML field.
	 *
	 * @param array $field The field data.
	 */
	public function render_field( $field ) {
		printf(
			'<input type="text" name="%s" value="%s" readonly>',
			esc_attr( $field['name'] ),
			esc_attr( $field['value'] ),
		);
	}

	public function render_field_settings($field) {
		// display options
		acf_render_field_setting(
			$field, [
				'label'        => __( 'Show in Rest', 'acf-unique-id' ),
				'instructions' => '',
				'type'         => 'true_false',
				'name'         => 'show_in_rest',
				'ui'           => 1,
			]
		);
	}

	// Hides the field from rest API
	public function load_field($field)
	{
		if ( ! $field['show_in_rest']) {
			$this->show_in_rest = false;
		}

		return $field;
	}

	public function format_value($value, $post_id, $field) {
		if (empty($value)) {
			return false;
		}

		return $value;
	}

	/**
	 * Define the unique ID if one does not already exist.
	 *
	 * @param string $value   The field value.
	 * @param int    $post_id The post ID.
	 * @param array  $field   The field data.
	 *
	 * @return string The filtered value.
	 */
	public function update_value( $value, $post_id, $field ) {
		if ( ! empty( $value ) ) {
			return $value;
		}

		return uniqid();
	}
}

add_action('acf/include_field_types', fn () => acf_register_field_type('unique_id_field'));
