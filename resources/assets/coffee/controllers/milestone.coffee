angular.module 'mis'
	.controller 'milestoneCtrl', ($scope, milestone,$filter, $timeout, $window,notify,$resource,$http)->
		$scope.loading = true
		$scope.currentPage = 1
		$scope.edit = false
		$scope.modal_title = 'Add'
		if $scope.edit == false
			$scope.modal_title = 'Add'

		currentUrl = $window.location.href
		pId = currentUrl.split('/')[4]||"Undefined"

		milestone.get(pId).success (data)->
			$scope.milestones = data.milestones
			$scope.users = []
			angular.forEach data.users, (value,key) ->
				if value.id != 0
					if value.people.lname
						$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
					else
						$scope.users.push (id:value.id,label:value.people.fname)
				return
			$scope.loading = false

		$scope.example14model = [

		]

		$scope.example14settings = externalIdProp: ''
		$scope.Pro_Id = pId

		$scope.milestone_completed = (id,completed)->
			milestone.completed(id, completed).success (data)->
			if completed == true
				notify
					message: 'Milestone reopen'
					duration: 1500
					position: 'right'
			else
				notify
					message: 'Milestone completed'
					duration: 1500
					position: 'right'

			return

		$scope.clearAll = ->
			angular.element('#addNewAppModal').modal('hide')
			$timeout (->
				$scope.submitted = false
				$scope.edit = false
				$scope.milestone = {}
				), 100
			return

		$scope.submit = (form)->
			$scope.loading = true
			$scope.submitted = true
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true

			if $scope.edit == false
				$scope.milestone.project_id = pId
				$scope.milestone.user_id = []
				angular.forEach $scope.example14model, (value, key) ->
					$scope.milestone.user_id.push(value.id)
					return
				milestone.save($scope.milestone).success (data)->
					$scope.submitted = false
					$scope.milestone = {}
					angular.element('#addNewAppModal').modal('hide')
					notify
						message: "Added successfully"
						duration: 1500
						position: 'right'
					$scope.example14model.length = 0

					milestone.get(pId).success (getData)->
						$scope.milestones = getData.milestones
						$scope.users = []
						angular.forEach getData.users, (value,key) ->
							if value.id != 0
								if value.people.lname
									$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
								else
									$scope.users.push (id:value.id,label:value.people.fname)
							return
						$scope.loading = false
			else
				$scope.milestone.user_id = []
				angular.forEach $scope.example14model, (value, key) ->
					$scope.milestone.user_id.push(value.id)
					return
				milestone.update($scope.milestone).success (data)->
					console.log data
					$scope.submitted = false
					$scope.edit = false
					$scope.milestone = {}
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: "Updated successfully"
							duration: 1500
							position: 'right'

						milestone.get(pId).success (getData)->
							$scope.milestones = getData.milestones
							$scope.users = []
							angular.forEach getData.users, (value,key) ->
								if value.id != 0
									if value.people.lname
										$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
									else
										$scope.users.push (id:value.id,label:value.people.fname)
								return
							$scope.loading = false
					), 100

		angular.element('#addNewAppModal').on('hidden.bs.modal', (form)->
			$scope.clearAll(form)
			$scope.modal_title = 'Add'
			$scope.example14model.length = 0
		)

		$scope.deleteMilestone = (id)->
			swal(
				title: 'Are you Sure?'
				text: 'You won\'t be able to revert this!'
				type: 'warning'
				timer: 7000
				showCancelButton: true
				).then((result)->
					if result.value
						$scope.loading = true
						milestone.destroy(id).success (data)->
							milestone.get(pId).success (getData)->
								$scope.milestones = getData.milestones
								# $scope.users = getData.users

								$scope.users = []
								angular.forEach getData.users, (value,key) ->
									if value.id != 0
										if value.people.lname
											$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
										else
											$scope.users.push (id:value.id,label:value.people.fname)
									return
								$scope.loading = false
								
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result.dismiss == swal.DismissReason.cancel
  						swal 'Cancelled', 'Your record is safe ', 'info'
				)			


		# $scope.deleteMilestone = (id)->
		# 	$scope.loading = true
		# 	milestone.destroy(id).success (data)->
		# 		milestone.get(pId).success (getData)->
		# 			$scope.milestones = getData.milestones
		# 			# $scope.users = getData.users

		# 			$scope.users = []
		# 			angular.forEach getData.users, (value,key) ->
		# 				if value.id != 0
		# 					if value.people.lname
		# 						$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
		# 					else
		# 						$scope.users.push (id:value.id,label:value.people.fname)
		# 				return
		# 			$scope.loading = false


		# 		notify
		# 			message: "Deleted successfully"
		# 			duration: 1500
		# 			position: 'right'

		$scope.editMilestone = (id)->
			milestone.edit(id).success (data)->
				$scope.milestone = data.milestone
				$scope.milestone.due_date1 = data.milestone.due_date
				$scope.milestone.due_date=$filter('date') data.milestone.due_date, 'dd-MM-yyyy'
				
				$scope.users=[]
				angular.forEach data.allUsers, (value,key) ->
					if value.id != 0
						if value.people.lname
							$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
						else
							$scope.users.push (id:value.id,label:value.people.fname)
					return

				$scope.example14model = []
				angular.forEach data.milestone_users, (value,key) ->
					if(value.id !=0)
						if value.lname != null
							$scope.example14model.push({id:value.user_id,label:value.fname+' '+value.lname})
						else
							$scope.example14model.push({id:value.user_id,label:value.fname})
					return
				$scope.edit = true
				if $scope.edit == true
					$scope.modal_title = 'Save'
				angular.element('#addNewAppModal').modal('show')