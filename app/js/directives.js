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
					if(value.indexOf("tile") != -1) {
						element.bind("mouseenter mouseleave", function(){
							element.toggleClass('selected-hover')
							});	
					}
					if (value.indexOf("radio") != -1){
						var id = value.split('-');
						var checked = "";
						if(id[1] == scope.options[scope.optionType.options].selected) { 
							checked = "checked='true'"
						}
						var input = '<input type="radio" name="' + scope.optionType.title + '" value="' + id[1] + '"' + checked + '>';
						var html = $compile(input)(scope);
						element.prepend(html);
					}
					if (value.indexOf("radioprice") != -1){
						element.bind("click", function(){
							scope.$apply(attrs.update);	
						})
					}
				})
		}
		
	}

});

app.directive("pinit", function(){
	return {
		restrict: 'E',
		scope: {
			img: "@",
			prodid: "@",
			caption: "@",
			url: "@"		
		},
		templateUrl: 'partials/pinterest.html'
	}
})

app.directive("newwindow", function(){
	return {
		restrict: 'E',
		link: function(scope, element, attrs){
			element.bind("click", function(){
				console.log(attrs.href)
				window.open(attrs.href, "Create Pin", 'width=600, height=400');
				return false;
				})
		}
	}

	})






