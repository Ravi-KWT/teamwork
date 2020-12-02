angular.module 'mis'
	.controller 'TasksCtrl', ($scope, task,$timeout,DTOptionsBuilder,DTColumnDefBuilder,$window,notify, prompt)->
		$scope.loading = true
		$scope.currentPage = 1
		$scope.edit = false
		$scope.formError = 0
		$scope.tsk_completed = ''
		$scope.currentDate = new Date()
		$scope.minDate = new Date()
		$scope.single = false



		$scope.substractDate = (date)->
			temp = new Date(date)
			$scope.minDate = new Date(temp.getFullYear(), temp.getMonth(), temp.getDate())
		if $scope.edit == false
			$scope.modal_title = 'Add'


		$scope.dtOptions = DTOptionsBuilder.newOptions().withOption('stateLoadParams',(setting,data)->
				data.search.search = ' '
			).withOption('responsive', true).withOption('order',[0,'desc']).withOption('lengthChange', false).withOption('paging', false)

		$scope.dtColumnDefs = [
			DTColumnDefBuilder.newColumnDef(6).notSortable()
			DTColumnDefBuilder.newColumnDef(7).notSortable()
		]

		currentUrl = $window.location.href
		pId = currentUrl.split('/')[4]||"Undefined"
		tId = currentUrl.split('/')[6]||"Undefined"

		task.get(pId).success (data)->
			$scope.tasks = data.tasks
			$scope.taskcategories = data.taskcategories
			$scope.users = []
			
			angular.forEach data.users, (value,key) ->
				if(value.id !=0)
					if value.people.lname != null
						$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
					else
						$scope.users.push (id:value.id,label:value.people.fname)
				return
			$scope.loading = false

		$scope.example14model = [

		]

		# $scope.exportData = ->
		#   blob = new Blob([ document.getElementById('exportable').innerHTML ], type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8')
		#   saveAs blob, 'Report.xls'
		#   return

		$scope.calc_spent_time = (et,st)->
			$scope.lg_start_time = st
			$scope.lg_end_time = et
			if moment.duration(moment($scope.lg_end_time, 'HH:mm a') - moment($scope.lg_start_time, 'HH:mm a')) > 0
				return moment.duration(moment.duration(moment($scope.lg_end_time, 'HH:mm a') - moment($scope.lg_start_time, 'HH:mm a')),"miliseconds").format("h [hrs] m [min]")
			else
				return '0 min'



		$scope.example14settings = 
			externalIdProp: ''
			
		task.show(tId,pId).success (data)->
			$scope.taskDetail = data.task
			$scope.tsk1.completed = $scope.taskDetail.completed
			$scope.logs = data.logs
			$scope.billable = data.billable
			$scope.total_task_billable_hours = data.total_task_billable_hours
			$scope.total_task_non_billable_hours = data.total_task_non_billable_hours
			$scope.total_task_minute=data.total_task_minute
			$scope.total_task_hours = data.total_task_hours
			$scope.loading = false

		# for Everything Tasks
		# task.everythingLog().success (data)->
		# 	$scope.start_date = new Date

		# 	$scope.everythingLogs = data.logs
		# 	$scope.projectsList = data.projectsList
		# 	$scope.usersList = data.users
		# 	$scope.filterHours = data.hours
		# 	$scope.minDate = new Date
		# 	$scope.logs_date = data.logs

		# 	$scope.loading = false


		# $scope.submitSearch = (form)->
		# 	$scope.loading = true
		# 	if form.$invalid
		# 		$scope.loading = false
		# 		return
		# 	else
		# 		$scope.loading = true

		# 	task.searchEverything($scope.searchForm).success (data)->
		# 		$scope.everythingLogs = data[0]
		# 		$scope.logs_date = data[0]
		# 		$scope.filterHours = data[1]
		# 		$scope.loading = false

		#end for Everything Tasks
		# task.getCat().success (data)->
		# 	$scope.taskcategories = data
		# 	$scope.loading = false
		$scope.Pro_Id = pId

		# $scope.exportToExcel = (data1)->
		# 	task.exportLogsToExcel(data1).success (data, status, headers, config) ->
		# 		if status > 400 and status < 600
		# 			console.log 'export status danger'
		# 		else
		# 			anchor = angular.element('<a/>')
		# 			anchor.css("display", 'none')
		# 			# Make sure it's not visible
		# 			angular.element(document.body).append(anchor)
		# 			# Attach to document
		# 			anchor.attr(
		# 				href: 'data:attachment/xls;charset=system,' + encodeURI(data)
		# 				target: '_blank'
		# 				download: 'Report.xls')[0].click()
		# 		return
		# 		notify
		# 			message: 'Report generated'
		# 			duration: 1500
		# 			position: 'right'


		$scope.task_completed = (id,completed)->
			task.completed(id, completed).success (data)->
			if completed == true
				notify
					message: 'Task completed'
					duration: 1500
					position: 'right'
				task.get($scope.Pro_Id).success (data)->
					$scope.tasks = data.tasks
					$scope.taskcategories = data.taskcategories
					$scope.users = []
					angular.forEach data.users, (value,key) ->
						if(value.id !=0)
							if value.people.lname != null
								$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
							else
								$scope.users.push (id:value.id,label:value.people.fname)
						return
			else
				task.get($scope.Pro_Id).success (data)->
					$scope.tasks = data.tasks
					$scope.taskcategories = data.taskcategories
					$scope.users = []
					angular.forEach data.users, (value,key) ->
						if(value.id !=0)
							if value.people.lname != null
								$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
							else
								$scope.users.push (id:value.id,label:value.people.fname)
						return
				notify
					message: 'Task reopen'
					duration: 1500
					position: 'right'
			
				

		$scope.showModal = (event) ->
			
			$scope.task.category_id = event.target.id
			angular.element('#addNewAppModal').modal('show')
			task.get(pId).success (data)->
				$scope.users = []
				if(data.loginUser.user_id !=0)
					if data.loginUser.lname != null
						$scope.example14model.push (id:data.loginUser.user_id,label:data.loginUser.fname+" "+data.loginUser.lname )
					else
						$scope.example14model.push (id:data.loginUser.user_id,label:data.loginUser.fname)

				console.log data.loginUser.user_id
				angular.forEach data.users, (value,key) ->
					if(value.id !=0)
						if value.people.lname != null
							$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
						else
							$scope.users.push (id:value.id,label:value.people.fname)
					return
				$scope.loading = false
			return

		$scope.showTaskCategoryModal = (event) ->
			
			angular.element('#addTaskCategoryModal').modal('show')
			$scope.loading = false
			$scope.task_category_name = ''


		$scope.showLogModal = (event,id) ->
			$scope.task_id = id
			$scope.project_id = pId
			$scope.logtimeInit()
			$scope.logtime.billable = null
			# $scope.logtime.date = moment($scope.currentDate.toDateString()).format('DD-MM-YYYY')
			# $scope.logtime.start_time = moment($scope.currentDate).format('hh:mm A')
			# $scope.logtime.end_time = moment($scope.currentDate).format('hh:mm A')
			# angular.element('#timepicker_1').timepicker
			# 	minuteStep: 5
			# 	snapToStep: true
			# 	defaultTime: $scope.logtime.start_time
			# 	forceRoundTime: true
			# angular.element('#timepicker_2').timepicker
			# 	minuteStep: 5
			# 	snapToStep: true
			# 	defaultTime: $scope.logtime.end_time
			# 	forceRoundTime: true
			angular.element('#logTimeModal').modal('show')

			return


		$scope.startTimer = (task_id,user_id,project_id) ->
			$scope.task_id = task_id
			$scope.project_id = project_id
			$scope.user_id = user_id
			task.startLogTimer($scope.task_id,$scope.user_id,$scope.project_id).success (data)->
				console.log(data)
				notify
							message: 'Log Timer start successfully.'
							duration: 1500
							position: 'right'
			return

		# pause log timer
		$scope.pauseTimer = (task_id,user_id,project_id,timer_id) ->
			$scope.task_id = task_id
			$scope.project_id = project_id
			$scope.user_id = user_id
			$scope.timer_id = timer_id
			task.pauseLogTimer($scope.task_id,$scope.user_id,$scope.project_id,$scope.timer_id).success (data)->
				console.log(data)
				notify
							message: 'Log Timer paused successfully.'
							duration: 1500
							position: 'right'
			return

		$scope.cancelAll = ->
			angular.element('#addNewAppModal').modal('hide')
			$timeout (->
				$scope.submitted = false
				$scope.edit = false
				$scope.task = {}
			), 100
			return
		$scope.clearAll = (form)->
			$scope.options =
				title: 'You have changes.'
				message:'Are you sure you want to discard changes?'
				input:false
				label:''
				value:''
				values:false
				buttons:[
					{
						label: 'Ok'
						primary: true
					}
					{
						label: 'Cancel'
						cancel: true
					}
				]

			if form.$dirty

				prompt($scope.options).then( ->
					angular.element('#addNewAppModal').modal('hide')
					if $scope.edit ==  false
						angular.element('.task-select .select2-container').select2('val','')
					$scope.submitted = false
					$scope.formError = 0
					$scope.edit = false
					$scope.task = {}

				)
			else
				angular.element('#addNewAppModal').modal('hide')
				angular.element('.task-select .select2-container').select2('val','')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.task = {}
					$scope.formError = 0
					$scope.task.priority = "medium"
					), 100
			return

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
				)
			else
				angular.element('#logTimeModal').modal('hide')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.logtimeInit()
					), 500
			return
		$scope.submitTaskCategory = (form)->
				$scope.loading = true
				$scope.submitted = true
				errors = form.$error
				angular.forEach errors, (val)->
					if angular.isArray(val)
						$scope.formError += val.length
					return
				if form.$invalid
					$scope.loading = false
					return
				else
					$scope.loading = true
					$scope.task_category = {}
					$scope.task_category.name = $scope.task_category_name
					$scope.task_category.project_id = $scope.Pro_Id
					
				
					task.saveTaskCategory($scope.task_category).success (data)->
						$scope.submitted = false
						$scope.task_category = {}
						angular.element('#addTaskCategoryModal').modal('hide')
						notify
							message: 'Added successfully.'
							duration: 1500
							position: 'right'

						task.get(pId).success (response)->
							$scope.tasks = response.tasks
							$scope.taskcategories = response.taskcategories
							$scope.users = []
							angular.forEach response.users, (value,key) ->
								if value.id != 0
									if value.people.lname != null
										$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
									else
										$scope.users.push (id:value.id,label:value.people.fname)
								return
							$scope.loading = false
						
				

		$scope.submit = (form)->
			$scope.loading = true
			$scope.submitted = true
			errors = form.$error
			angular.forEach errors, (val)->
				if angular.isArray(val)
					$scope.formError += val.length
				return
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true

			if $scope.edit == false
				$scope.task.user_id = []
				angular.forEach $scope.example14model, (value, key) ->
					$scope.task.user_id.push(value.id)
					return

				$scope.task.project_id = pId

				task.save($scope.task).success (data)->

					$scope.submitted = false
					$scope.task = {}
					$scope.formError = 0
					angular.element('#addNewAppModal').modal('hide')
					notify
						message: 'Task added successfully'
						duration: 1500
						position: 'right'

					$scope.example14model.length = 0
					task.get(pId).success (getData)->
						$scope.tasks = getData.tasks
						$scope.users = []
						angular.forEach getData.users, (value,key) ->
							if value.id != 0
								if value.people.lname != null
									$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
								else
									$scope.users.push (id:value.id,label:value.people.fname)
							return
						$scope.loading = false
			else
				$scope.task.user_id = []
				angular.forEach $scope.example14model, (value, key) ->
					$scope.task.user_id.push(value.id)
					return
				task.update($scope.task).success (data)->
					$scope.submitted = false
					$scope.edit = false
					window.location.href = '/projects/'+pId+'/tasks'
					$scope.task = {}
					$scope.formError = 0
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: 'Task updated successfully'
							duration: 1500
							position: 'right'

						$scope.example14model.length = 0
						task.show(tId,pId).success (data)->
							$scope.taskDetail = data.task
						task.get(pId).success (getData)->
							$scope.tasks = getData.tasks
							$scope.users = []
							angular.forEach getData.users, (value,key) ->
								if value.id != 0
									if value.people.lname != null
										$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
									else
										$scope.users.push (id:value.id,label:value.people.fname)
								return
							$scope.loading = false
					), 100

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

		angular.element('#addNewAppModal').on('hidden.bs.modal',(form) ->
			$scope.clearAll(form)
			$scope.modal_title = 'Add'
			$scope.example14model.length = 0
			$scope.formError = 0
			$scope.task.priority = 'medium'
			angular.element("#addNewAppModal .my-tabs>li.active").removeClass("active")
			angular.element('#default-home').addClass('active')
			angular.element('#addNewAppModal .my-tabs>li a').attr('aria-expanded', false)
			angular.element('#home').attr('aria-expanded',true)
			angular.element('#addNewAppModal .tab-content>div').removeClass('active')
			angular.element('#addNewAppModal .tab-content #home').addClass('active')
		)

		angular.element('#logTimeModal').on('hidden.bs.modal',(form) ->
			$scope.logtimeInit()
			$scope.modal_title = 'Add'
		)
		angular.element('#logTimeModal').on('shown.bs.modal',(form) ->
			$scope.logtimeInit()
		)	
		$scope.logtimeInit = ()->
			if $scope.logtime.date
				$scope.logtime.date 
			else
				$scope.logtime.date = moment($scope.currentDate.toDateString()).format('DD-MM-YYYY')

			if $scope.logtime.description
				$scope.logtime.discription 
			else
				$scope.logtime.discription = ''
			$scope.logtime.billable = null
			$scope.Logtime.billable.error = ''
			current_time = ''
			current_time2 = ''
			d = ''
			d = $scope.currentDate
			if $scope.logtime.start_time
				current_time = moment($scope.logtime.start_time,'hh::mm A').format('hh:mm A')
			else   
				current_time = moment($scope.currentDate,'hh::mm A').format('hh:mm A')
	 		angular.element('#timepicker_1').timepicker
		 		minuteStep:1
		 		snapToStep:true
		 		defaultTime:current_time
		 		forceRoundTime: true 


			if $scope.logtime.end_time
				current_time2 = moment($scope.logtime.end_time,'hh::mm A').format('hh:mm A')
			else   
				current_time2 = moment($scope.currentDate,'hh::mm A').format('hh:mm A')
	 		angular.element('#timepicker_2').timepicker
		 		minuteStep:1
		 		snapToStep:true
		 		defaultTime:current_time2
		 		forceRoundTime: true 



		$scope.toggleStatus = ->
			task.update($scope.task.status).success (data)->
				task.get(pId).success (getData)->
					$scope.tasks = getData.tasks
					$scope.users = getData.users

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
					$scope.logtime.project_id = $scope.project_id
				else
					$scope.logtime.task_id = $scope.task_id
					$scope.logtime.project_id = $scope.project_id

				task.savelog($scope.logtime,pId).success (data)->
					if data.success == true

						$scope.submitted = false
						$scope.logtimeInit()
						$scope.logtime.description = ''


						$scope.loading = false
						angular.element('#logTimeModal').modal('hide')
						notify
							message: 'Logtime Added successfully'
							duration: 1500
							position: 'right'
						# this is for single task record fetch
						task.show(tId,pId).success (data)->

							$scope.taskDetail = data.task
							$scope.logs= data.logs
							$scope.billable = data.billable
							$scope.total_task_billable_hours = data.total_task_billable_hours
							$scope.total_task_non_billable_hours = data.total_task_non_billable_hours
							$scope.total_task_minute=data.total_task_minute
							$scope.total_task_hours = data.total_task_hours

						# this is for all tasks record fetch
						task.get(pId).success (data)->
							$scope.tasks = data.tasks
							$scope.users = data.users

							$scope.loading = false
					else
						$scope.loading = false
						$scope.Logtime.billable.error = data.errors.billable[0]
						console.log(data.errors.billable[0]);
			else
				task.updatelog($scope.logtime).success (data)->
					if data.success == true
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
								$scope.loading = false
							# task.everythingLog().success (data)->
							# 	$scope.everythingLogs = data
							# 	$scope.logs_date = data
							# 	$scope.loading = false

						), 100
					else
						$scope.loading = false
						$scope.Logtime.billable.error = data.errors.billable[0]

		$scope.deleteTask = (id)->
			console.log('hi');
			console.log(id);
			$scope.loading = false
			# (swal) ->
			swal(
				title: 'Are you Sure?'
				text: 'You won\'t be able to revert this!'
				type: 'warning'
				buttons: [true, "OK"]
				timer: 7000
				showCancelButton: true
				# confirmButtonText: 'Yes, delete it!'
				# cancelButtonText: 'No, cancel!'
				).then((result)->
					console.log(pId)
					if result == true
						$scope.loading = true
						task.destroy(pId,id).success (data)->
							task.get(pId).success (getData)->
								$scope.loading = false
								$scope.tasks = getData.tasks
								$scope.users = []
								angular.forEach getData.users, (value,key) ->
									if(value.id !=0)
										if value.people.lname != null
											$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
										else
											$scope.users.push (id:value.id,label:value.people.fname)
									return

							if $scope.single
								window.location.href = '/projects/'+pId+'/tasks'
						if $scope.single == false	
							$scope.loading = false	
							swal("Deleted!", "Your record has been deleted.", "success");
							window.location.href = '/projects/'+pId+'/tasks'

					else if result.dismiss == swal.DismissReason.cancel
  						swal 'Cancelled', 'Your record is safe', 'info'
				)
				
		$scope.deleteSingleTask = (id)->
			$scope.single = true
			$scope.loading = true
			$scope.deleteTask(id)

		$scope.editTask = (id)->
			task.edit(id,pId).success (data)->
				$scope.edit = true
				if $scope.edit == true
					$scope.modal_title = 'Save'

				$scope.task = data.task
				$scope.users = []
				angular.forEach data.allUsers, (value,key) ->
					if(value.id !=0)
						if value.people.lname != null
							$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
						else
							$scope.users.push (id:value.id,label:value.people.fname)
					return
				$scope.task.priority = data.task.priority

				$scope.example14model = []
				angular.forEach data.task_users, (value,key) ->
					if(value.id !=0)
						if value.lname != null
							$scope.example14model.push({id:value.user_id,label:value.fname+' '+value.lname})
						else
							$scope.example14model.push({id:value.user_id,label:value.fname})
					return
				$scope.Logtime.billable.error = false
				angular.element('#addNewAppModal').modal('show')

		$scope.deleteLog = (lid)->
			$scope.loading = false
			# (swal) ->
			swal(
				title: 'Are you Sure?'
				text: 'You won\'t be able to revert this!'
				type: 'warning'
				buttons: [true, "OK"]
				timer: 7000
				showCancelButton: true
				).then((result)->
					console.log(result)
					if result == true
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
					else if result == null
  						swal 'Cancelled', 'Your record is safe', 'info'
				)

		$scope.editLog = (id)->
			$scope.logtimeInit()
			$scope.task_id = id
			$scope.edit = true
			if $scope.edit == true
				$scope.modal_title = 'Save'
			angular.element('#logTimeModal').modal('show')
			task.editlog(id).success (data)->
				$scope.edit = true
				$scope.logtime = data.logtime
				$scope.username= data.username
				# angular.element('#addNewAppModal').modal('show')

		$scope.getUserName = (id)->
			task.getName(id).success (data)->
				return data.fname

		$scope.getTaskName = (id)->
			task.getTName(id).success (data)->
				return data.name
		$scope.changeBillable = (id,value)->
			$scope.loading = true
			task.changeBillabled(id,value).success (data)->
				task.show(tId,pId).success (data)->
					# $scope.taskDetail = data.task
					# $scope.logs= data.logs
					$scope.billable = data.billable
					$scope.total_task_billable_hours = data.total_task_billable_hours
					$scope.total_task_non_billable_hours = data.total_task_non_billable_hours
					$scope.total_task_minute=data.total_task_minute
					$scope.total_task_hours = data.total_task_hours
					$scope.loading = false
			return
