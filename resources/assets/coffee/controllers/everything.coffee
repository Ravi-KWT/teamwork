angular.module 'mis'

	.controller 'EverythingCtrl', ($scope, task, $timeout,$window,notify, prompt)->

		$scope.loading = true
		$scope.currentPage = 1
		$scope.edit = false
		$scope.formError = 0
		$scope.tsk_completed = ''
		if $scope.edit == false
			$scope.modal_title = 'Add'

		currentUrl = $window.location.href
		pId = currentUrl.split('/')[4]||"Undefined"
		tId = currentUrl.split('/')[6]||"Undefined"

		# task.get(pId).success (data)->
		# 	$scope.tasks = data.tasks
		# 	$scope.users = []
		# 	angular.forEach data.users, (value,key) ->
		# 		$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
		# 		return
		# 	$scope.loading = false
		# 	$scope.example14model = [
		# ]

		# $scope.calc_spent_time = (et,st)->
		# 	$scope.lg_start_time = st
		# 	$scope.lg_end_time = et
		# 	if moment.duration(moment($scope.lg_end_time, 'HH:mm a') - moment($scope.lg_start_time, 'HH:mm a')) > 0
		# 		return moment.duration(moment.duration(moment($scope.lg_end_time, 'HH:mm a') - moment($scope.lg_start_time, 'HH:mm a')),"miliseconds").format("h [hrs] m [min]")
		# 	else
		# 		return '0 min'

		# $scope.example14settings = externalIdProp: ''
		# task.show(tId,pId).success (data)->
		# 	$scope.taskDetail = data.task
		# 	$scope.tsk1.completed = $scope.taskDetail[0]['completed']
		# 	$scope.logs = data.logs
		# 	$scope.billable = data.billable
		# 	$scope.total_task_billable_hours = data.total_task_billable_hours
		# 	$scope.total_task_non_billable_hours = data.total_task_non_billable_hours
		# 	$scope.total_task_minute=data.total_task_minute
		# 	$scope.total_task_hours = data.total_task_hours


		# 	$scope.loading = false
		# for Everything Tasks
		task.everythingLog().success (data)->
			$scope.everythingLogs = data
			$scope.logs_date = data

			$scope.loading = false
		# $scope.date_change =(enddate,startdate)->
		# 	if startdate == enddate
		# 		$scope.searchForm.end_date = ''



		$scope.submitSearch = (form)->
			$scope.loading = true
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true

			task.searchEverything($scope.searchForm).success (data)->
				$scope.everythingLogs = data
				$scope.logs_date = data
				$scope.loading = false





		#end for Everything Tasks


		task.getCat().success (data)->
			$scope.taskcategories = data
			$scope.loading = false
		$scope.Pro_Id = pId

		$scope.task_completed = (id,completed)->
			task.completed(id, completed).success (data)->
			if completed == true
				notify
					message: 'Task reopen'
					duration: 1500
					position: 'right'
			else
				notify
					message: 'Task completed'
					duration: 1500
					position: 'right'





		# $scope.showModal = (event) ->
		# 	$scope.task.category_id = event.target.id
		# 	angular.element('#addNewAppModal').modal('show')
		# 	task.get(pId).success (data)->
		# 		$scope.users = []
		# 		angular.forEach data.users, (value,key) ->
		# 			$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
		# 			return
		# 		$scope.loading = false
		# 	return


		$scope.showLogModal = (event,id) ->
			$scope.task_id = id

			angular.element('#logTimeModal').modal('show')
			return

		# $scope.calc_spent_time = (et,st)->
		# 	$scope.lg_start_time = st
		# 	$scope.lg_end_time = et
		# 	return moment.duration(moment.duration(moment($scope.lg_end_time, 'HH:mm a') - moment($scope.lg_start_time, 'HH:mm a')),"miliseconds").format("h [hrs] m [min]")

		$scope.cancelAll = ->
			angular.element('#addNewAppModal').modal('hide')
			$timeout (->
				$scope.submitted = false
				$scope.edit = false
				$scope.task = {}
			), 100
			return



		# $scope.clearAll = (form)->
		# 	$scope.options =
		# 		title: 'You have changes.'
		# 		message:'Are you sure you want to discard changes?'
		# 		input:false
		# 		label:''
		# 		value:''
		# 		values:false
		# 		buttons:[
		# 			{
		# 				label: 'Ok'
		# 				primary: true
		# 			}
		# 			{
		# 				label: 'Cancel'
		# 				cancel: true
		# 			}
		# 		]

		# 	if form.$dirty

		# 		prompt($scope.options).then( ->
		# 			angular.element('#addNewAppModal').modal('hide')
		# 			if $scope.edit ==  false
		# 				angular.element('.task-select .select2-container').select2('val','')
		# 			$scope.submitted = false
		# 			$scope.formError = 0
		# 			$scope.edit = false
		# 			$scope.task = {}

		# 		)
		# 	else
		# 		angular.element('#addNewAppModal').modal('hide')
		# 		angular.element('.task-select .select2-container').select2('val','')
		# 		$timeout (->
		# 			$scope.submitted = false
		# 			$scope.edit = false
		# 			$scope.task = {}
		# 			$scope.formError = 0
		# 			$scope.task.priority = "low"
		# 			), 100
		# 	return

		$scope.logCancel = ->
			angular.element('#logTimeModal').modal('hide')
			$timeout (->
				$scope.submitted = false
				$scope.edit = false
				$scope.logtime = {}
			), 100
			return



		$scope.logClearAll = (form)->
			$scope.optionsLog =
				title: 'You have changes.'
				message:'Are you sure you want to discard changes?'
				input:false
				label:''
				value:''
				values:false
				buttons:[
					{
						label: 'ok'
						primary: true
					}
					{
						label: 'Cancel'
						cancel: true
					}
				]

			if form.$dirty
				prompt($scope.optionsLog).then( ->
					angular.element('#logTimeModal').modal('hide')
					$scope.submitted = false
					$scope.edit = false
					$scope.logtime = {}

				)
			else
				angular.element('#logTimeModal').modal('hide')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.logtime = {}
					), 100
			return

		# $scope.submit = (form)->
		# 	$scope.loading = true
		# 	$scope.submitted = true
		# 	errors = form.$error
		# 	angular.forEach errors, (val)->
		# 		if angular.isArray(val)
		# 			$scope.formError += val.length
		# 		return
		# 	if form.$invalid
		# 		$scope.loading = false
		# 		return
		# 	else
		# 		$scope.loading = true

		# 	if $scope.edit == false
		# 		$scope.task.user_id = []
		# 		angular.forEach $scope.example14model, (value, key) ->
		# 			$scope.task.user_id.push(value.id)
		# 			return

		# 		$scope.task.project_id = pId

		# 		task.save($scope.task).success (data)->

		# 			$scope.submitted = false
		# 			$scope.task = {}
		# 			$scope.formError = 0
		# 			angular.element('#addNewAppModal').modal('hide')
		# 			notify
		# 				message: 'Task added successfully'
		# 				duration: 1500
		# 				position: 'right'

		# 			$scope.example14model.length = 0
		# 			task.get(pId).success (getData)->
		# 				$scope.tasks = getData.tasks
		# 				$scope.users = []
		# 				angular.forEach getData.users, (value,key) ->
		# 					$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
		# 					return
		# 				$scope.loading = false
		# 	else
		# 		$scope.task.user_id = []
		# 		angular.forEach $scope.example14model, (value, key) ->
		# 			$scope.task.user_id.push(value.id)
		# 			return
		# 		task.update($scope.task).success (data)->
		# 			$scope.submitted = false
		# 			$scope.edit = false
		# 			$scope.task = {}
		# 			$scope.formError = 0
		# 			angular.element('#addNewAppModal').modal('hide')
		# 			$timeout (->
		# 				notify
		# 					message: 'Task updated successfully'
		# 					duration: 1500
		# 					position: 'right'

		# 				$scope.example14model.length = 0
		# 				task.show(tId,pId).success (data)->
		# 					$scope.taskDetail = data.task
		# 				task.get(pId).success (getData)->
		# 					$scope.tasks = getData.tasks
		# 					$scope.users = []
		# 					angular.forEach getData.users, (value,key) ->
		# 						$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
		# 						return
		# 					$scope.loading = false
		# 			), 100

		$scope.secondsToTime = (seconds) ->
			seconds = Math.round(seconds)
			hours = Math.floor(seconds / (60 * 60))
			get_minutes = seconds % (60 * 60)
			minutes = Math.floor(get_minutes / 60)
			get_seconds = get_minutes % 60
			seconds = Math.ceil(get_seconds)

			hour_min_sec =
				'h': hours
				'm': minutes
				's': seconds
			hour_min_sec


		# angular.element('#addNewAppModal').on('hidden.bs.modal',(form) ->
		# 	$scope.clearAll(form)
		# 	$scope.modal_title = 'Add'
		# 	$scope.example14model.length = 0
		# 	$scope.formError = 0
		# 	$scope.task.priority = 'low'
		# 	angular.element("#addNewAppModal .my-tabs>li.active").removeClass("active")
		# 	angular.element('#default-home').addClass('active')
		# 	angular.element('#addNewAppModal .my-tabs>li a').attr('aria-expanded', false)
		# 	angular.element('#home').attr('aria-expanded',true)
		# 	angular.element('#addNewAppModal .tab-content>div').removeClass('active')
		# 	angular.element('#addNewAppModal .tab-content #home').addClass('active')
		# )

		angular.element('#logTimeModal').on('hidden.bs.modal',(form) ->
			$scope.logtime = {}
			$scope.modal_title = 'Add'
		)


		# $scope.toggleStatus = ->
		# 	task.update($scope.task.status).success (data)->
		# 		task.get(pId).success (getData)->
		# 			$scope.tasks = getData.tasks
		# 			$scope.users = getData.users

		$scope.submitLog = (form)->

			$scope.loading = true
			$scope.submitted = true
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true

			if $scope.edit == false
				if tId != 'Undefined'
					$scope.logtime.task_id = tId
					console.log 'task detail task_id'+$scope.logtime.task_id
				else
					$scope.logtime.task_id = $scope.task_id
					console.log $scope.logtime.task_id

				console.log $scope.logtime
				task.savelog($scope.logtime).success (data)->
					$scope.submitted = false
					$scope.logtime = {}
					$scope.loading = false
					angular.element('#logTimeModal').modal('hide')
					notify
						message: 'Logtime Added successfully'
						duration: 1500
						position: 'right'
					# this is for single task record fetch
					task.show(tId,pId).success (data)->
						console.log data
						$scope.taskDetail = data.task
						$scope.logs= data.logs
						$scope.billable = data.billable
						$scope.total_task_billable_hours = data.total_task_billable_hours
						$scope.total_task_non_billable_hours = data.total_task_non_billable_hours
						$scope.total_task_minute=data.total_task_minute
						$scope.total_task_hours = data.total_task_hours
						console.log $scope.total_task_hours

					# this is for all tasks record fetch
					task.get(pId).success (data)->
						$scope.tasks = data.tasks
						$scope.users = data.users
						$scope.loading = false
			else
				task.updatelog($scope.logtime).success (data)->
					$scope.submitted = false
					$scope.edit = false
					$scope.logtime = {}
					$scope.loading = false
					angular.element('#logTimeModal').modal('hide')
					$timeout (->
						notify
							message:'Logtime updated successfully'
							position:'right'
							duration: 1500

						task.show(tId,pId).success (data)->
							$scope.taskDetail = data.task
							$scope.logs= data.logs
							$scope.billable = data.billable
							$scope.total_task_billable_hours = data.total_task_billable_hours
							$scope.total_task_non_billable_hours = data.total_task_non_billable_hours
							$scope.total_task_minute=data.total_task_minute
							$scope.total_task_hours = data.total_task_hours
							# $scope.taskDetail = data.task
							# $scope.logs= data.logs
							# $scope.billable=data.billable
							# $scope.total_task_minute=data.total_task_minute
							$scope.loading = false
						task.everythingLog().success (data)->
							$scope.everythingLogs = data
							$scope.logs_date = data
							$scope.loading = false

					), 100

		# $scope.deleteTask = (id)->
		# 	$scope.loading = true
		# 	task.destroy(id).success (data)->
		# 		task.get(pId).success (getData)->
		# 			$scope.tasks = getData.tasks
		# 			$scope.users = []
		# 			angular.forEach getData.users, (value,key) ->
		# 				$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
		# 				return

		# 			$scope.loading = false
		# 			notify
		# 				message:'Task deleted successfully'
		# 				position:'right'
		# 				duration: 1500

		# $scope.editTask = (id)->
		# 	task.edit(id,pId).success (data)->
		# 		$scope.edit = true
		# 		if $scope.edit == true
		# 			$scope.modal_title = 'Edit'

		# 		$scope.task = data.task
		# 		$scope.task.priority = data.task.priority

		# 		$scope.example14model = []
		# 		angular.forEach data.task_users, (value,key) ->
		# 			$scope.example14model.push({id:value.user_id,label:value.fname+' '+value.lname})
		# 			return

		# 		angular.element('#addNewAppModal').modal('show')

		$scope.deleteLog = (id)->
			swal(
				title: 'Are you Sure?'
				text: 'You won\'t be able to revert this!'
				type: 'warning'
				timer: 7000
				showCancelButton: true
				).then((result)->
					if result.value
						$scope.loading = true
						task.destroylog(lid).success (data)->
							task.show(tId,pId).success (getData)->
								# $scope.taskDetail = data.task
								$scope.logs= getData.logs
								$scope.billable = getData.billable
								$scope.total_task_billable_hours = getData.total_task_billable_hours
								$scope.total_task_non_billable_hours = getData.total_task_non_billable_hours
								$scope.total_task_minute=getData.total_task_minute
								$scope.total_task_hours = getData.total_task_hours
								$scope.loading = false
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result.dismiss == swal.DismissReason.cancel
  						swal 'Cancelled', 'Your record is safe', 'info'
				)

		# $scope.deleteLog = (lid)->
		# 	$scope.loading = true
		# 	task.destroylog(lid).success (data)->
		# 		task.show(tId,pId).success (getData)->
		# 			# $scope.taskDetail = data.task
		# 			$scope.logs= getData.logs
		# 			$scope.billable = getData.billable
		# 			$scope.total_task_billable_hours = getData.total_task_billable_hours
		# 			$scope.total_task_non_billable_hours = getData.total_task_non_billable_hours
		# 			$scope.total_task_minute=getData.total_task_minute
		# 			$scope.total_task_hours = getData.total_task_hours
		# 			$scope.loading = false

		# 			notify
		# 				message:'Task deleted successfully'
		# 				position:'right'
		# 				duration: 1500


		$scope.editLog = (id)->
			$scope.task_id = id
			$scope.edit = true
			if $scope.edit == true
				$scope.modal_title = 'Save'
			angular.element('#logTimeModal').modal('show')
			task.editlog(id).success (data)->
				$scope.edit = true
				$scope.logtime = data
				# angular.element('#addNewAppModal').modal('show')

		# $scope.getUserName = (id)->
		# 	task.getName(id).success (data)->
		# 		return data.fname

		# $scope.getTaskName = (id)->
		# 	task.getTName(id).success (data)->
		# 		return data.name
		# $scope.changeBillable = (id,value)->
		# 	$scope.loading = true
		# 	task.changeBillabled(id,value).success (data)->
		# 		task.show(tId,pId).success (data)->
		# 			# $scope.taskDetail = data.task
		# 			# $scope.logs= data.logs
		# 			$scope.billable = data.billable
		# 			$scope.total_task_billable_hours = data.total_task_billable_hours
		# 			$scope.total_task_non_billable_hours = data.total_task_non_billable_hours
		# 			$scope.total_task_minute=data.total_task_minute
		# 			$scope.total_task_hours = data.total_task_hours
		# 			$scope.loading = false
		# 	return

