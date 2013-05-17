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
	$scope.addonsSelected = {};

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
		
		$scope.mainImageUrl = imageUrl;
	}

	JsonService.query(function(response){
    	$scope.options = response;	
		
	});
	
	$scope.updatePrice = function(val, pr, title){
		
		$scope.totalPrice = $scope.selectionPrice
	
		var price = parseInt(pr);

		//todo figure out why this is showing the opposite value
		if (val == "false") {
			$scope.addonPrice += price;			
			//which item is checked
			$scope.addonsSelected[title] = true;			
		} else {
			$scope.addonPrice -= price;
			//which item is checked
			$scope.addonsSelected[title] = false;
		}
		$scope.totalPrice += $scope.addonPrice; 
		if ($scope.totalPrice != $scope.selectionPrice) {
			$scope.addonString = "+ $" + $scope.addonPrice +  " = $" + $scope.totalPrice;
		} else {
			$scope.addonString = "";
		}
	}

	$scope.validateSelections = function(){
		//check configuration choices
		var availOptions = $scope.product.options;
		for (var i=0; i < availOptions.length; i++){
			var title = $scope.options[availOptions[i]].title
			var sel = ($scope.choiceValues[title]);
			if (sel == undefined){
				alert("Please select " + title);
			} 
		}
		//check addons if true, must make a selection
		var addons = $scope.product.addons;
		for (var i=0; i < addons.length; i++){
			var title = addons[i].title;
			var sel = $scope.addonValues[title];
			if ($scope.addonsSelected[title] == true && sel == undefined && addons[i].options != null){
					alert("Please select " + addons[i].title);				
			}
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








