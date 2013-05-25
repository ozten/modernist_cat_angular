function SlidesEditCtrl ($scope, $http) {
	$http.get('products/slides.json').success(function(data){
		$scope.slides = data;
	})
}