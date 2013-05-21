'use strict';
var app = angular.module("modCat", ['ui.bootstrap', 'jsonService']);

// Declare app level module which depends on filters, and services
app.config(function ($routeProvider, $locationProvider) {
    $routeProvider
    	.when('/',
            {
                templateUrl: 'partials/home.html',
            })

        .when('/products', 
    		{
    			templateUrl: 'partials/products.html', 
    			controller: "ProductListCtrl"
    		})
    	.when('/products/:productId',
    		{
    			templateUrl: 'partials/productDetail.html',
    			controller: "ProductDetailCtrl"
    		})
    	.otherwise({redirectTo: '/'});

        //$locationProvider.html5Mode(true).hashPrefix('!');
  });
