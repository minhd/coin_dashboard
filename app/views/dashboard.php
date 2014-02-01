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
								<span class="label label-success pull-right label-xl" ng-show="lite.wallet.balance">{{lite.wallet.balance | number:1}}</span> 
								<span class="badge" ng-hide="lite.wallet.balance">loading</span> 
								Litecoin
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="doge.wallet.balance">{{doge.wallet.balance | number:0}}</span> 
								<span class="badge" ng-hide="doge.wallet.balance">loading</span> 
								Doge
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
						<div class="panel-heading">Workers Status</div>
						<table class="table table-striped">
							<thead>
								<th>Worker Name</th><th>Status</th><th>Hash Rate</th>
							</thead>
							<tbody>
								<tr ng-repeat="w in lite.workers" ng-class="{'1':'success', '0':'danger'}[w.active]">
									<td>{{w.username}}</td>
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

					<div class="panel panel-default">
						<div class="panel-heading">ltcrabbit Status</div>
						<ul class="list-group">
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.btc">{{lite.status.status.hashrate | number:0}} kh/s</span> 
								Hashrate
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.btc">{{lite.status.status.balance}}</span>
								Balance
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.btc">{{lite.status.status.sharerate | number:0}} share/s</span>
								Share Rate
							</li>
						</ul>
						<div class="panel-heading">ltcrabbit Pool Status</div>
						<ul class="list-group">
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.btc">{{lite.status.pool.hashrate | number:0}} h/s</span> 
								Hashrate
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.btc">{{lite.status.pool.workers | number}}</span>
								Workers
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.btc">{{lite.status.pool.networkdiff | number}}</span>
								Network Difficulty
							</li>
							<li class="list-group-item">
								<span class="label label-success pull-right label-xl" ng-show="earning.btc">{{lite.status.pool.efficiency}}</span>
								Efficiency
							</li>
						</ul>
					</div>
				</div>
			</div>
	
			<hr>


		</div>
		
	</div>

</body>
</html>
