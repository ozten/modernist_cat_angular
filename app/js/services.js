'use strict';

/* Services 

angular.module('jsonService', ['ngResource']).
factory('JsonService', function($resource){
  return $resource('products/:File.json', {}, {
    query: {method:'GET', params:{File:'options'}}
  });
});*/

angular.module('jsonService', ['ngResource']).
factory('JsonService', function($resource){
  return $resource('products/options.json', {}, {
    query: {method:'GET', isArray: false}
  });
});