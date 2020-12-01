angular.module 'mis'

	.factory 'Designation', ($http)->
		return{
			get: ->
				$http.get '/api/designations'

			save: (formData)->
				$http
					method: 'POST'
					url: '/designations'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			edit: (id)->
				$http.get '/api/designation/'+id

			update: (formData)->
				$http
					method: 'PUT'
					url: '/designations/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			destroy: (id)->
				$http.delete('/designations/' + id)
		}