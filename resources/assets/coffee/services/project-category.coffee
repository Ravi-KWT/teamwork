angular.module 'mis'

	.factory 'projectCategory', ($http)->
		return{
			get: ->
				$http.get '/api/project-categories'

			save: (formData)->
				$http
					method: 'POST'
					url: '/project-categories'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			edit: (id)->
				$http.get '/api/project-category/'+id

			update: (formData)->
				$http
					method: 'PUT'
					url: '/project-categories/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			destroy: (id)->
				$http.delete('/project-categories/' + id)

			getProjects: (id)->
				$http.get '/api/project-categories-projects'+id

		}