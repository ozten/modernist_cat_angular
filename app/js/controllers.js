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
	$http.get('products/' + $routeParams.productId + '.json').success(function(data){
		$scope.product = data;
		$scope.mainImageUrl = data.images[0];
		$scope.selectionPrice = data.price;
	});

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
	
	$scope.toggleAddons = function(pr){
		console.log("toggleAddons")
		$scope.isVisible = ! $scope.isVisible;
		var price = parseInt(pr);
		if ($scope.isVisible == true){
			$scope.selectionPrice += price;
			console.log($scope.selectionPrice);
		} else {
			$scope.selectionPrice -= price;
			console.log($scope.selectionPrice);
		}
	}
	$scope.isVisible = false;
});

function ProductSelectionCtrl ($scope){
	$scope.select = function(item){
			$scope.selected = item;
		}
		$scope.isActive = function(item){
			return $scope.selected === item;
		}

}






