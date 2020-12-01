angular.module 'mis'

	.factory 'Industry', ($http)->
		return{
			get: ->
				$http.get '/api/industries'

			save: (formData)->
				$http
					method: 'POST'
					url: '/industries'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			edit: (id)->
				$http.get '/api/industry/'+id

			update: (formData)->
				$http
					method: 'PUT'
					url: '/industries/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			destroy: (id)->
				$http.delete('/industries/' + id)
		}