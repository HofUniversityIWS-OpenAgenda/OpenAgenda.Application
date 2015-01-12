/**
 * @namespace angular_module.OpenAgenda
 * @memberOf angular_module
 */

/**
 * @deprecated The OpenAgenda.Data module is experimental and currently not used at all!
 *
 * @description Data transfer layer and HTTP interception methods.
 *
 * <p>
 * This module keeps track of failed HTTP requests using the accordant HTTP interceptor
 * for each scope. Scopes in this regard are "fetchAll", "fetch", "save" and "remove", thus
 * the typical CRUD actions. The data stores are temporarily persisted in the browser's
 * local storage.
 * </p>
 *
 * @example OpenAgenda.Data.MeetingStore.fetchAll().then(function(data) { });
 *
 * @class Data
 * @memberOf angular_module.OpenAgenda
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
/**
 * @deprecated The OpenAgenda.Data module is experimental and currently not used at all!
 *
 * @class Interceptor
 * @memberOf angular_module.OpenAgenda.Data
 * @description HTTP interception handler calling a callback function in the concrete Data.Store instance
 * @author Oliver Hader <oliver@typo3.org>
 */
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
/**
 * @deprecated The OpenAgenda.Data module is experimental and currently not used at all!
 *
 * @abstract
 * @class Store
 * @memberOf angular_module.OpenAgenda.Data
 * @description Abstract data store defining various data-handling methods
 * @author Oliver Hader <oliver@typo3.org>
 */
.factory('OpenAgenda.Data.Store', ['$q', '$resource', 'OpenAgenda.Data.Utility', 'OpenAgenda.Data.LocalStorage', 'OpenAgenda.Data.Interceptor',
	function($q, $resource, oaUtility, oaLocalStorage, oaInterceptor) {
	var stores = {};

	/**
	 * @constructor
	 * @memberOf angular_module.OpenAgenda.Data
	 * @param {string} storeName Name/type of the store (e.g. "Meeting" or "Task")
	 */
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

		/**
		 * @private
		 * @method getInterceptor
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @param {object} subject
		 * @return {string}
		 */
		function getIdentifier(subject) {
			if (typeof subject === 'object' && typeof subject[identifier] === 'string') {
				return subject[identifier];
			} else if (typeof subject === 'string') {
				return subject;
			}
			return null;
		}

		/**
		 * @private
		 * @method getInterceptor
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @param {object} data
		 */
		function getInterceptor(data) {
			subjects[getIdentifier(data)] = data;
			console.log(data);
		}

		/**
		 * @private
		 * @method saveInterceptor
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @param {object} data
		 */
		function saveInterceptor(data) {

		}

		/**
		 * @private
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @param {object} data
		 */
		function queryInterceptor(data) {

		}

		/**
		 * @private
		 * @method removeInterceptor
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @param {object} data
		 */
		function removeInterceptor(data) {

		}

		/**
		 * @private
		 * @method access
		 * @param {object} resourceResult
		 * @returns {{access: Function, invoke: Function}}
		 */
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

		/**
		 * @private
		 * @method accessWrap
		 * @param {object} deferred
		 * @returns {Function}
		 */
		function accessWrap(deferred) {
			return function(resourceResult) {
				deferred.resolve(resourceResult);
			}
		}

		/**
		 * @description Fetches all objects
		 * @method fetchAll
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @returns {angular_module.$resource}
		 */
		this.fetchAll = function() {
			return $resource(endpoints.fetchAll, { name: name }, defaultActions);
		};
		/**
		 * @description Fetches one object
		 * @method fetch
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @param {object|string} subject
		 * @returns {angular_module.$resource}
		 */
		this.fetch = function(subject) {
			return $resource(endpoints.fetch, { name: name, identifier: getIdentifier(subject) }, defaultActions);
		};
		/**
		 * @description Saves one object
		 * @method save
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @param {object|string} subject
		 * @returns {angular_module.$resource}
		 */
		this.save = function(subject) {
			if (getIdentifier(subject) === null) {
				return $resource(endpoints.create, { name: name, identifier: getIdentifier(subject) }, defaultActions);
			} else {
				return $resource(endpoints.update, { name: name, identifier: getIdentifier(subject) }, defaultActions);
			}
		};
		/**
		 * @decription Removes one object
		 * @method remove
		 * @memberOf angular_module.OpenAgenda.Data.Store
		 * @param {object|string} subject
		 * @returns {angular_module.$resource}
		 */
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
/**
 * @deprecated The OpenAgenda.Data module is experimental and currently not used at all!
 *
 * @class MeetingStore
 * @memberOf angular_module.OpenAgenda.Data
 * @description Concrete instance of a data store for Meeting objects
 * @param {object} OpenAgenda.Data.Store The abstract data store
 * @author Oliver Hader <oliver@typo3.org>
 */
.factory('OpenAgenda.Data.MeetingStore', ['OpenAgenda.Data.Store', function(oaDataStore) {
	return oaDataStore.get('meeting');
}])
/**
 * @deprecated The OpenAgenda.Data module is experimental and currently not used at all!
 *
 * @class TaskStore
 * @memberOf angular_module.OpenAgenda.Data
 * @description Concrete instance of a data store for Task objects
 * @param {object} OpenAgenda.Data.Store The abstract data store
 * @author Oliver Hader <oliver@typo3.org>
 */
.factory('OpenAgenda.Data.TaskStore', ['OpenAgenda.Data.Store', function(oaDataStore) {
	return oaDataStore.get('task');
}])
/**
 * @deprecated The OpenAgenda.Data module is experimental and currently not used at all!
 *
 * @class LocalStorage
 * @memberOf angular_module.OpenAgenda.Data
 * @description Wrapper for browser's local storage implementation
 * @author Oliver Hader <oliver@typo3.org>
 */
.factory('OpenAgenda.Data.LocalStorage', ['$window', '$rootScope', function($window, $rootScope) {
	angular.element($window).on('storage', function(event) {
		console.log(event);
	});

	return {
		/**
		 * @description Sets values in local storage
		 * @method set
		 * @memberOf angular_module.OpenAgenda.Data.LocalStorage
		 * @param {string} key
		 * @param {mixed} value
		 */
		set: function(key, value) {
			$window.localStorage.setItem('OpenAgenda.Data.LocalStorage::' + key, value);
		},
		/**
		 * @description Gets values from local storage
		 * @method get
		 * @memberOf angular_module.OpenAgenda.Data.LocalStorage
		 * @param {string} key
		 */
		get: function(key) {
			return $window.localStorage.getItem('OpenAgenda.Data.LocalStorage::' + key);
		}
	};
}]);
