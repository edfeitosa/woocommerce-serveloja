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

class WC_Serveloja_API {

	private function servidor() {
		return "http://desenvolvimento.redeserveloja.com/Novo/WebApi/";
	}

	public function metodos_get($url, $param, $authorization, $applicationId) {
		$con = curl_init();
		curl_setopt($con, CURLOPT_URL, WC_Serveloja_API::servidor() . $url . "?" . $param);
		curl_setopt($con, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($con, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($con, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: ' . $authorization . '',
				'ApplicationId: ' . $applicationId . ''
			)
		);
		$data = curl_exec($con);
		curl_close($con);
		return $data;
	}

	public function metodos_post($url, $param, $authorization, $applicationId) {
		$data_string = json_encode($param);
		$con = curl_init();
		curl_setopt($con, CURLOPT_URL, WC_Serveloja_API::servidor() . $url);
		curl_setopt($con, CURLOPT_POST, 1);
		curl_setopt($con, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($con, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($con, CURLOPT_HTTPHEADER, array(
				'Authorization: Basic ' . $authorization,
				'ApplicationId: ' . $applicationId,
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string)
			)
		);
		$data = curl_exec($con);
		curl_close($con);
		return $data;
	}
    
} ?>