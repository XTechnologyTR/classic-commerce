<?php
/**
 * REST API Customers controller
 *
 * Handles requests to the /customers endpoint.
 *
 * @package ClassicCommerce/API
 * @since   WC-2.6.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * REST API Customers controller class.
 *
 * @package ClassicCommerce/API
 * @extends WC_REST_Customers_V2_Controller
 */
class WC_REST_Customers_Controller extends WC_REST_Customers_V2_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';

	/**
	 * Get formatted item data.
	 *
	 * @since  WC-3.0.0
	 * @param  WC_Data $object WC_Data instance.
	 * @return array
	 */
	protected function get_formatted_item_data( $object ) {
		$data        = $object->get_data();
		$format_date = array( 'date_created', 'date_modified' );

		// Format date values.
		foreach ( $format_date as $key ) {
			$datetime              = $data[ $key ];
			$data[ $key ]          = wc_rest_prepare_date_response( $datetime, false );
			$data[ $key . '_gmt' ] = wc_rest_prepare_date_response( $datetime );
		}

		return array(
			'id'                 => $object->get_id(),
			'date_created'       => $data['date_created'],
			'date_created_gmt'   => $data['date_created_gmt'],
			'date_modified'      => $data['date_modified'],
			'date_modified_gmt'  => $data['date_modified_gmt'],
			'email'              => $data['email'],
			'first_name'         => $data['first_name'],
			'last_name'          => $data['last_name'],
			'role'               => $data['role'],
			'username'           => $data['username'],
			'billing'            => $data['billing'],
			'shipping'           => $data['shipping'],
			'is_paying_customer' => $data['is_paying_customer'],
			'avatar_url'         => $object->get_avatar_url(),
			'meta_data'          => $data['meta_data'],
		);
	}

	/**
	 * Get the Customer's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'customer',
			'type'       => 'object',
			'properties' => array(
				'id'                 => array(
					'description' => __( 'Unique identifier for the resource.', 'classic-commerce' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'       => array(
					'description' => __( "The date the customer was created, in the site's timezone.", 'classic-commerce' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created_gmt'   => array(
					'description' => __( 'The date the customer was created, as GMT.', 'classic-commerce' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified'      => array(
					'description' => __( "The date the customer was last modified, in the site's timezone.", 'classic-commerce' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt'  => array(
					'description' => __( 'The date the customer was last modified, as GMT.', 'classic-commerce' ),
					'type'        => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'email'              => array(
					'description' => __( 'The email address for the customer.', 'classic-commerce' ),
					'type'        => 'string',
					'format'      => 'email',
					'context'     => array( 'view', 'edit' ),
				),
				'first_name'         => array(
					'description' => __( 'Customer first name.', 'classic-commerce' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'last_name'          => array(
					'description' => __( 'Customer last name.', 'classic-commerce' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'role'               => array(
					'description' => __( 'Customer role.', 'classic-commerce' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'username'           => array(
					'description' => __( 'Customer login name.', 'classic-commerce' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_user',
					),
				),
				'password'           => array(
					'description' => __( 'Customer password.', 'classic-commerce' ),
					'type'        => 'string',
					'context'     => array( 'edit' ),
				),
				'billing'            => array(
					'description' => __( 'List of billing address data.', 'classic-commerce' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'first_name' => array(
							'description' => __( 'First name.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'last_name'  => array(
							'description' => __( 'Last name.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'company'    => array(
							'description' => __( 'Company name.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'address_1'  => array(
							'description' => __( 'Address line 1', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'address_2'  => array(
							'description' => __( 'Address line 2', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'city'       => array(
							'description' => __( 'City name.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'state'      => array(
							'description' => __( 'ISO code or name of the state, province or district.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'postcode'   => array(
							'description' => __( 'Postal code.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'country'    => array(
							'description' => __( 'ISO code of the country.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'email'      => array(
							'description' => __( 'Email address.', 'classic-commerce' ),
							'type'        => 'string',
							'format'      => 'email',
							'context'     => array( 'view', 'edit' ),
						),
						'phone'      => array(
							'description' => __( 'Phone number.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
				'shipping'           => array(
					'description' => __( 'List of shipping address data.', 'classic-commerce' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'first_name' => array(
							'description' => __( 'First name.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'last_name'  => array(
							'description' => __( 'Last name.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'company'    => array(
							'description' => __( 'Company name.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'address_1'  => array(
							'description' => __( 'Address line 1', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'address_2'  => array(
							'description' => __( 'Address line 2', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'city'       => array(
							'description' => __( 'City name.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'state'      => array(
							'description' => __( 'ISO code or name of the state, province or district.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'postcode'   => array(
							'description' => __( 'Postal code.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
						'country'    => array(
							'description' => __( 'ISO code of the country.', 'classic-commerce' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
						),
					),
				),
				'is_paying_customer' => array(
					'description' => __( 'Is the customer a paying customer?', 'classic-commerce' ),
					'type'        => 'bool',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'avatar_url'         => array(
					'description' => __( 'Avatar URL.', 'classic-commerce' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'meta_data'          => array(
					'description' => __( 'Meta data.', 'classic-commerce' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'description' => __( 'Meta ID.', 'classic-commerce' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'key'   => array(
								'description' => __( 'Meta key.', 'classic-commerce' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'value' => array(
								'description' => __( 'Meta value.', 'classic-commerce' ),
								'type'        => 'mixed',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
