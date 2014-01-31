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
</head>
<body>

	<div ng-app="dash_app">
		<div ng-view></div>
	</div>

	<div id="index-template" class="hide">
		<div class="container">
			<hr>
			<div class="row">
				<div class="col-md-6">
					<h3>Wallet Balances</h3>
					<ul class="list-group">
						<li class="list-group-item">
							<span class="label label-primary pull-right" ng-show="lite.wallet.balance">{{lite.wallet.balance}}</span> 
							<span class="badge" ng-hide="lite.wallet.balance">loading</span> 
							Litecoin
						</li>
						<li class="list-group-item">
							<span class="label label-primary pull-right" ng-show="doge.wallet.balance">{{doge.wallet.balance}}</span> 
							<span class="badge" ng-hide="doge.wallet.balance">loading</span> 
							Doge
						</li>
					</ul>
					<h3>Earnings</h3>
					<li class="list-group-item">
						<span class="label label-primary pull-right">{{earning.btc}}</span> 
						BTC
					</li>
					<li class="list-group-item">
						<span class="label label-primary pull-right">{{earning.usd}}</span> 
						USD
					</li>
					<li class="list-group-item">
						<span class="label label-primary pull-right">{{earning.aud}}</span> 
						AUD
					</li>
				</div>
				<div class="col-md-6">
					<h3>Market Value</h3>
					<dl class="dl-horizontal">
						<span ng-repeat="m in market">
							<dt>{{m.id}}</dt>
							<dd><input type="text" ng-model="m.price" ng-change="calculate_earning"></dd>
						</span>
					</dl>
				</div>
			</div>

		</div>
		
	</div>

</body>
</html>
