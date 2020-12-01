angular.module 'mis'

	.factory 'Theme', ($http)->
		return{
			update: (class_name)->
				$http
					method: 'POST'
					url: '/change-theme'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param({name: class_name})
		}