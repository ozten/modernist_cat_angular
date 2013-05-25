'use strict';
var app = angular.module("modCat", ['ui.bootstrap', 'jsonService']);

// Declare app level module which depends on filters, and services
app.config(function ($routeProvider, $locationProvider) {
    $routeProvider
    	.when('/',
            {
                templateUrl: 'partials/home.html',
            })

        .when('/shop', 
    		{
    			templateUrl: 'partials/shop.html'
    		})
        .when('/products/', 
            {
                templateUrl: 'partials/shop.html'
            })
    	.when('/products/:productId',
    		{
    			templateUrl: 'partials/productDetail.html',
    			controller: "ProductDetailCtrl"
    		})
        .when('/thanks',
            {
                templateUrl: 'partials/thanks.html',
            })
        .when('/featuredlinks',
            {
                templateUrl: 'partials/alllinks.html',
                controller: 'LinkListCtrl'
            })
        .when('/edit',
            {
                templateUrl: 'partials/edit.html',
                controller: 'EditListCtrl'
            })
    	.otherwise({redirectTo: '/'});

        //$locationProvider.html5Mode(true).hashPrefix('!');
  });

var edit = angular.module("modCatEdit", ['ui.bootstrap', 'jsonService']);

// Declare app level module which depends on filters, and services
edit.config(function ($routeProvider, $locationProvider) {
    $routeProvider
        .when('/edit',
            {
                templateUrl: 'edit/index.html',
            })

        
  });

