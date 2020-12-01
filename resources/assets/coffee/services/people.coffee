angular.module 'mis'

	.factory 'PEOPLE', ($http)->
		return{
			get: ->
				$http.get '/api/people'

			getCountry: ->
				$http.get '/api/country'

			save: (formData, educations, experiences)->
				$http
					method: 'POST'
					url: '/people'
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
					data: $.param(
						is_teamlead: formData.is_teamlead
						is_projectlead: formData.is_projectlead
						user_detail: formData 
						educations: educations
						experiences: experiences
						)
			getProjectPeople: (id)->
				$http.get '/api/project-people/'+id

			addPeopleToProject: (users,project_id)->
				$http
					method: 'POST'
					url: '/add-people-to-project'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(
						users: users
						project_id: project_id
						)
			statusChanged: (id)->
				$http
					method: 'POST'
					url: '/user-status-changed'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(
						id: id
						)
			edit: (id)->
				$http.get '/api/people/'+id

			view_people: (id)->
				$http.get '/api/viewpeople/'+id
		   

			update: (formData, educations, experiences)->
				$http
					method: 'PUT'
					url: '/people/'+formData.id
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(
						is_teamlead: formData.is_teamlead
						is_projectlead: formData.is_projectlead
						roles: formData.roles
						user_detail:formData 
						educations: educations
						experiences: experiences
						)
			destroy: (id)->
				$http.delete('/people/' + id)

			destroyEducation: (id)->
				$http.delete('/education/' + id)

			destroyExperience: (id)->
				$http.delete('/experience/' + id)

			login:(formData)->
				$http
					method: 'POST'
					url: '/login'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(formData)
			fogotPassword: (forgotPasswordData)->
				$http
					method: 'POST'
					url: '/password/email'
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' }
					data: $.param(forgotPasswordData)
		}