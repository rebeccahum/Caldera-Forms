<?php

/**
 * Access to field definitions
 *
 * @package Caldera_Forms
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
class Caldera_Forms_Fields {

	/**
	 * Get all field definitions
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public static function get_all() {

		/**
		 * Register or remove field types
		 *
		 * @since unknown
		 *
		 * @param array $field_types Field types
		 */
		$field_types = apply_filters( 'caldera_forms_get_field_types', array() );


		if ( ! empty( $field_types ) ) {
			foreach ( $field_types as $fieldType => $fieldConfig ) {
				// check for a viewer
				if ( isset( $fieldConfig[ 'viewer' ] ) ) {
					add_filter( 'caldera_forms_view_field_' . $fieldType, $fieldConfig[ 'viewer' ], 10, 3 );
				}
			}
		}

		return $field_types;

	}

	/**
	 * Get definition of one field
	 *
	 * @since 1.5.0
	 *
	 * @param string $type Field type
	 *
	 * @return array
	 */
	public static function definition( $type ){
		$fields = self::get_all();
		if( in_array( $type, $fields ) ){
			return $fields[ 'type' ];
		}

		return array();

	}

	/**
	 * Get internal field types without filter
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public static function internal_types() {
		$internal_fields = array(
			//basic
			'text'             => array(
				"field"       => __( 'Single Line Text', 'caldera-forms' ),
				"description" => __( 'Single Line Text', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/generic-input.php",
				"category"    => __( 'Basic', 'caldera-forms' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/text/config.php",
					"preview"  => CFCORE_PATH . "fields/text/preview.php"
				),

			),
			'hidden'           => array(
				"field"       => __( 'Hidden', 'caldera-forms' ),
				"description" => __( 'Hidden', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/hidden/field.php",
				"category"    => __( 'Basic', 'caldera-forms' ),
				"static"      => true,
				"setup"       => array(
					"preview"       => CFCORE_PATH . "fields/hidden/preview.php",
					"template"      => CFCORE_PATH . "fields/hidden/setup.php",
					"not_supported" => array(
						'hide_label',
						'caption',
						'required',
					)
				)
			),
			'email'            => array(
				"field"       => __( 'Email Address', 'caldera-forms' ),
				"description" => __( 'Email Address', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/generic-input.php",
				"category"    => __( 'Basic', 'caldera-forms' ),
				"setup"       => array(
					"preview"  => CFCORE_PATH . "fields/email/preview.php",
					"template" => CFCORE_PATH . "fields/email/config.php"
				)
			),
			'button'           => array(
				"field"       => __( 'Button', 'caldera-forms' ),
				"description" => __( 'Button, Submit and Reset types', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/button/field.php",
				"category"    => __( 'Basic', 'caldera-forms' ),
				"capture"     => false,
				"setup"       => array(
					"template"      => CFCORE_PATH . "fields/button/config_template.php",
					"preview"       => CFCORE_PATH . "fields/button/preview.php",
					"default"       => array(
						'class' => 'btn btn-default',
						'type'  => 'submit'
					),
					"not_supported" => array(
						'hide_label',
						'caption',
						'required',
						'entry_list'
					)
				)
			),
			'phone_better'     => array(
				"field"       => __( 'Phone Number (Better)', 'caldera-forms' ),
				"description" => __( 'Phone number with advanced options and international formatting', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/phone_better/field.php",
				"category"    => __( 'Basic', 'caldera-forms' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/phone_better/config.php",
					"preview"  => CFCORE_PATH . "fields/phone_better/preview.php",
					"default"  => array(
						'default' => '',

					)
				),
				"scripts"     => array(
					CFCORE_URL . 'fields/phone_better/assets/js/intlTelInput.min.js',
				),
				"styles"      => array(
					CFCORE_URL . 'fields/phone_better/assets/css/intlTelInput.css'
				),
			),
			'phone'            => array(
				"field"       => __( 'Phone Number (Basic)', 'caldera-forms' ),
				"description" => __( 'Phone number with masking', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/generic-input.php",
				"category"    => __( 'Basic', 'caldera-forms' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/phone/config.php",
					"preview"  => CFCORE_PATH . "fields/phone/preview.php",
					"default"  => array(
						'default' => '',
						'type'    => 'local',
						'custom'  => '(999)999-9999'
					)
				)
			),
			'paragraph'        => array(
				"field"       => __( 'Paragraph Textarea', 'caldera-forms' ),
				"description" => __( 'Paragraph Textarea', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/paragraph/field.php",
				"category"    => __( 'Basic', 'caldera-forms' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/paragraph/config_template.php",
					"preview"  => CFCORE_PATH . "fields/paragraph/preview.php",
					"default"  => array(
						'rows' => '4'
					),
				)
			),
			'wysiwyg'          => array(
				"field"       => __( 'Rich Editor', 'caldera-forms' ),
				"description" => __( 'TinyMCE WYSIWYG editor', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/wysiwyg/field.php",
				"category"    => __( 'Basic', 'caldera-forms' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/wysiwyg/config_template.php",
					"preview"  => CFCORE_PATH . "fields/wysiwyg/preview.php",
				),
				"scripts"     => array(
					CFCORE_URL . 'fields/wysiwyg/wysiwyg.js'
				),
				"styles"      => array(
					CFCORE_URL . "fields/wysiwyg/wysiwyg.min.css",
				),
			),

			//special
			'calculation'      => array(
				"field"       => __( 'Calculation', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/calculation/field.php",
				"handler"     => array( Caldera_Forms::get_instance(), "run_calculation" ),
				"category"    => __( 'Special', 'caldera-forms' ),
				"description" => __( 'Calculate values', 'caldera-forms' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/calculation/config.php",
					"preview"  => CFCORE_PATH . "fields/calculation/preview.php",
					"default"  => array(
						'element' => 'h3',
						'classes' => 'total-line',
						'before'  => __( 'Total', 'caldera-forms' ) . ':',
						'after'   => ''
					),

				),
			),
			'range_slider'     => array(
				"field"       => __( 'Range Slider', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/range_slider/field.php",
				"category"    => __( 'Special', 'caldera-forms' ),
				"description" => __( 'Range Slider input field', 'caldera-forms' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/range_slider/config.php",
					"preview"  => CFCORE_PATH . "fields/range_slider/preview.php",
					"default"  => array(
						'default'      => 1,
						'step'         => 1,
						'min'          => 0,
						'max'          => 100,
						'showval'      => 1,
						'suffix'       => '',
						'prefix'       => '',
						'color'        => '#00ff00',
						'handle'       => '#ffffff',
						'handleborder' => '#cccccc',
						'trackcolor'   => '#e6e6e6'
					),
				),
				"styles"      => array(
					CFCORE_URL . "fields/range_slider/rangeslider.css",
				),
			),
			'star_rating'      => array(
				"field"       => __( 'Star Rating', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/star-rate/field.php",
				"category"    => __( 'Special', 'caldera-forms' ),
				"description" => __( 'Star rating input for feedback', 'caldera-forms' ),
				"viewer"      => array( Caldera_Forms::get_instance(), 'star_rating_viewer' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/star-rate/config.php",
					"preview"  => CFCORE_PATH . "fields/star-rate/preview.php",
					"default"  => array(
						'number'      => 5,
						'space'       => 3,
						'size'        => 13,
						'color'       => '#FFAA00',
						'track_color' => '#AFAFAF',
						'type'        => 'star',
					),
				)
			),

			//file
			'file'             => array(
				"field"       => __( 'File', 'caldera-forms' ),
				"description" => __( 'File Uploader', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/file/field.php",
				"viewer"      => array( Caldera_Forms::get_instance(), 'handle_file_view' ),
				"category"    => __( 'File', 'caldera-forms' ),
				"setup"       => array(
					"preview"  => CFCORE_PATH . "fields/file/preview.php",
					"template" => CFCORE_PATH . "fields/file/config_template.php"
				)
			),
			'advanced_file'    => array(
				"field"       => __( 'Advanced File Uploader', 'caldera-forms' ),
				"description" => __( 'Inline, multi file uploader', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/advanced_file/field.php",
				"viewer"      => array( Caldera_Forms::get_instance(), 'handle_file_view' ),
				"category"    => __( 'File', 'caldera-forms' ),
				"setup"       => array(
					"preview"  => CFCORE_PATH . "fields/advanced_file/preview.php",
					"template" => CFCORE_PATH . "fields/advanced_file/config_template.php"
				),
				"scripts"     => array(
					CFCORE_URL . 'fields/advanced_file/uploader.js'
				),

			),

			//content
			'html'             => array(
				"field"       => __( 'HTML', 'caldera-forms' ),
				"description" => __( 'Add text/html content', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/html/field.php",
				"category"    => __( 'Content', 'caldera-forms' ),
				"icon"        => CFCORE_URL . "fields/html/icon.png",
				"capture"     => false,
				"setup"       => array(
					"preview"       => CFCORE_PATH . "fields/html/preview.php",
					"template"      => CFCORE_PATH . "fields/html/config_template.php",
					"not_supported" => array(
						'hide_label',
						'caption',
						'required',
						'entry_list'
					)
				)
			),
			'section_break'    => array(
				"field"       => __( 'Section Break', 'caldera-forms' ),
				"description" => __( 'An HR tag to separate sections of your form.', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/section-break/field.php",
				"category"    => __( 'Content', 'caldera-forms' ),
				"static"      => true,
				"setup"       => array(
					"template"      => CFCORE_PATH . "fields/section-break/setup.php",
					"not_supported" => array(
						'hide_label',
						'caption',
						'required',
					)
				)
			),

			//select
			'dropdown'         => array(
				"field"       => __( 'Dropdown Select', 'caldera-forms' ),
				"description" => __( 'Dropdown Select', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/dropdown/field.php",
				"category"    => __( 'Select', 'caldera-forms' ),
				"options"     => "single",
				"static"      => true,
				"viewer"      => array( Caldera_Forms::get_instance(), 'filter_options_calculator' ),
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/dropdown/config_template.php",
					"preview"  => CFCORE_PATH . "fields/dropdown/preview.php",
					"default"  => array(),
				)
			),
			'checkbox'         => array(
				"field"       => __( 'Checkbox', 'caldera-forms' ),
				"description" => __( 'Checkbox', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/checkbox/field.php",
				"category"    => __( 'Select', 'caldera-forms' ),
				"options"     => "multiple",
				"static"      => true,
				"viewer"      => array( Caldera_Forms::get_instance(), 'filter_options_calculator' ),
				"setup"       => array(
					"preview"  => CFCORE_PATH . "fields/checkbox/preview.php",
					"template" => CFCORE_PATH . "fields/checkbox/config_template.php",

				),
			),
			'radio'            => array(
				"field"       => __( 'Radio', 'caldera-forms' ),
				"description" => __( 'Radio', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/radio/field.php",
				"category"    => __( 'Select', 'caldera-forms' ),
				"options"     => true,
				"static"      => true,
				"viewer"      => array( Caldera_Forms::get_instance(), 'filter_options_calculator' ),
				"setup"       => array(
					"preview"  => CFCORE_PATH . "fields/radio/preview.php",
					"template" => CFCORE_PATH . "fields/radio/config_template.php",
				)
			),
			'filtered_select2' => array(
				"field"       => __( 'Autocomplete', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/select2/field/field.php",
				"category"    => __( 'Select', 'caldera-forms' ),
				"description" => 'Select2 dropdown',
				"options"     => "multiple",
				"static"      => true,
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/select2/field/config.php",
					"preview"  => CFCORE_PATH . "fields/select2/field/preview.php",
				),
				"scripts"     => array(
					CFCORE_URL . "fields/select2/js/select2.min.js",
				),
				"styles"      => array(
					CFCORE_URL . "fields/select2/css/select2.css",
				)
			),
			'date_picker'      => array(
				"field"       => __( 'Date Picker', 'caldera-forms' ),
				"description" => __( 'Date Picker', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/date_picker/datepicker.php",
				"category"    => __( 'Select', 'caldera-forms' ),
				"setup"       => array(
					"preview"  => CFCORE_PATH . "fields/date_picker/preview.php",
					"template" => CFCORE_PATH . "fields/date_picker/setup.php",
					"default"  => array(
						'format' => 'yyyy-mm-dd'
					),
				)
			),
			'toggle_switch'    => array(
				"field"       => __( 'Toggle Switch', 'caldera-forms' ),
				"description" => __( 'Toggle Switch', 'caldera-forms' ),
				"category"    => __( 'Select', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/toggle_switch/field.php",
				"viewer"      => array( Caldera_Forms::get_instance(), 'filter_options_calculator' ),
				"options"     => "single",
				"static"      => true,
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/toggle_switch/config_template.php",
					"preview"  => CFCORE_PATH . "fields/toggle_switch/preview.php",
				),
			),
			'color_picker'     => array(
				"field"       => __( 'Color Picker', 'caldera-forms' ),
				"description" => __( 'Color Picker', 'caldera-forms' ),
				"category"    => __( 'Select', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/color_picker/field.php",
				"setup"       => array(
					"preview"  => CFCORE_PATH . "fields/color_picker/preview.php",
					"template" => CFCORE_PATH . "fields/color_picker/setup.php",
					"default"  => array(
						'default' => '#FFFFFF'
					),

				),
			),
			'states'           => array(
				"field"       => __( 'State/ Province Select', 'caldera-forms' ),
				"description" => __( 'Dropdown select for US states and Canadian provinces.', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/states/field.php",
				"category"    => __( 'Select', 'caldera-forms' ),
				"placeholder" => false,
				"setup"       => array(
					"template" => CFCORE_PATH . "fields/states/config_template.php",
					"preview"  => CFCORE_PATH . "fields/states/preview.php",
					"default"  => array(),
				)
			),


			//discontinued
			'recaptcha'        => array(
				"field"       => __( 'reCAPTCHA', 'caldera-forms' ),
				"description" => __( 'reCAPTCHA anti-spam field', 'caldera-forms' ),
				"file"        => CFCORE_PATH . "fields/recaptcha/field.php",
				"category"    => __( 'Discontinued', 'caldera-forms' ),
				"handler"     => array( Caldera_Forms::get_instance(), 'captcha_check' ),
				"capture"     => false,
				"setup"       => array(
					"template"      => CFCORE_PATH . "fields/recaptcha/config.php",
					"preview"       => CFCORE_PATH . "fields/recaptcha/preview.php",
					"not_supported" => array(
						'caption',
						'required'
					),
				)
			),

		);

		return $internal_fields;
	}

}