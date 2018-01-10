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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Serveloja_API {

	private function servidor() {
		return "http://desenvolvimento.redeserveloja.com/Novo/webapi/";
	}

	public function metodos_acesso_api($url, $method, $param) {
		if ($method == "get") {
		/* em caso do método GET, os parâmetros serão adicionados na URL, como padrão */
		$con = curl_init($this->servidor() . $url . "?" . $param);
		curl_setopt($con, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($con, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json'
			)
		);
		} else if ($method == "post") {
		/* em caso de POST, os parametros serão adicionados a um array, ex: array("id" => "$id", "symbol" => "$symbol"); */
		$con = curl_init($this->servidor() . $url . "?" . $param);
		curl_setopt($con, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($con, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($con, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen(json_encode($param))
			)
		);
		} 
		curl_setopt($con, CURLOPT_TIMEOUT, 5);
		curl_setopt($con, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($con);
		curl_close($con);
		return $data;
	}
    
} ?>