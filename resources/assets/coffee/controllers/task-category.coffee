angular.module 'mis'

.controller 'TaskCategoryCtrl', ($scope, taskCategory, DTOptionsBuilder, DTColumnDefBuilder, $interval, $timeout,prompt,notify)->
	$scope.loading = true
	$scope.currentPage = 1
	$scope.edit = false
	if $scope.edit == false
		$scope.modal_title = 'Add'


	# start For DataTable
	$scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ' '
			).withOption('responsive', true)

	$scope.dtColumnDefs = [
		DTColumnDefBuilder.newColumnDef(0)
		DTColumnDefBuilder.newColumnDef(1)
		DTColumnDefBuilder.newColumnDef(2).notSortable()
	]

	taskCategory.get().success (data)->
		$scope.task_categories = data.categories
		$scope.projects = data.projects
		$scope.loading = false
		return
	# end

	angular.element('#addNewAppModal').on('hidden.bs.modal', ->
		$scope.project_category = {}
		$scope.modal_title = 'Add'
	)

	$scope.cancelAll = ->
		angular.element('#addNewAppModal').modal('hide')
		$timeout (->
			$scope.submitted = false
			$scope.edit = false
			$scope.task_category = {}
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
					$scope.submitted = false
					$scope.edit = false
					$scope.task_category = {}
				)
			else
				angular.element('#addNewAppModal').modal('hide')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.task_category = {}
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
				taskCategory.save($scope.task_category).success (data)->
					$scope.submitted = false
					$scope.task_category = {}
					angular.element('#addNewAppModal').modal('hide')
					notify
						message: 'Added successfully.'
						duration: 1500
						position: 'right'


					taskCategory.get().success (getData)->
						$scope.task_categories = getData.categories
						$scope.projects = getData.projects
						$scope.loading = false
			else
				taskCategory.update($scope.task_category).success (data)->
					$scope.submitted = false
					$scope.edit = false
					$scope.task_category = {}
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'

						taskCategory.get().success (getData)->
							$scope.task_categories = getData.categories
							$scope.projects = getData.projects
							$scope.loading = false
					), 500


	$scope.deleteCategory = (id)->
		swal(
			title: 'Are you Sure?'
			text: 'You won\'t be able to revert this!'
			type: 'warning'
			timer: 7000
			showCancelButton: true
			).then((result)->
				if result.value
					$scope.loading = true
					taskCategory.destroy(id).success (data)->
						taskCategory.get().success (getData)->
							$scope.task_categories = getData.categories
							$scope.projects = getData.projects
							$scope.loading = false
					swal("Deleted!", "Your record has been deleted.", "success");
				else if result.dismiss == swal.DismissReason.cancel
						swal 'Cancelled', 'Your record is safe', 'info'
			)

	# $scope.deleteCategory = (id)->
	# 	$scope.loading = true
	# 	taskCategory.destroy(id).success (data)->
	# 		taskCategory.get().success (getData)->
	# 			$scope.task_categories = getData.categories
	# 			$scope.projects = getData.projects
	# 			$scope.loading = false
	# 		notify
	# 			message: 'Deleted successfully.'
	# 			duration: 1500
	# 			position: 'right'

	$scope.editCategory = (id)->
		taskCategory.edit(id).success (data)->
			$scope.edit = true
			if $scope.edit == true
				$scope.modal_title = 'Save'
			$scope.task_category = data
			angular.element('#addNewAppModal').modal('show')

	angular.element('#addNewAppModal').on 'hidden.bs.modal', (form)->
		$scope.clearAll(form)
		return

	angular.element('#addNewAppModal').on 'shown.bs.modal', ->
		$('#appName').focus()
		return