(function () {
	'use strict';

	// Declare app level module which depends on views, and components
	angular
		.module('app', [
	  	'ngRoute',
	  	'app.index',
	  	'app.index2'
		])
		.config(['$routeProvider', function($routeProvider) {
	  	$routeProvider.otherwise({redirectTo: '/index'});
		}]);
})();