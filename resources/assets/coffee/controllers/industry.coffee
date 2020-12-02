angular.module 'mis'

	.controller 'IndustryCtrl', ($scope, Industry, DTOptionsBuilder, DTColumnDefBuilder, $interval, prompt, $timeout,notify)->
		$scope.loading = true
		$scope.currentPage = 1
		$scope.edit = false
		if $scope.edit == false
			$scope.modal_title = 'Add'




		$scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ' '
			).withOption('responsive', true)

		$scope.industry = {}

		$scope.dtColumnDefs = [
			DTColumnDefBuilder.newColumnDef(0)
			DTColumnDefBuilder.newColumnDef(1).notSortable()
		]


		Industry.get().success (data)->
			$scope.industries = data
			$scope.loading = false
			return

		angular.element('#addNewAppModal').on('hidden.bs.modal', ->
			$scope.industry = {}
			$scope.modal_title = 'Add'
		)


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
					$scope.industry = {}
				)
			else
				angular.element('#addNewAppModal').modal('hide')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.industry = {}
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
				Industry.save($scope.industry).success (data)->
					$scope.loading = false
					$scope.submitted = false
					$scope.industry = {}
					angular.element('#addNewAppModal').modal('hide')

					notify
						message: 'Added successfully.'
						duration: 1500
						position: 'right'

					Industry.get().success (getData)->
						$scope.industries = getData
						$scope.loading = false
			else
				Industry.update($scope.industry).success (data)->

					$scope.submitted = false
					$scope.edit = false
					$scope.industry = {}
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'

						Industry.get().success (getData)->
							$scope.industries = getData
							$scope.loading = false
					), 10


		$scope.deleteIndustry = (id)->
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
						Industry.destroy(id).success (data)->
							Industry.get().success (getData)->
								$scope.industries = getData
								$scope.loading = false
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result.dismiss == swal.DismissReason.cancel
  						swal 'Cancelled', 'Your record is safe', 'info'
				)

		# $scope.deleteIndustry = (id)->
		# 	$scope.loading = true
		# 	Industry.destroy(id).success (data)->
		# 		Industry.get().success (getData)->
		# 			$scope.industries = getData
		# 			$scope.loading = false
		# 		notify
		# 			message: 'Deleted successfully.'
		# 			duration: 1500
		# 			position: 'right'

		$scope.editIndustry = (id)->
			Industry.edit(id).success (data)->
				$scope.edit = true
				if $scope.edit == true
					$scope.modal_title = 'Save'
				$scope.industry = {
					id: data.id
					name: data.name
				}
				angular.element('#addNewAppModal').modal('show')


		angular.element('#addNewAppModal').on 'hidden.bs.modal', ->
			$scope.clearAll($scope.industry)
			return

		angular.element('#addNewAppModal').on 'shown.bs.modal', ->
			$('#appName').focus()
			return


