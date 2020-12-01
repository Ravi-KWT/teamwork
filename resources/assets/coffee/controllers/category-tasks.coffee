angular.module 'mis'
	.controller 'TaskCategoriesCtrl', ($scope, categoryTask, $timeout,DTOptionsBuilder,DTColumnDefBuilder,$window,notify, prompt)->
		$scope.loading = false
		$scope.formError = 0
		$scope.currentDate = new Date()

		currentUrl = $window.location.href
		cId = currentUrl.split('/')[4]||"Undefined"



		$scope.example14model = [

		]

		$scope.example14settings = externalIdProp: ''

		$scope.showModal = (event) ->
			angular.element('#addNewAppModal').modal('show')
			categoryTask.get(cId).success (data)->
				$scope.users = []
				if(data.loginUser.user_id !=0)
					if data.loginUser.lname != null
						$scope.example14model.push (id:data.loginUser.user_id,label:data.loginUser.fname+" "+data.loginUser.lname )
					else
						$scope.example14model.push (id:data.loginUser.user_id,label:data.loginUser.fname)
				angular.forEach data.users, (value,key) ->
					if(value.id != 0)
						if value.people.lname != null
							$scope.users.push (id:value.id,label:value.people.fname+" "+value.people.lname )
						else
							$scope.users.push (id:value.id,label:value.people.fname)
					return
				$scope.loading = false
			return

		$scope.cancelAll = ->
			angular.element('#addNewAppModal').modal('hide')
			$timeout (->
				$scope.submitted = false
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
					angular.element('.task-select .select2-container').select2('val','')
					$scope.submitted = false
					$scope.formError = 0
					$scope.task = {}
				)
			else
				angular.element('#addNewAppModal').modal('hide')
				angular.element('.task-select .select2-container').select2('val','')
				$timeout (->
					$scope.submitted = false
					$scope.task = {}
					$scope.formError = 0
					$scope.task.priority = "low"
					), 100
			return

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

			$scope.task.user_id = []
			angular.forEach $scope.example14model, (value, key) ->
				$scope.task.user_id.push(value.id)
				return

			categoryTask.save($scope.task).success (data)->
				angular.element('#addNewAppModal').modal('hide')
				notify
					message: 'Task added successfully'
					duration: 1500
					position: 'right'
				$scope.example14model.length = 0
				$scope.loading = false
				$window.location.href = currentUrl;
			
		angular.element('#addNewAppModal').on('hidden.bs.modal',(form) ->
			$scope.clearAll(form)
			$scope.modal_title = 'Add'
			$scope.example14model.length = 0
			$scope.formError = 0
			$scope.task.priority = 'low'
			angular.element("#addNewAppModal .my-tabs>li.active").removeClass("active")
			angular.element('#default-home').addClass('active')
			angular.element('#addNewAppModal .my-tabs>li a').attr('aria-expanded', false)
			angular.element('#home').attr('aria-expanded',true)
			angular.element('#addNewAppModal .tab-content>div').removeClass('active')
			angular.element('#addNewAppModal .tab-content #home').addClass('active')
		)

