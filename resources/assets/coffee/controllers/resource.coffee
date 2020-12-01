	angular.module 'mis'

	.controller 'ResourceCtrl', ($resource,$scope, Resource, DTOptionsBuilder,DTColumnDefBuilder,prompt,$timeout,notify,$http)->
		$scope.loading = true
		$scope.currentPage = 1
		$scope.edit = false
		if $scope.edit == false
			$scope.modal_title = 'Add'

		$scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ' '
			).withOption('responsive', true)

		$scope.resouce = {}
		$scope.dtColumnDefs = [
			DTColumnDefBuilder.newColumnDef(0)
			DTColumnDefBuilder.newColumnDef(1).notSortable()
		]
		
		# $scope.value = 100
		formatToPercentage = (value) ->
  			value + '%'
		$scope.slider =
			options:
				ceil: 100
				floor: 0
				step:10
				translate: formatToPercentage
				showSelectionBar: true
				showTicks: true
				getTickColor: (value) ->
					if value < 50
						return 'red'
					if value < 70
						return 'orange'
					if value < 100
						return 'yellow'
					'#2AE02A'

		#Resource.get().success (data)->
		#	$scope.resouces = data
		#	$scope.loading = false
		#	return
		$scope.clearForm = ()->
			$scope.resouce = {}
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
					$scope.resouce = {}
				)
			else
				angular.element('#addNewAppModal').modal('hide')
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.resouce = {}
					), 100
			return

		angular.element('#addNewAppModal').on('hidden.bs.modal', ->
			$scope.resouce = {}
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
				Resource.save($scope.resouce).success (data)->
					$scope.loading = false
					$scope.submitted = false
					$scope.resouce = {}
					angular.element('#addNewAppModal').modal('hide')
					notify
						message: 'Added successfully.'
						duration: 1500
						position: 'right'

					Resource.get().success (getData)->
						$scope.resouces = getData
						$scope.loading = false

			else
				Resource.update($scope.resouce).success (data)->
					$scope.submitted = false
					$scope.edit = false
					$scope.resouce = {}
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'

						Resource.get().success (getData)->
							$scope.resouces = getData
							$scope.loading = false
					), 500



		$scope.deleteResource = (id)->
			$scope.loading = true
			Resource.destroy(id).success (data)->
				notify
					message: 'Deleted successfully.'
					duration: 1500
					position: 'right'
				Resource.get().success (getData)->
					$scope.resouces = getData
					$scope.loading = false

		$scope.editResource = (id)->
			Resource.edit(id).success (data)->
				$scope.edit = true
				if $scope.edit
					$scope.modal_title = 'Save'
				$scope.resouce = {
					id: data.id
					name: data.name
				}
				angular.element('#addNewAppModal').modal('show')

		angular.element('#addNewAppModal').on 'hidden.bs.modal', ->
			$scope.clearAll($scope.resouce)
			return

		angular.element('#addNewAppModal').on 'shown.bs.modal', ->
			$('#appName').focus()
			return
