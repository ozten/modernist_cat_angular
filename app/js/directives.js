'use strict';

/* Directives */


app.directive("thumbnail", function() {
	return {
		scope: {
			name:"@",
			subtitle:"@",
			prodid:"@"
		},
		restrict: "E",
		transclude: true,
		template: '<li class="three columns"><a href="#/products/{{prodid}}" title="{{subtitle}}" class="title"> {{name}}</a><a href="#/products/{{prodid}}" title="{{subtitle}}" class="title" ng-transclude></a></li>',
		replace: true
	}
});

app.directive("chooser", function($compile) {
	return {
		restrict: 'A',
		require: 'ngModel',
		link: function(scope, element, attrs, ctrl){
				element.bind("click", function(){
					scope.$apply(attrs.enter);
					// view > model
					ctrl.$setViewValue(element.text());
					});
				

				attrs.$observe('layout', function(value){
					if(value == "tile"){
						element.bind("mouseenter mouseleave", function(){
							element.toggleClass('selected-hover')
							});	
					}
					if (value=="radio"){
						var input = '<input type="radio" name="' + scope.optionType + '">';
						var html = $compile(input)(scope);
						console.log("radio:", html)
						element.prepend(html);
					}
				})
		}
		
	}

});




