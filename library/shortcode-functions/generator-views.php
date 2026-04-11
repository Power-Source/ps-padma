<?php
/**
 * PS Padma: Shortcode Generator Views
 *
 * Rendert die Formularfelder im Shortcode-Builder-Popup.
 * Jede statische Methode entspricht einem Feld-Typ aus Padma_Generator_Data.
 *
 * @package Padma
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Padma_Generator_Views {

	/**
	 * Text-Input
	 */
	public static function text( $id, $field ) {
		$field = wp_parse_args( $field, array( 'default' => '' ) );
		return '<input type="text" name="' . esc_attr( $id ) . '" value="' . esc_attr( $field['default'] ) . '" id="su-generator-attr-' . esc_attr( $id ) . '" class="su-generator-attr" />';
	}

	/**
	 * Textarea
	 */
	public static function textarea( $id, $field ) {
		$field = wp_parse_args( $field, array( 'rows' => 3, 'default' => '' ) );
		return '<textarea name="' . esc_attr( $id ) . '" id="su-generator-attr-' . esc_attr( $id ) . '" rows="' . intval( $field['rows'] ) . '" class="su-generator-attr">' . esc_textarea( $field['default'] ) . '</textarea>';
	}

	/**
	 * Select / Dropdown
	 */
	public static function select( $id, $field ) {
		$multiple = isset( $field['multiple'] ) ? ' multiple' : '';
		$output   = '<select name="' . esc_attr( $id ) . '" id="su-generator-attr-' . esc_attr( $id ) . '" class="su-generator-attr"' . $multiple . '>';
		foreach ( $field['values'] as $val => $label ) {
			$selected = ( (string) $field['default'] === (string) $val ) ? ' selected="selected"' : '';
			$output  .= '<option value="' . esc_attr( $val ) . '"' . $selected . '>' . esc_html( $label ) . '</option>';
		}
		$output .= '</select>';
		return $output;
	}

	/**
	 * Boolean Toggle (Yes/No Switch)
	 */
	public static function bool( $id, $field ) {
		$field   = wp_parse_args( $field, array( 'default' => 'no' ) );
		$class   = 'su-generator-switch su-generator-switch-' . esc_attr( $field['default'] );
		$output  = '<span class="' . $class . '">';
		$output .= '<span class="su-generator-yes">' . __( 'Ja', 'ps-padma' ) . '</span>';
		$output .= '<span class="su-generator-no">' . __( 'Nein', 'ps-padma' ) . '</span>';
		$output .= '</span>';
		$output .= '<input type="hidden" name="' . esc_attr( $id ) . '" value="' . esc_attr( $field['default'] ) . '" id="su-generator-attr-' . esc_attr( $id ) . '" class="su-generator-attr su-generator-switch-value" />';
		return $output;
	}

	/**
	 * Farb-Picker (using Farbtastic)
	 */
	public static function color( $id, $field ) {
		$field = wp_parse_args( $field, array( 'default' => '#000000' ) );
		return '<span class="su-generator-select-color">'
			. '<span class="su-generator-select-color-wheel"></span>'
			. '<input type="text" name="' . esc_attr( $id ) . '" value="' . esc_attr( $field['default'] ) . '" id="su-generator-attr-' . esc_attr( $id ) . '" class="su-generator-attr su-generator-select-color-value" />'
			. '</span>';
	}

	/**
	 * Slider (using SimpleSlider)
	 */
	public static function slider( $id, $field ) {
		$field = wp_parse_args( $field, array(
			'default' => 0,
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
		) );
		return '<input type="text" name="' . esc_attr( $id ) . '" value="' . esc_attr( $field['default'] ) . '" id="su-generator-attr-' . esc_attr( $id ) . '" class="su-generator-attr su-generator-range-picker" min="' . intval( $field['min'] ) . '" max="' . intval( $field['max'] ) . '" step="' . esc_attr( $field['step'] ) . '" />';
	}

	/**
	 * Alias: 'number' field type → same as slider
	 */
	public static function number( $id, $field ) {
		return self::slider( $id, $field );
	}

	/**
	 * File/Image Upload (öffnet WordPress Media Manager)
	 */
	public static function upload( $id, $field ) {
		$field = wp_parse_args( $field, array( 'default' => '' ) );
		return '<input type="text" name="' . esc_attr( $id ) . '" value="' . esc_attr( $field['default'] ) . '" id="su-generator-attr-' . esc_attr( $id ) . '" class="su-generator-attr su-generator-upload-value" />'
			. '<div class="su-generator-field-actions">'
			. '<a href="javascript:;" class="button su-generator-upload-button">'
			. '<span class="dashicons dashicons-admin-media" aria-hidden="true"></span> '
			. __( 'Medienverwaltung', 'ps-padma' )
			. '</a>'
			. '</div>';
	}

	/**
	 * Icon-Picker (Font Awesome)
	 */
	public static function icon( $id, $field ) {
		$field = wp_parse_args( $field, array( 'default' => '' ) );
		return '<input type="text" name="' . esc_attr( $id ) . '" value="' . esc_attr( $field['default'] ) . '" id="su-generator-attr-' . esc_attr( $id ) . '" class="su-generator-attr su-generator-icon-picker-value" />'
			. '<div class="su-generator-field-actions">'
			. '<a href="javascript:;" class="button su-generator-icon-picker-button su-generator-field-action">'
			. '<span class="dashicons dashicons-star-filled" aria-hidden="true"></span> '
			. __( 'Icon wählen', 'ps-padma' )
			. '</a>'
			. '</div>'
			. '<div class="su-generator-icon-picker su-generator-clearfix">'
			. '<input type="text" class="widefat" placeholder="' . esc_attr__( 'Icons filtern', 'ps-padma' ) . '" />'
			. '</div>';
	}

	/**
	 * Extra CSS Class field
	 */
	public static function extra_css_class( $id, $field ) {
		return self::text( $id, $field );
	}

}
