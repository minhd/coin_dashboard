<?php

class HomeController extends BaseController {

	public function dash() {
		if(Config::get('coins.ltc.address')===''){
			echo 'The application needs configuration. Environment: '.App::environment();
		}else{
			return View::make('dashboard');
		}
	}

	public function litecoin($task){
		if($task==='getbalance'){
			$data['received'] = (float) Curl::get('http://explorer.litecoin.net/chain/Litecoin/q/getreceivedbyaddress/'.Config::get('coins.ltc.address'));
			$data['sent'] = (float) Curl::get('http://explorer.litecoin.net/chain/Litecoin/q/getsentbyaddress/'.Config::get('coins.ltc.address'));
			$data['balance'] = $data['received'] - $data['sent'];
			return Response::json($data); 
		}else if($task==='getworkers'){
			$data = Curl::get_json(Config::get('coins.ltc.api_address').'&action=getuserworkers&api_key='.Config::get('coins.ltc.api_key').'&id='.Config::get('coins.ltc.api_id'));
			return Response::json($data->{'getuserworkers'});
		}else if($task==='getstatus'){
			$status = Curl::get_json(Config::get('coins.ltc.api_address').'&action=getuserstatus&api_key='.Config::get('coins.ltc.api_key').'&id='.Config::get('coins.ltc.api_id'));
			$data['status'] = $status->{'getuserstatus'};
			$status = Curl::get_json(Config::get('coins.ltc.api_address').'&action=getpoolstatus&api_key='.Config::get('coins.ltc.api_key').'&id='.Config::get('coins.ltc.api_id'));
			$data['pool'] = $status->{'getpoolstatus'};
			return Response::json($data);
		}
	}

	public function dogecoin($task){
		if($task=='getbalance'){
			$data['balance'] = (float) Curl::get('http://dogechain.info/chain/Dogecoin/q/addressbalance/'.Config::get('coins.doge.address'));
			return Response::json($data);
		}else if($task==='getworkers'){
			$data = Curl::get_json(Config::get('coins.doge.api_address').'&action=getuserworkers&api_key='.Config::get('coins.doge.api_key').'&id='.Config::get('coins.doge.api_id'));
			return Response::json($data->{'getuserworkers'}->{'data'});
		}else if($task=='getstatus'){
			$status = Curl::get_json(Config::get('coins.doge.api_address').'&action=getuserstatus&api_key='.Config::get('coins.doge.api_key').'&id='.Config::get('coins.doge.api_id'));
			$data['status'] = $status->{'getuserstatus'}->{'data'};
			$balance = Curl::get_json(Config::get('coins.doge.api_address').'&action=getuserbalance&api_key='.Config::get('coins.doge.api_key').'&id='.Config::get('coins.doge.api_id'));
			$data['balance']['confirmed'] = $balance->{'getuserbalance'}->{'data'}->{'confirmed'};
			$data['balance']['unconfirmed'] = $balance->{'getuserbalance'}->{'data'}->{'unconfirmed'};
			$status = Curl::get_json(Config::get('coins.doge.api_address').'&action=getpoolstatus&api_key='.Config::get('coins.doge.api_key').'&id='.Config::get('coins.doge.api_id'));
			$data['pool'] = $status->{'getpoolstatus'}->{'data'};
			return Response::json($data);
		}
	}

	public function market_rates(){
		// $data['ltc_usd'] = file_get_contents('http://dogechain.info/chain/Dogecoin/q/addressbalance/DDpYPkv1bUMXxfp57Fb8N7Ds6BVmyzhtjn');
		$post = array("pairs" => "ltc_usd,ltc_btc,doge_btc,btc_usd");
		// fetch data
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "http://www.cryptocoincharts.info/v2/api/tradingPairs");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		$rawData = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($rawData);

		//usd_aud
		$yqdata = json_decode(file_get_contents('http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22USDAUD%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='), true);
		array_push($data, array(
			'id'=>'usd/aud',
			'price'=>$yqdata['query']['results']['rate']['Rate']
		));

		//aud_usd
		$yqdata = json_decode(file_get_contents('http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22AUDUSD%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='), true);
		array_push($data, array(
			'id'=>'aud/usd',
			'price'=>$yqdata['query']['results']['rate']['Rate']
		));

		return Response::json($data);
	}
}