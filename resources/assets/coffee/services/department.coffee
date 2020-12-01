angular.module 'mis'

	.factory 'Department', ($http)->
		return{
			get: ->
				$http.get '/api/departments'

			save: (formData)->
				$http
					method: 'POST'
					url: '/departments'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			edit: (id)->
				$http.get '/api/department/'+id

			update: (formData)->
				$http
					method: 'PUT'
					url: '/departments/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			destroy: (id)->
				$http.delete('/departments/' + id)
		}