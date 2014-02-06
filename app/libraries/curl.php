<?php
class Curl{
	public static function get_json($link){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($curl);
		return json_decode($data);
	}

	public static function get($link){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($curl);
		return $data;
	}
}