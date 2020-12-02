angular.module 'mis'

	.controller 'ProjectCategoryCtrl', ($scope,DTOptionsBuilder, DTColumnDefBuilder,projectCategory, prompt, $timeout,notify)->
		$scope.loading = true
		$scope.edit = false

		$scope.project_category = {}
		if $scope.edit == false
			$scope.modal_title = 'Add'

		# start For DataTable
		$scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ' '
			).withOption('responsive', true)

		$scope.dtColumnDefs = [
			DTColumnDefBuilder.newColumnDef(0)
			DTColumnDefBuilder.newColumnDef(1).notSortable()
		]

		projectCategory.get().success (data)->
			$scope.categories = data
			$scope.loading = false
			return
		# End




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
					$scope.project_category = {}
				)
			else
				angular.element('#addNewAppModal').modal('hide')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.project_category = {}
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
				projectCategory.save($scope.project_category).success (data)->
					$scope.loading = false
					$scope.submitted = false
					$scope.project_category = {}
					angular.element('#addNewAppModal').modal('hide')
					notify
						message: 'Added successfully.'
						duration: 1500
						position: 'right'

					projectCategory.get().success (getData)->
						$scope.categories = getData
						$scope.loading = false
			else
				projectCategory.update($scope.project_category).success (data)->
					$scope.loading = false
					$scope.submitted = false
					$scope.edit = false
					$scope.project_category = {}
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'
						projectCategory.get().success (getData)->
							$scope.categories = getData
							$scope.loading = false
					), 500


		angular.element('#addNewAppModal').on('hidden.bs.modal', ->
			$scope.project_category.name = ''
			$scope.modal_title = 'Add'
		)

		

		$scope.deleteCategory = (id)->
			swal(
				title: 'Are you Sure?'
				text: 'You won\'t be able to revert this!'
				type: 'warning'
				timer: 7000
				buttons: [true, "OK"]
				showCancelButton: true
				).then((result)->
					if result == true
						$scope.loading = true
						projectCategory.destroy(id).success (data)->
							projectCategory.get().success (getData)->
								$scope.categories = getData
								$scope.loading = false
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result == null
  						swal 'Cancelled', 'Your record is safe', 'info'
				)
		# $scope.deleteCategory = (id)->
		# 	$scope.loading = true
		# 	projectCategory.destroy(id).success (data)->
		# 		projectCategory.get().success (getData)->
		# 			$scope.categories = getData
		# 			$scope.loading = false
		# 		notify
		# 			message: 'Deleted successfully.'
		# 			duration: 1500
		# 			position: 'right'

		$scope.editCategory = (id)->
			projectCategory.edit(id).success (data)->
				$scope.edit = true

				if $scope.edit == true
					$scope.modal_title  = 'Save'
			

				$scope.project_category = {
					id: data.id
					name: data.name
				}
				angular.element('#addNewAppModal').modal('show')

		angular.element('#addNewAppModal').on 'hidden.bs.modal', ->
			$scope.clearAll($scope.project_category.name)
			return

		angular.element('#addNewAppModal').on 'shown.bs.modal', ->
			$('#appName').focus()
			return

