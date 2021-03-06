<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Coin Dashboard</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">


	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
	<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script src="assets/lib/angular/angular.min.js"></script>
	<script src="assets/lib/angular/angular-route.min.js"></script>
	<script src="assets/js/scripts.js"></script>
	<style>
		.label-xl{font-size:120%;}
	</style>
</head>
<body>

	<div ng-app="dash_app">
		<div ng-view></div>
	</div>

	<div id="index-template" class="hide">
		<div class="container">
			<hr>
			<button class='btn btn-primary' ng-click="refresh()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>

			<hr>
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">Wallet Balances</div>
						
						<ul class="list-group">
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="lite.wallet.balance">{{lite.wallet.balance | number:4}}</span> 
								<span class="badge" ng-hide="lite.wallet.balance">loading</span> 
								Litecoin
								<br/>
								<a href="http://explorer.litecoin.net/address/LcZtnwQ2RSbvhYtgY1qyxGVJVBe9t4fp8v">LcZtnwQ2RSbvhYtgY1qyxGVJVBe9t4fp8v</a>
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="doge.wallet.balance">{{doge.wallet.balance | number}}</span> 
								<span class="badge" ng-hide="doge.wallet.balance">loading</span> 
								Doge
								<br/>
								<a href="http://dogechain.info/address/DDpYPkv1bUMXxfp57Fb8N7Ds6BVmyzhtjn">DDpYPkv1bUMXxfp57Fb8N7Ds6BVmyzhtjn</a>
							</li>
						</ul>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">Earnings</div>
						<ul class="list-group">
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.btc">{{earning.btc | currency:"BTC "}}</span> 
								<span class="badge" ng-hide="earning.btc">loading</span> 
								BTC
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.usd">{{earning.usd | currency}}</span> 
								<span class="badge" ng-hide="earning.usd">loading</span> 
								USD
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.aud">{{earning.aud | currency:"AUD$ "}}</span> 
								<span class="badge" ng-hide="earning.aud">loading</span> 
								AUD
							</li>
						</ul>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							Workers Status
							<button class="btn btn-xs btn-primary pull-right" ng-click="refresh_workers()"><i class="glyphicon glyphicon-refresh"></i></button>
						</div>
						<table class="table table-striped">
							<thead>
								<th ng-click="predicate = 'username';reverse=!reverse">Worker Name</th>
								<th ng-click="predicate = 'mining';reverse=!reverse">Mining</th>
								<th ng-click="predicate = 'status';reverse=!reverse">Status</th>
								<th ng-click="predicate = 'hashrate';reverse=!reverse">Hash Rate</th>
							</thead>
							<tbody>
								<tr ng-repeat="w in workers | orderBy:predicate:reverse" class="{{w.style}}">
									<td>{{w.username}}</td>
									<td>{{w.mining}}</td>
									<td>
										<i class="glyphicon glyphicon-ok" ng-show="w.active=='1'"></i>
										<i class="glyphicon glyphicon-remove" ng-show="w.active=='0'"></i>
									</td>

									<td>{{w.hashrate}}</td>
								</tr>
							</tbody>
						</table>
					</div>

				</div>
				<div class="col-md-6">

					<div class="panel panel-default">
						<div class="panel-heading">Status</div>
						<table class="table table-striped">
							<thead>
								<th></th>
								<th>LTC</th>
								<th>DOGE</th>
							</thead>
							<tbody>
								<tr>
									<th>Hash Rate</th>
									<td>{{lite.status.status.hashrate | number:0}} Kh/s</td>
									<td>{{doge.status.status.hashrate | number:0}} Kh/s</td>
								</tr>
								<tr>
									<th>Balance in Pool</th>
									<td>{{lite.status.status.balance | number:4}}</td>
									<td>{{doge.status.balance.confirmed | number:4}} <span class="label label-default">{{doge.status.balance.unconfirmed | number:4}}</span></td>
								</tr>
								<tr>
									<th>Share Rate</th>
									<td>{{lite.status.status.sharerate | number}} share/s</td>
									<td>{{doge.status.status.sharerate | number}} share/s</td>
								</tr>
								<tr>
									<th>Pool Difficulty</th>
									<td>{{lite.status.pool.networkdiff | number}}</td>
									<td>{{doge.status.pool.networkdiff | number}}</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">Market Value</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form">
								<div class="form-group" ng-repeat="m in market">
									<label for="" class="col-sm-2">{{m.id}}</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" ng-model="m.price" ng-change="calculate_earning">
									</div>
								</div>
							</form>
						</div>
					</div>

					
				</div>
			</div>
	
			<hr>


		</div>
		
	</div>

</body>
</html>
