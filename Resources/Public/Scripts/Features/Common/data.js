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
				data[key] = assertTypeFormat(value);
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
}]);
