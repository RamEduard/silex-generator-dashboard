(function () {
	'use strict';

	angular.module('app.index2', ['ngRoute'])

	.config(['$routeProvider', function($routeProvider) {
	  $routeProvider.when('/index2', {
	    templateUrl: TEMPLATES_PATH + '/index2.html',
	    controller: 'Index2Ctrl'
	  });
	}])

	.controller('Index2Ctrl', [function() {

	}]);
})();