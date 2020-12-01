angular.module 'mis'

	.factory 'Resource', ($http)->
		return{
			get: ->
				$http.get '/api/resources'

			save: (formData)->
				$http
					method: 'POST'
					url: '/resources'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			edit: (id)->
				$http.get '/api/resource/'+id

			update: (formData)->
				$http
					method: 'PUT'
					url: '/resources/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			destroy: (id)->
				$http.delete('/resources/' + id)
		}