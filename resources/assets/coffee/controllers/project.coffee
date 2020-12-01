angular.module 'mis'

	.controller 'ProjectCtrl', ($scope,$interval, PROJECT, $timeout,prompt,$window,notify)->
		$scope.loading = false
		$scope.currentPage = 1
		projectHours = 0
		$scope.totalPages = 0;
		$scope.range = [];
		$scope.edit = false
		price_types  = 'per_hour'
		projectlead_id = ''
		if $scope.edit == false
			$scope.modal_title = 'Add'
			angular.element('.status-detail').hide()

		currentUrl = $window.location.href
		pId = currentUrl.split('/')[4]||"Undefined"
		$scope.Pro_Id = pId
		$scope.formError = 0
		angular.element('#fix_hours').hide()
		#PROJECT.getCompany().success (data)->
		#	$scope.companies = data.companies.data
		#	$scope.loading = false
	
		

		#PROJECT.get().success (data)->
		#	$scope.projects = data.projects
		#	$scope.projectsCategories = data.projectsCategories
		#	$scope.loading = false
		#	console.log($scope.projects)

		$scope.showModal = (event) ->
			console.log(event)
			$scope.client_id = event.target.id
			# $scope.project_array.client_id = $scope.client_id
			angular.element('#addNewAppModal').modal('show')
			return

		$scope.viewHours = ->
			angular.element('#fix_hours').show()
			if $scope.edit==true
				$scope.project_array.fix_hours = projectHours
			return

		$scope.hideHours = ->
			angular.element('#fix_hours').hide()
			$scope.project_array.fix_hours = 0
			return

		$scope.cancelAll = ->
			angular.element('#addNewAppModal').modal('hide')
			$timeout (->
				$scope.submitted = false
				if $scope.edit == false
					$scope.modal_title = 'Add'
					angular.element('.status-detail').hide()

				$scope.project_array = {}
				angular.element('.status-detail').hide()
				$scope.formError = 0
			), 1000
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
					$scope.formError = 0
					if $scope.edit == false
						$scope.modal_title = 'Add'
						angular.element('.status-detail').hide()

					$scope.project_array = {}
					angular.element('.status-detail').hide()
					$scope.formError = 0
				)
			else
				angular.element('#addNewAppModal').modal('hide')

				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.formError = 0
					$scope.project_array = {}
					$scope.formError = 0
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

			if $scope.edit == false
				$scope.project_array.price_types = $scope.price_types
				if $scope.client_id
					$scope.project_array.client_id = $scope.client_id
					$scope.project_array.projectlead_id = $scope.projectlead_id
				PROJECT.save($scope.project_array).success (data)->
					$scope.submitted = false
					$scope.project_array = {}
					$scope.formError = 0
					angular.element('#addNewAppModal').modal('hide')
					notify
						message: 'Added successfully.'
						duration: 1500
						position: 'right'
					$scope.formError = 0
					PROJECT.getCompany().success (data)->
						$scope.companies = data.companies
						$scope.loading = false

					PROJECT.get().success (getData)->
						$scope.projects = getData.projects
						$scope.projectsCategories = getData.projectsCategories
						$scope.loading = false
						window.location.href = '/projects';
			else
				$scope.project_array.price_types = $scope.price_types
				$scope.project_array.client_id = $scope.client_id
				$scope.project_array.projectlead_id = $scope.projectlead_id
				PROJECT.update($scope.project_array).success (data)->
					
					$scope.submitted = false
					$scope.edit = false
					$scope.formError = 0
					if $scope.edit == false
						$scope.modal_title = 'Add'
						angular.element('.status-detail').hide()
					$scope.formError = 0
					$scope.project_array = {}
					angular.element('#addNewAppModal').modal('hide')
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'

						PROJECT.getCompany().success (dataCompany)->
							$scope.companies = dataCompany.companies
							$scope.loading = false

						PROJECT.get().success (getData)->
							$scope.projects = getData.projects
							$scope.projectsCategories = getData.projectsCategories
							$scope.loading = false
						window.location.href = '/projects';
					), 500

		angular.element('#addNewAppModal').on('hidden.bs.modal', (form)->
			$scope.clearAll(form)
			$scope.modal_title = 'Add'
			$scope.price_types = price_types
			$scope.client_id= ''
			$scope.projectlead_id= ''
			$scope.formError = 0
			angular.element(".my-tabs>li").removeClass("active");
			angular.element('#default-home').addClass('active')
			angular.element('.my-tabs>li a').attr('aria-expanded', false);
			angular.element('#home').attr('aria-expanded',true)
			angular.element('.tab-content>div').removeClass('active')
			angular.element('.tab-content #home').addClass('active')

			# End

			)



		$scope.deleteProject = (id)->
			swal(
				title: 'Are you Sure?'
				text: 'You won\'t be able to revert this!'
				type: 'warning'
				timer: 7000
				showCancelButton: true
				).then((result)->
					if result.value
						$scope.loading = true
						PROJECT.destroy(id).success (data)->
							window.location.href = '/projects';
							PROJECT.getCompany().success (dataCompany)->
								$scope.companies = dataCompany.companies
								$scope.loading = false
							PROJECT.get().success (getData)->
								$scope.projects = getData.projects
								$scope.projectsCategories = getData.projectsCategories
								$scope.loading = false
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result.dismiss == swal.DismissReason.cancel
  						swal("Cancelled", "Your record is safe", "info")
				)

		# $scope.deleteProject = (id)->
		# 	$scope.loading = true
		# 	PROJECT.destroy(id).success (data)->
		# 		PROJECT.getCompany().success (dataCompany)->
		# 			$scope.companies = dataCompany.companies
		# 			$scope.loading = false
		# 		PROJECT.get().success (getData)->
		# 			$scope.projects = getData.projects
		# 			$scope.projectsCategories = getData.projectsCategories
		# 			$scope.loading = false
		# 		notify
		# 			message: 'Deleted successfully.'
		# 			duration: 1500
		# 			position: 'right'

		$scope.editProject = (id)->
			PROJECT.edit(id).success (data)->
				$scope.edit = true
				if $scope.edit == true
					$scope.modal_title = 'Save'
					angular.element('.status-detail').show()
				$scope.project_array = data
				$scope.price_types = data.price_types
				projectHours = data.fix_hours
				$scope.client_id = data.client_id
				$scope.projectlead_id = data.projectlead_id
				$scope.projCategoryryId = data.category_id
				# $scope.project_array.status=data.status

				angular.element('#addNewAppModal').modal('show')

		$scope.showProjectCategory = ->
			angular.element('#addProjectCategory').modal('show')
			return

		$scope.submitProjectCategory = (form) ->
			$scope.submittedCategory = true
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true
			PROJECT.saveProjectCategory($scope.project_category).success (data)->
				console.log $scope.project_category
				$scope.submittedCategory = false
				$scope.project_category = {}
				$scope.loading = false
				angular.element('#addProjectCategory').modal('hide')
				notify
					message: 'Added successfully.'
					duration: 1500
					position: 'right'

				PROJECT.getCompany().success (dataCompany)->
					$scope.companies = dataCompany.companies
					$scope.loading = false
				PROJECT.get().success (getData)->
					$scope.projectsCategories = getData.projectsCategories



		$scope.clearProjectCategory = (form)->
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
					angular.element('#addProjectCategory').modal('hide')
					$scope.submittedCategory = false
					$scope.project_category = {}
				)
			else

				angular.element('#addProjectCategory').modal('hide')

				$timeout (->
					$scope.submittedCategory = false
					$scope.project_category = {}
					), 100
			return


