/**
 * Http module for capturing requests
 *
 * Use the configuration property *proxy* to enable this feature.
 * The HttpInterceptor takes care of failed requests.
 * The HttpManager interacts with the HttpQueue to replay requests.
 * The HttpHeartbeat instance checks the Navigator and Server status.
 *
 * Changes to the online status are issued using the $rootScope.
 * $rootScope.$watch('online', function(newValue, oldValue) { ... });
 *
 * Example:
 * $http.get('url', {}, { proxy: true });
 * $http.get({url: 'url', data: data, proxy: true});
 *
 * @author Oliver Hader <oliver@typo3.org>
 */
angular.module('Http', [])
.run(['$rootScope', 'HttpManager', 'HttpHeartbeat', function($rootScope, HttpManager, HttpHeartbeat) {
	$rootScope.$watch('online', handleOnline);

	function handleOnline(newValue, oldValue) {
		if (typeof newValue === 'undefined' || typeof oldValue === 'undefined' || newValue === oldValue) {
			return;
		}
		if (newValue) {
			HttpManager.manage();
		}
	}

	HttpHeartbeat.start();
}])
.config(['$httpProvider', function($httpProvider) {
	$httpProvider.interceptors.push('HttpInterceptor');
}])
.factory('HttpManager', ['$rootScope', '$http', 'HttpQueue', function($rootScope, $http, HttpQueue) {
	function replay(request, proxyId) {
		request.config.proxyReplay++;
		$http(request.config);
	}

	return {
		manage: function() {
			angular.forEach(HttpQueue.all(), replay);
		}
	}
}])
.factory('HttpQueue', function() {
	var requests = {};

	function getRandomString() {
		var i, randomString = '';
		for (i = 0; i < 16; i++) {
			randomString += Math.floor(Math.random() * 256).toString(16);
		}
		return randomString;
	}

	return {
		all: function() {
			return requests;
		},
		getRequest: function(proxyId) {
			return requests[proxyId];
		},
		needsHandling: function(config) {
			return (config.proxy || config.proxyId);
		},
		addRequest: function addRequest(config) {
			if (!config.proxy || config.proxyId && requests[config.proxyId]) {
				return false;
			}
			config.proxyId = getRandomString();
			requests[config.proxyId] = {
				config: config,
				deferred: null
			};
		},
		setDeferred: function(config, deferred) {
			if (!config.proxy || !config.proxyId || requests[config.proxyId]['deferred']) {
				return false;
			}
			config.proxyReplay = 0;
			requests[config.proxyId]['deferred'] = deferred;
		},
		removeRequest: function(config, data) {
			if (!config.proxyId || !requests[config.proxyId]) {
				return false;
			}
			if (requests[config.proxyId]['deferred']) {
				requests[config.proxyId]['deferred'].resolve(data);
			}
			delete requests[config.proxyId];
		}
	}
})
.factory('HttpHeartbeat', ['$window', '$rootScope', '$http', '$interval', function($window, $rootScope, $http, $interval) {
	var interval;
	var serverOnline;
	var navigatorOnline;

	navigatorOnline = navigator.onLine;
	$window.addEventListener('online', emitNavigatorChange);
	$window.addEventListener('offline', emitNavigatorChange);

	function emitNavigatorChange() {
		navigatorOnline = navigator.onLine;
	}

	function beat() {
		var current = serverOnline;

		$http.get('/ping')
		.success(function() {
			serverOnline = true;
		})
		.error(function(data, status) {
			serverOnline = false;
		});

		$rootScope.online = (serverOnline && navigatorOnline);
	}

	return {
		isOnline: function() {
			return $rootScope.online;
		},
		start: function() {
			//$interval(beat, 2500);
		},
		stop: function() {

		}
	};
}])
.factory('HttpInterceptor', ['$q', '$location', 'HttpQueue', function($q, $location, HttpQueue) {
	return {
		request: function(config) {
			HttpQueue.addRequest(config);
			return config;
		},

		requestError: function(rejection) {
			HttpQueue.removeRequest(rejection.config);
			return $q.reject(rejection);
		},

		response: function(response) {
			HttpQueue.removeRequest(response.config, response.data);
			return response;
		},

		responseError: function(rejection) {
			if (HttpQueue.needsHandling(rejection.config) && rejection.status === 0) {
				var deferred = $q.defer();
				deferred.notify('Network issues');
				HttpQueue.setDeferred(rejection.config, deferred);
				return deferred.promise;
			}
			return $q.reject(rejection);
		}
	};
}]);
