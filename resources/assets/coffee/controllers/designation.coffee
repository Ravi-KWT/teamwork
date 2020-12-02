angular.module 'mis'

	.controller 'DesignationCtrl', ($resource,$scope, Designation,DTColumnDefBuilder, DTOptionsBuilder,prompt, $timeout,notify,$interval)->
		$scope.loading = true
		$scope.currentPage = 1
		$scope.edit = false
		if $scope.edit == false
			$scope.modal_title = 'Add'

		$scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ' '
			).withOption('responsive', true)

		$scope.dtColumnDefs = [
			DTColumnDefBuilder.newColumnDef(0)
			DTColumnDefBuilder.newColumnDef(1).notSortable()
		]

		Designation.get().success (data)->
			$scope.designations = data
			$scope.loading = false
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
					$scope.designation = {}
				)
			else
				angular.element('#addNewAppModal').modal('hide')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.designation = {}
					), 100
			return

		angular.element('#addNewAppModal').on('hidden.bs.modal', ->
			$scope.designation = {}
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
				Designation.save($scope.designation).success (data)->

					$scope.loading = false
					$scope.submitted = false
					$scope.designation = {}
					angular.element('#addNewAppModal').modal('hide')
					notify
						message: 'Added successfully.'
						duration: 1500
						position: 'right'
					Designation.get().success (data)->
						$scope.designations = data


			else
				Designation.update($scope.designation).success (data)->
					$scope.submitted = false
					$scope.edit = false
					$scope.designation = {}
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'

						Designation.get().success (getData)->
							$scope.designations = getData
							$scope.loading = false
					), 500


		
		$scope.deleteDesignation = (id)->
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
						Designation.destroy(id).success (data)->
							Designation.get().success (getData)->
								$scope.designations = getData
								$scope.loading = false
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result.dismiss == swal.DismissReason.cancel
  						swal 'Cancelled', 'Your record is safe', 'info'
				)

		# $scope.deleteDesignation = (id)->
		# 	$scope.loading = true
		# 	Designation.destroy(id).success (data)->
		# 		Designation.get().success (getData)->
		# 			$scope.designations = getData
		# 			$scope.loading = false
		# 		notify
		# 			message: 'Deleted successfully.'
		# 			duration: 1500
		# 			position: 'right'

		$scope.editDesignation = (id)->
			Designation.edit(id).success (data)->
				$scope.edit = true
				if $scope.edit == true
					$scope.modal_title = 'Save'
				$scope.designation = {
					id: data.id
					name: data.name
				}
				angular.element('#addNewAppModal').modal('show')

		angular.element('#addNewAppModal').on 'hidden.bs.modal', ->
			$scope.clearAll($scope.designation)
			return

		angular.element('#addNewAppModal').on 'shown.bs.modal', ->
			$('#appName').focus()
			return