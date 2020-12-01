angular.module 'mis'

	.factory 'categoryTask', ($http)->
		return{
			get:(cId)->
				$http.get '/api/task-category-modal-users-list', params: category_id: cId

			save: (formData)->
				$http
					method: 'POST'
					url: '/task-category-modal-add-task'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)
		}