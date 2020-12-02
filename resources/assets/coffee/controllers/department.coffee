	angular.module 'mis'

	.controller 'DepartmentCtrl', ($resource,$scope, Department, DTOptionsBuilder,DTColumnDefBuilder,prompt,$timeout,notify,$http)->
		$scope.loading = true
		$scope.currentPage = 1
		$scope.edit = false
		if $scope.edit == false
			$scope.modal_title = 'Add'

		$scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ' '
			).withOption('responsive', true)

		$scope.department = {}
		$scope.dtColumnDefs = [
			DTColumnDefBuilder.newColumnDef(0)
			DTColumnDefBuilder.newColumnDef(1).notSortable()
		]


		Department.get().success (data)->
			$scope.departments = data
			$scope.loading = false
			return
		$scope.clearForm = ()->
			$scope.department = {}
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
					$scope.submitted = false
					$scope.edit = false
					$scope.department = {}
				)
			else
				angular.element('#addNewAppModal').modal('hide')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.department = {}
					), 100
			return

		angular.element('#addNewAppModal').on('hidden.bs.modal', ->
			$scope.department = {}
			$scope.modal_title = 'Add'
		)




		$scope.submit = (form)->
			$scope.loading = true
			$scope.submitted = true
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true

			if $scope.edit == false
				Department.save($scope.department).success (data)->
					$scope.loading = false
					$scope.submitted = false
					$scope.department = {}
					angular.element('#addNewAppModal').modal('hide')
					notify
						message: 'Added successfully.'
						duration: 1500
						position: 'right'

					Department.get().success (getData)->
						$scope.departments = getData
						$scope.loading = false

			else
				Department.update($scope.department).success (data)->
					$scope.submitted = false
					$scope.edit = false
					$scope.department = {}
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'

						Department.get().success (getData)->
							$scope.departments = getData
							$scope.loading = false
					), 500



		$scope.deleteDepartment = (id)->
			swal(
				title: 'Are you Sure?'
				text: 'You won\'t be able to revert this!'
				type: 'warning'
				buttons: [true, "OK"]
				timer: 7000
				showCancelButton: true
				).then((result)->
					if result == true
						$scope.loading = true
						Department.destroy(id).success (data)->
							Department.get().success (getData)->
								$scope.departments = getData
								$scope.loading = false
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result == null
  						swal 'Cancelled', 'Your record is safe', 'info'
				)


		# $scope.deleteDepartment = (id)->
		# 	$scope.loading = true
		# 	Department.destroy(id).success (data)->
		# 		notify
		# 			message: 'Deleted successfully.'
		# 			duration: 1500
		# 			position: 'right'
				# Department.get().success (getData)->
				# 	$scope.departments = getData
				# 	$scope.loading = false

		$scope.editDepartment = (id)->
			Department.edit(id).success (data)->
				$scope.edit = true
				if $scope.edit
					$scope.modal_title = 'Save'
				$scope.department = {
					id: data.id
					name: data.name
				}
				angular.element('#addNewAppModal').modal('show')

		angular.element('#addNewAppModal').on 'hidden.bs.modal', ->
			$scope.clearAll($scope.department)
			return

		angular.element('#addNewAppModal').on 'shown.bs.modal', ->
			$('#appName').focus()
			return
