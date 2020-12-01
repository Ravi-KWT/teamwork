angular.module 'mis'

	.factory 'task', ($http)->
		return{
			get:(pId)->
				$http.get '/api/tasks', params: project_id: pId

			# everythingLog:()->
			# 	$http.get '/api/everything'

			# exportLogsToExcel:(logs)->
			# 	$http
			# 		method: 'POST'
			# 		url: '/exportTask'
			# 		headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
			# 		data: $.param('logs':logs)


			# searchEverything: (formData)->
			# 	$http
			# 		method: 'POST'
			# 		url: '/searchEverything'
			# 		headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
			# 		data: $.param(formData)

			getCat:->
				$http.get '/api/task-categories'

			save: (formData)->
				$http
					method: 'POST'
					url: '/projects/'+ formData.id + '/tasks'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)
			saveTaskCategory: (formData)->
				$http 
					method: 'POST'
					url: '/add-task-category'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)
					
			show: (tId,pId)->
				$http.get '/api/projects/'+pId+'/tasks/'+tId

			

			savelog: (formData,tId)->
				$http
					method: 'POST'
					url: '/logtimes'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData,'task_id':tId)

			edit: (id,pId)->
				$http.get '/api/task/'+id, params: project_id: pId

			getTask: (id,pId)->
				$http.get '/api/task/'+id, params: project_id: pId

			editlog: (id,tId)->
				$http.get '/api/logtime/'+id, params: task_id: tId

			update: (formData,id)->
				$http
					method: 'PUT'
					url: '/projects/'+ formData.project_id + '/tasks/' + formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			updatelog: (formData,id)->
				$http
					method: 'PUT'
					url: '/logtimes/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)

			destroy: (pId,id)->
				$http.delete('/projects/'+ pId + '/tasks/' + id)

			destroylog: (id)->
				$http.delete('/logtimes/' + id)

			completed: (id, status)->
				$http
					method: 'POST'
					url: '/task-status'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(
						id: id
						completed: status
						)

			getName: (id)->
				$http.get '/api/people-name' + id

			getTName: (id)->
				$http.get '/api/task-name' + id

			changeBillabled: (id,value)->
				$http
					method: 'POST'
					url:'/log-billable'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(
						id: id
						billable: value
						)
			startLogTimer:(task_id,user_id,project_id)->
				$http
					method: 'POST'
					url:'/start-log-timer'
					headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
					data: $.param(
						task_id:task_id
						user_id:user_id
						project_id:project_id
						)

			pauseLogTimer:(task_id,user_id,project_id,timer_id)->
				$http
					method: 'POST'
					url:'/pause-log-timer'
					headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
					data: $.param(
						task_id:task_id
						user_id:user_id
						project_id:project_id
						timer_id:timer_id
						)
		}