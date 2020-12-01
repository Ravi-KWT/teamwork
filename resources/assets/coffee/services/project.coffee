angular.module 'mis'

	.factory 'PROJECT', ($http)->
		return{
			get: ->
				$http.get '/api/projects'

			getCompany:->
				$http.get '/api/companies'

			save: (formData)->
				$http
					method: 'POST'
					url: '/projects'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)


			edit: (id)->
				$http.get '/api/projects/'+id

			update: (formData)->
				$http
					method: 'PUT'
					url: '/projects/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			destroy: (id)->
				$http.delete('/projects/' + id)
			saveProjectCategory: (formData)->
				$http
					method: 'POST'
					url: '/project-categories'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)
		}