angular.module 'mis'

	.controller 'ThemeCtrl', ($scope, Theme, $interval, prompt, $timeout,notify)->

		$scope.changeTheme = (class_name) ->
			$scope.loading = true
			Theme.update(class_name).success (data) ->
				$scope.loading = true
				angular.element("body").removeAttr("class").addClass(data.class_name)
				notify
					message: 'Theme changed successfully.'
					duration: 1500
					position: 'right'



