function index($scope, api, $filter){
	$scope.lite = {wallet:{},workers:[],transactions:[],earning:'loading'}
	$scope.doge = {wallet:{},workers:[],transactions:[],earning:'loading'}
	$scope.market = [];
	$scope.earning = {btc:0,usd:0,aud:0}
	$scope.ready = false;
	$scope.workers = [];
	$scope.predicate = 'hashrate';
	$scope.reverse = true;
	
	$scope.$watch('[lite, doge, market]',function(newVal){
		if($scope.market.length > 0 && $scope.lite.wallet.balance!='undefined' && $scope.doge.wallet.balance!='undefined'){
			$scope.calculate_earnings();
		}
	}, true);

	$scope.refresh = function(){
		$scope.lite = {wallet:{},workers:[],transactions:[],earning:'loading', status:null};
		$scope.doge = {wallet:{},workers:[],transactions:[],earning:'loading', status:null};
		$scope.market = [];
		$scope.earning = {btc:0,usd:0,aud:0}
		$scope.ready = false;
		api.lite('getbalance').then(function(data){ $scope.lite.wallet = data;});
		api.doge('getbalance').then(function(data){ $scope.doge.wallet = data;});
		api.market().then(function(data){ $scope.market = data;$scope.calculate_earnings();});
		$scope.refresh_workers();
		$scope.refresh_status();
	}

	$scope.refresh_workers = function(){
		$scope.lite.workers = [];
		$scope.workers = [];
		api.lite('getworkers').then(function(data){ 
			$scope.lite.workers = data;
			$.each($scope.lite.workers, function(){
				this.mining = 'LTC';
			});
			$scope.workers = $scope.workers.concat($scope.lite.workers);
		});
		api.doge('getworkers').then(function(data){ 
			$scope.doge.workers = data;
			$.each($scope.doge.workers, function(){
				this.mining = 'DOGE';
				if(this.hashrate > 0){
					this.active = 1;
				}else this.active = 0;
			});
			$scope.workers = $scope.workers.concat($scope.doge.workers);
		});
	}

	$scope.refresh_status = function(){
		$scope.lite.status = null;
		api.lite('getstatus').then(function(data){ $scope.lite.status = data});
		api.doge('getstatus').then(function(data){ $scope.doge.status = data});
	}

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

	$scope.refresh();

}

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
		;
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


