/**
 * General data handling and interaction methods
 * @author Oliver Hader <oliver@typo3.org>
 */
angular.module('OpenAgenda.Data', [])
.factory('OpenAgenda.Data.Utility', [function() {
	function assertTypeFormat(data) {
		if (angular.isDate(data)) {
			data = moment(data).format();
		} else if (angular.isObject(data) || angular.isArray(data)) {
			angular.forEach(data, function(value, key) {
				if (angular.isString(key) && key.substr(0, 1) === '$') {
					delete data[key];
				} else {
					data[key] = assertTypeFormat(value);
				}
			});
		}

		return data;
	}

	return {
		jsonCast: function(data) {
			return assertTypeFormat(
				angular.copy(data)
			);
		}
	}
}])
.factory('OpenAgenda.Data.Interceptor', [function() {
	return {
		response: function (response) {
			if (typeof response.config.interceptorCallback === 'function') {
				response.config.interceptorCallback(response.data);
			}
			return response;
		}
	};
}])
.factory('OpenAgenda.Data.Store', ['$q', '$resource', 'OpenAgenda.Data.Utility', 'OpenAgenda.Data.LocalStorage', 'OpenAgenda.Data.Interceptor',
	function($q, $resource, oaUtility, oaLocalStorage, oaInterceptor) {
	var stores = {};

	function Store(storeName) {
		var name = storeName;
		var identifier = '__identitity';
		var endpoints = {
			fetchAll: '/:name/list.json',
			fetch: '/:name/:identifier/show.json',
			create: '/:name/create.json',
			update: '/:name/:identifier/update.json',
			remove: '/:name/:identifier/delete.json'
		};
		var defaultActions = {
			'get':    { method:'GET', interceptor: oaInterceptor, interceptorCallback: getInterceptor },
			'save':   { method:'POST', interceptor: oaInterceptor, interceptorCallback: saveInterceptor },
			'query':  { method:'GET', isArray:true, interceptor: oaInterceptor, interceptorCallback: queryInterceptor },
			'remove': { method:'GET', interceptor: oaInterceptor, interceptorCallback: removeInterceptor }
		};

		var subjects = {};

		function getIdentifier(subject) {
			if (typeof subject === 'object' && typeof subject[identifier] === 'string') {
				return subject[identifier];
			} else if (typeof subject === 'string') {
				return subject;
			}
			return null;
		}

		function getInterceptor(data) {
			subjects[getIdentifier(data)] = data;
			console.log(data);
		}

		function saveInterceptor(data) {

		}

		function queryInterceptor(data) {

		}

		function removeInterceptor(data) {

		}

		function access(resourceResult) {
			return {
				access: function() {
					return resourceResult;
				},
				invoke: function(callback) {
					callback(resourceResult);
				}
			}
		}

		function accessWrap(deferred) {
			return function(resourceResult) {
				deferred.resolve(resourceResult);
			}
		}

		this.fetchAll = function() {
			return $resource(endpoints.fetchAll, { name: name }, defaultActions);
		};
		this.fetch = function(subject) {
			return $resource(endpoints.fetch, { name: name, identifier: getIdentifier(subject) }, defaultActions);
		};
		this.save = function(subject) {
			if (getIdentifier(subject) === null) {
				return $resource(endpoints.create, { name: name, identifier: getIdentifier(subject) }, defaultActions);
			} else {
				return $resource(endpoints.update, { name: name, identifier: getIdentifier(subject) }, defaultActions);
			}
		};
		this.remove = function(subject) {
			return $resource(endpoints.remove, { name: name, identifier: getIdentifier(subject) }, defaultActions);
		}
	}

	return {
		// Singleton access on instances
		get: function(storeName) {
			if (typeof stores[storeName] === 'undefined') {
				stores[storeName] = new Store(storeName);
			}
			return stores[storeName];
		}
	}
}])
.factory('OpenAgenda.Data.MeetingStore', ['OpenAgenda.Data.Store', function(oaDataStore) {
	return oaDataStore.get('meeting');
}])
.factory('OpenAgenda.Data.TaskStore', ['OpenAgenda.Data.Store', function(oaDataStore) {
	return oaDataStore.get('task');
}])
.factory('OpenAgenda.Data.LocalStorage', ['$window', '$rootScope', function($window, $rootScope) {
	angular.element($window).on('storage', function(event) {
		console.log(event);
	});

	return {
		set: function(key, value) {
			$window.localStorage.setItem('OpenAgenda.Data.LocalStorage::' + key, value);
		},
		get: function(key) {
			return $window.localStorage.getItem('OpenAgenda.Data.LocalStorage::' + key);
		}
	};
}]);
