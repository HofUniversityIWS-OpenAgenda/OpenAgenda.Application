/**
 * @deprecated The Http module is experimental and currently not used at all!
 *
 * @class angular_module.Http
 * @memberOf angular_module
 * @description Http module for capturing requests
 *
 * <p>
 * Use the configuration property *proxy* to enable this feature.
 * The HttpInterceptor takes care of failed requests.
 * The HttpManager interacts with the HttpQueue to replay requests.
 * The HttpHeartbeat instance checks the Navigator and Server status.
 * </p>
 *
 * <p>
 * Changes to the online status are issued using the $rootScope.
 * $rootScope.$watch('online', function(newValue, oldValue) { ... });
 * </p>
 *
 * @example
 * $http.get('url', {}, { proxy: true });
 * $http.get({url: 'url', data: data, proxy: true});
 *
 * @author Oliver Hader <oliver@typo3.org>
 */
angular.module('Http', [])
/**
 * @description Constructor to instantiate the heartbeat service
 * @method run
 * @memberOf angular_module.Http
 * @param {object} $rootScope {@link http://docs.angularjs.org/api/ng.$rootScope}
 * @param {object} HttpManager Http.HttpManager of this module
 * @param {object} HttpHeartbeat Http.HttpHeartbeat of this module
 */
.run(['$rootScope', 'HttpManager', 'HttpHeartbeat', function($rootScope, HttpManager, HttpHeartbeat) {
	$rootScope.$watch('online', handleOnline);

	/**
	 * @description Handles the status change triggered by the heartbeat service
	 * @function handleOnline
	 * @memberOf angular_module.Http
	 * @param {bool} newValue New status value
	 * @param {bool} oldValue Previous status value
	 */
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
/**
 * @description Constructor configuration injection
 * @method config
 * @memberOf angular_module.Http
 * @param {object} $httpProvider {@link http://docs.angularjs.org/api/ng.$httpProvider}
 */
.config(['$httpProvider', function($httpProvider) {
	$httpProvider.interceptors.push('HttpInterceptor');
}])
/**
 * @deprecated The Http module is experimental and currently not used at all!
 *
 * @description HttpManager factory to replay failed requests
 * @class HttpManager
 * @memberOf angular_module.Http
 * @param {object} $rootScope {@link http://docs.angularjs.org/api/ng.$rootScope}
 * @param {object} $http {@link http://docs.angularjs.org/api/ng.$http}
 * @param {object} HttpQueue Http.HttpQueue of this module
 * @author Oliver Hader <oliver@typo3.org>
 */
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
/**
 * @deprecated The Http module is experimental and currently not used at all!
 *
 * @description HttpQueue factory to enqueue requests in general
 * @class HttpQueue
 * @memberOf angular_module.Http
 * @author Oliver Hader <oliver@typo3.org>
 */
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
/**
 * @deprecated The Http module is experimental and currently not used at all!
 *
 * @description HttpHeartbeat factory to keep track of browser connectivity and remote server avilability
 * @class HttpHeartbeat
 * @memberOf angular_module.Http
 * @param {object} $window {@link http://docs.angularjs.org/api/ng.$window}
 * @param {object} $rootScope {@link http://docs.angularjs.org/api/ng.$rootScope}
 * @param {object} $http {@link http://docs.angularjs.org/api/ng.$http}
 * @param {object} $interval {@link http://docs.angularjs.org/api/ng.$interval}
 * @author Oliver Hader <oliver@typo3.org>
 */
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
/**
 * @deprecated The Http module is experimental and currently not used at all!
 *
 * @description HttpInterceptor factory to keep track of successful and failed HTTP request
 * @class HttpInterceptor
 * @memberOf angular_module.Http
 * @param {object} $q {@link http://docs.angularjs.org/api/ng.$q}
 * @param {object} $location {@link http://docs.angularjs.org/api/ng.$location}
 * @param {object} HttpQueue Http.HttpQueue of this module
 * @author Oliver Hader <oliver@typo3.org>
 */
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
