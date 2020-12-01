angular.module 'mis'

	.factory 'milestone', ($http)->
		return{
			get:(pId) ->
				$http.get '/api/milestones', params: project_id: pId

			save: (formData)->
				$http
					method: 'POST'
					url: '/projects/'+ formData.id + '/milestones'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			edit: (id,pId)->
				$http.get '/api/milestone/'+id, params: project_id: pId

			update: (formData,id)->
				$http
					method: 'PUT'
					url: '/projects/'+ formData.project_id + '/milestones/' + formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)
					

			destroy: (pId,id)->
				$http.delete('/projects/'+ pId + '/milestones/' + id)

			completed: (id, status)->
				$http
					method: 'POST'
					url: '/milestone-status'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(
						id: id
						completed: status
						)

		}