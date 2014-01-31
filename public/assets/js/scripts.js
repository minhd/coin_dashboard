angular.module('dash_app',['ngRoute'])

	.factory('api', function($http){
		return {
			lite: function(get){ return promise = $http.get('lite/'+get).then(function(response){return response.data}); },
			doge: function(get){ return promise = $http.get('doge/'+get).then(function(response){return response.data}); },
			market: function(){ return promise = $http.get('market/').then(function(response){return response.data}); },
		}
	})

	.config(function($routeProvider){
		$routeProvider
			.when('/',{
				controller:index,
				template:$('#index-template').html()
			})
	})

	.filter('getById', function() {
		return function(input, id) {
			var i=0, len=input.length;
			for (; i<len; i++) {
				if (input[i].id === id) {
					return input[i];
				}
			}
			return null;
		}
	})
;

function index($scope, api, $filter){
	$scope.lite = {wallet:{},workers:[],transactions:[],earning:'loading'}
	$scope.doge = {wallet:{},workers:[],transactions:[],earning:'loading'}
	$scope.market = [];
	$scope.earning = {btc:0,usd:0,aud:0}
	$scope.ready = false;
	api.lite('getbalance').then(function(data){ $scope.lite.wallet = data;});
	api.lite('getworkers').then(function(data){ $scope.lite.workers = data;});
	api.doge('getbalance').then(function(data){ $scope.doge.wallet = data;});
	api.market().then(function(data){ $scope.market = data;$scope.calculate_earnings();});

	$scope.$watch('[lite, doge, market]',function(newVal){
		if($scope.market.length > 0 && $scope.lite.wallet.balance!='undefined' && $scope.doge.wallet.balance!='undefined'){
			$scope.calculate_earnings();
		}
	}, true);

	$scope.calculate_earnings = function(){
		$scope.earning = {btc:0,usd:0,aud:0};

		//ltc_btc
		var rate = $filter('getById')($scope.market, 'ltc/btc');
		rate = parseFloat(rate.price);
		$scope.earning.btc += rate * $scope.lite.wallet.balance;

		//doge_btc
		var rate = $filter('getById')($scope.market, 'doge/btc');
		rate = parseFloat(rate.price);
		$scope.earning.btc += rate * $scope.doge.wallet.balance;

		//btc_usd
		var rate = $filter('getById')($scope.market, 'btc/usd');
		rate = parseFloat(rate.price);
		$scope.earning.usd += rate * $scope.earning.btc;

		//usd_aud
		var rate = $filter('getById')($scope.market, 'usd/aud');
		rate = parseFloat(rate.price);
		$scope.earning.aud += rate * $scope.earning.usd;
	}
}

