<?php

/**
 * File: /app/Controller/Component/AttachmentComponent.php
 * An Authorize.net component for CakePHP
 * Currently supports the following transaction types: Authorization and Capture, Credit
 * 
 * @link: 	https://github.com/LeGrande/authorize.net-component
 * @author: 	LeGrande Jolley - rroyale@gmail.com
 * @version: 	0.1
 * @license 	MIT
 */

class AuthorizeDotNetComponent extends Component {

	/**
	 * configuration variables for authorize.net - these are your secret credentials you get from authorize.net
	 * @var array
	 */
	var $config = array(
		'x_login'	=> 'XXXXXXX',
		'x_tran_key'	=> 'XXXXXXX'
	);

	/**
	 * authorize.net setup variables - you probably don't need to modify these
	 * @var array
	 */
	var $authnet_setup = array(
		'x_version'		=> '3.1',
		'x_delim_data'		=> 'TRUE',
		'x_delim_char'		=> '|',
		'x_relay_response'	=> 'FALSE'
	);

	/**
	 * this is the gateway transaction url at authorize.net that you post to
	 * @var string
	 */
	var $post_url = 'https://secure.authorize.net/gateway/transact.dll';
	//var $post_url = 'https://certification.authorize.net/gateway/transact.dll'; //FOR TESTING

	/**
	 * authorizes and captures a credit card transaction
	 * @param  array $data the data necessary to make the transaction
	 * @return array       the response from authorize.net
	 */
	function auth_capture($data) {

		$authnet_values = array(
			'x_type'		=> 'AUTH_CAPTURE',
			'x_method'		=> 'CC',
			'x_card_num'		=> $data['CreditCard']['number'],
			'x_exp_date'		=> $data['CreditCard']['expiration'],

			'x_amount'		=> $data['Transaction']['amount'],
			'x_description'		=> $data['Transaction']['description'],
			'x_invoice_num'		=> $data['Transaction']['invoice_number'],

			'x_first_name'		=> $data['Billing']['first_name'],
			'x_last_name'		=> $data['Billing']['last_name'],
			'x_address'		=> $data['Billing']['address'],
			'x_city'		=> $data['Billing']['city'],
			'x_state'		=> $data['Billing']['state'],
			'x_zip'			=> $data['Billing']['zip_code'],
			'x_email'		=> $data['Billing']['email'],
			'x_phone'		=> $data['Billing']['phone']
		);

		$response = $this->make_request($authnet_values);
		return $response;

	}

	/*
		this function will refund an entire transaction.  required to pass the full credit card number
	*/
	/**
	 * refund an entire transaction. requires to pass the full transaction number
	 * @param  array $data the data necessary to make the transaction
	 * @return array       the response from authorize.net
	 */
	function credit($data) {

		$authnet_values = array(
			'x_type'		=> 'CREDIT',
			'x_trans_id'		=> $data['trans_id'],
			'x_card_num'		=> $data['credit_card']
		);

		$response = $this->make_request($authnet_values);
		return $response;

	}

	function make_request($authnet_values) {

		$authnet_values['x_login'] 		=> $config['x_login'];
		$authnet_values['x_tran_key'] 		=> $config['x_tran_key'];
		$authnet_values['x_version'] 		=> $config['x_version'];
		$authnet_values['x_delim_data'] 	=> $config['x_delim_data'];
		$authnet_values['x_delim_char'] 	=> $config['x_delim_char'];
		$authnet_values['x_relay_response'] 	=> $config['x_relay_response'];

		$post_string = '';
		foreach($authnet_values as $key => $value) {
			$post_string .= '$key=' . urlencode( $value ) . '&';
		}
		$post_string = rtrim($post_string,'& ');

		$request = curl_init($post_url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$post_response = curl_exec($request); // execute curl post and store results in $post_response

		// This line takes the response and breaks it into an array using the specified delimiting character
		$authnet_response = explode($authnet_values['x_delim_char'],$post_response);
		return $authnet_response;

	}

}
