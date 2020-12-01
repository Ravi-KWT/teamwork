angular.module 'mis'

	.factory 'taskCategory', ($http)->
		return{
			get:->
				$http.get '/api/task-categories'
            
            
			getTask:->
				$http.get '/api/tasks'

			save: (formData)->
				$http
					method: 'POST'
					url: '/task-categories'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			edit: (id)->
				$http.get '/api/task-category/'+id

			update: (formData)->
				$http
					method: 'PUT'
					url: '/task-categories/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			destroy: (id)->
				$http.delete('/task-categories/' + id)
		}