<?php

class HomeController extends BaseController {

	public function dash() {
		return View::make('dashboard');
	}

	public function litecoin($task){
		if($task==='getbalance'){
			$data['received'] = (float) file_get_contents('http://explorer.litecoin.net/chain/Litecoin/q/getreceivedbyaddress/LcZtnwQ2RSbvhYtgY1qyxGVJVBe9t4fp8v');
			$data['sent'] = (float) file_get_contents('http://explorer.litecoin.net/chain/Litecoin/q/getsentbyaddress/LcZtnwQ2RSbvhYtgY1qyxGVJVBe9t4fp8v');
			$data['balance'] = $data['received'] - $data['sent'];
			return Response::json($data); 
		}else if($task==='getworkers'){
			$data = json_decode(file_get_contents('https://www.ltcrabbit.com/index.php?page=api&action=getuserworkers&api_key=055be6c110fbd328ffc6b678159bfe600f90935e84cf5df12dc3e67cf88e18bc&id=20597'));
			return Response::json($data->{'getuserworkers'});
		}else if($task==='getstatus'){
			$status = json_decode(file_get_contents('https://www.ltcrabbit.com/index.php?page=api&action=getuserstatus&api_key=055be6c110fbd328ffc6b678159bfe600f90935e84cf5df12dc3e67cf88e18bc&id=20597'));
			$data['status'] = $status->{'getuserstatus'};
			$status = json_decode(file_get_contents('https://www.ltcrabbit.com/index.php?page=api&action=getpoolstatus&api_key=055be6c110fbd328ffc6b678159bfe600f90935e84cf5df12dc3e67cf88e18bc&id=20597'));
			$data['pool'] = $status->{'getpoolstatus'};
			return Response::json($data);
		}
	}

	public function dogecoin($task){
		if($task=='getbalance'){
			$data['balance'] = (float) file_get_contents('http://dogechain.info/chain/Dogecoin/q/addressbalance/DDpYPkv1bUMXxfp57Fb8N7Ds6BVmyzhtjn');
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

		return Response::json($data);
	}
}