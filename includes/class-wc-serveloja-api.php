<?php
/**
 * WooCommerce Serveloja API class.
 *
 * API de comunicação com base Serveloja.
 *
 * @class   WC_Serveloja_API
 * @version 1.0.0
 * @author  Eduardo Feitosa
 */

if (!defined('ABSPATH')) {
	exit;
}

class WCSVL_Serveloja_API {

	private function WCSVL_servidor() {
		// return "https://sistemaserveloja.com.br/gtw/webapi/";
		return "http://desenvolvimento.redeserveloja.com/Novo/WebApi/";
	}

	public function WCSVL_metodos_get($url, $param, $authorization, $applicationId) {
		$args = array(
			'blocking' => true,
			'timeout' => '5000',
			'headers' => array(
				'Authorization' => 'Basic ' . $authorization,
				'ApplicationId' => $applicationId,
				'Content-Type' => 'application/json',
				'User-Agent' => $_SERVER['HTTP_USER_AGENT']
			)
		);
		$response = wp_remote_get(WC_Serveloja_API::WCSVL_servidor() . $url, $args);
		if (is_wp_error($response)) {
			return $response->get_error_message();
		} else {
			return $response;
		}
	}

	public function WCSVL_metodos_post($url, $param, $authorization, $applicationId) {
		$args = array(
			'blocking' => true,
			'timeout' => '5000',
			'headers' => array(
				'Authorization' => 'Basic ' . $authorization,
				'ApplicationId' => $applicationId,
				'Content-Type' => 'application/json',
				'User-Agent' => $_SERVER['HTTP_USER_AGENT']
			),
			'body' => json_encode($param)
		);
		$response = wp_remote_post(WC_Serveloja_API::WCSVL_servidor() . $url, $args);
		if (is_wp_error($response)) {
			return $response->get_error_message();
		} else {
			return $response;
		}
	}
    
} ?>