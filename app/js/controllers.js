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

	$scope.switchImage = function(prodid, sel, key) {
		sel = sel.replace(/&|\s+/g, '');
		sel = sel.toLowerCase();
		var imageUrl = "img/products/switches/" + prodid + "-" + sel + ".jpg";
		if (prodid == "set"){
			if (key == "laminatestandard" || key == "entSide"){
				imageUrl = "img/products/switches/standard-" + sel + ".jpg";
			} else {
				imageUrl = "img/products/switches/compactII-" + sel + ".jpg";
			}
		}
		if (prodid == "feeder"){
			imageUrl = "img/products/feedersizes.jpg";
		}
		$scope.mainImageUrl = imageUrl;
	}

	JsonService.query(function(response){
    	$scope.options = response;	
		
	});
	
	$scope.updatePrice = function(val, pr, id){
		
		$scope.totalPrice = $scope.selectionPrice
	
		var price = parseInt(pr);

		//todo figure out why this is showing the opposite value
		if (val == "false") {
			$scope.addonPrice += price;			
			//which item is checked
			$scope.addonsSelected[id] = true;			
		} else {
			$scope.addonPrice -= price;
			//which item is checked
			$scope.addonsSelected[id] = false;
		}
		$scope.totalPrice += $scope.addonPrice; 
		if ($scope.totalPrice != $scope.selectionPrice) {
			$scope.addonString = "+ $" + $scope.addonPrice +  " = $" + $scope.totalPrice;
		} else {
			$scope.addonString = "";
		}
	}
	$scope.changeBasePrice = function(opt, index){
		$scope.selectionPrice = $scope.options[opt].price[index];
	}

	$scope.validateSelections = function(){
		//check configuration choices
		var availOptions = $scope.product.options;
		var validOptions = false;
		var validAddons = false;
		
		for (var i in availOptions){
			var title = availOptions[i].title
			var sel = ($scope.choiceValues[i]);
			if (sel == undefined){
				alert("Please select " + title);
				validOptions = false;
				return false;
			} else {
				validOptions = true;
			}
		}
		//check addons if true, must make a selection
		var addons = $scope.product.addons;
		for (var i in addons){
			console.log(i)
			var sel = $scope.addonValues[i];
			if ($scope.addonsSelected[i] == true && sel == undefined && addons[i].options != null){
					alert("Please select " + addons[i].title);
					validAddons = false;
					return false;				
			} else {
				validAddons = true;
			}
		}
		console.log(validOptions, validAddons)
		if (validOptions == true && validAddons == true){
			var url = 'http://localhost:8888/cart/?'
			var options=JSON.stringify($scope.choiceValues);
			var addonValues = JSON.stringify($scope.addonValues);
			var addonsSelected = JSON.stringify($scope.addonsSelected);
			var checkoutUrl = 'cart/index.php?product_id=' + $scope.product.id + '&options=' + encodeURIComponent(options) + '&addonsSelected=' + encodeURIComponent(addonsSelected) + '&addonValues=' + encodeURIComponent(addonValues);
			window.location = checkoutUrl;
		} else {
			return false;
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
function CarouselDemoCtrl($scope, $routeParams, $http, JsonService) {

$http.get('products/slides.json').success(function(data){
  $scope.slides = data;
});


  $scope.myInterval = 20000;

  $scope.$watch('slides', function(values) {
	var slides = $scope.slides;
	console.log(slides.length);
    var i, a = [], b;

    for (i = 0; i < slides.length; i += 2) {
      b = { image1: slides[i] };

      if (slides[i + 1]) {
        b.image2 = slides[i + 1];
      }

      a.push(b);
    }

    $scope.groupedSlides = a;

  }, true);
  
  $scope.addSlide = function() {
    $scope.slides.push({
      image: 'http://placekitten.com/'+(200+25*Math.floor(Math.random()*4))+'/200',
      text: ['More','Extra','Lots of','Surplus'][Math.floor(Math.random()*4)] + ' ' +
        ['Cats', 'Kittys', 'Felines', 'Cutes'][Math.floor(Math.random()*4)]
    });
  };
}

var ModalDemoCtrl = function ($scope) {

  $scope.open = function () {
    $scope.shouldBeOpen = true;
  };

  $scope.close = function () {
    $scope.shouldBeOpen = false;
  };

  $scope.items = ['item1', 'item2'];

  $scope.opts = {
    backdropFade: true,
    dialogFade:true

  };

};

function LinkListCtrl($scope, $routeParams, $http, JsonService) {

$http.get('products/weblinks.json').success(function(data){
  $scope.links = data;
});

$scope.onHomePage = function(link){
    return (link.onHomePage == true);
};
};







