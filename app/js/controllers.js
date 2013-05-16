'use strict';

/* Controllers */

/* products list */
function ProductListCtrl ($scope, $http) {
	$http.get('products/products.json').success(function(data){
		$scope.products = data;
	})
}
/* each product detail */
app.controller("ProductDetailCtrl", function($scope, $routeParams, $http, JsonService) {
	$scope.selectionPrice = 0;
	$scope.totalPrice = 0;
	$http.get('products/' + $routeParams.productId + '.json').success(function(data){
		$scope.product = data;
		$scope.mainImageUrl = data.images[0];
		$scope.selectionPrice = data.price;
		$scope.totalPrice = $scope.selectionPrice;

	});

	$scope.choiceValues = {};
	$scope.addonValues = {};

	$scope.addonPrice = 0;
	$scope.addonString = "";	
	$scope.addons = false;

	$scope.setImage = function(imageUrl) {
		$scope.mainImageUrl = imageUrl;
	};

	$scope.switchImage = function(id, opt, sel) {
		sel = sel.replace(/&|\s+/g, '');
		sel = sel.toLowerCase();
		var imageUrl = "img/products/switches/" + id + "-"  + opt + "-" + sel + ".jpg";
		console.log(imageUrl);
		$scope.mainImageUrl = imageUrl;
	}

	JsonService.query(function(response){
    	$scope.options = response;	
		
	});
	
	$scope.updatePrice = function(val, pr, title, sel){
		console.log(val, title);
		$scope.totalPrice = $scope.selectionPrice
	
		var price = parseInt(pr);

		//todo figure out why this is showing the opposite value
		if (val == "false") {
			$scope.addonPrice += price;
			$scope.addonValues[title] = $scope.selected;
			//Todo keep track of what is checked and their values separately???
		} else {
			$scope.addonPrice -= price;
			$scope.addonValues[title] = null;
			//$scope.selected = null;
		}
		$scope.totalPrice += $scope.addonPrice; 
		if ($scope.totalPrice != $scope.selectionPrice) {
			$scope.addonString = "+ $" + $scope.addonPrice +  " = $" + $scope.totalPrice;
		} else {
			$scope.addonString = "";
		}
	}

	
	
});

function ProductSelectionCtrl ($scope){
	$scope.select = function(item){
			$scope.selected = item;
		}
		$scope.isActive = function(item){
			return $scope.selected === item;
		}

}






