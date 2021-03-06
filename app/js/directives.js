'use strict';

/* Directives */



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
						var input = '<input type="radio" name="' + scope.optionType.title + '" value="' + id[1] + '">';
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
			caption: "@"		
		},
		link: function (scope, element, attrs) {
           scope.url = 'http://modernistcat.com';
        },
		template: '<newwindow class="link" href="//pinterest.com/pin/create/button/?url={{url}}/{{prodid}}&' + 
		'media={{url}}/{{img}}&description={{caption}} from Modernistcat.com" data-pin-do="buttonPin" data-pin-config="none" >' +
		'<img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></newwindow>'
	}
})

app.directive("newwindow", function(){
	return {
		restrict: 'E',
		link: function(scope, element, attrs){
			element.bind("click", function(){
				window.open(attrs.href, "Create Pin", 'width=600, height=400');
				return false;
				})
		}
	}

	})






