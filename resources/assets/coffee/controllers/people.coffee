angular.module 'mis'
	.controller 'PeopleCtrl', ($scope, PEOPLE,$interval,DTOptionsBuilder,DTColumnDefBuilder, $timeout, prompt,$window,notify)->
		$scope.loading = true
		$scope.edit = false
		$scope.formError = 0
		$scope.max_date = new Date()
		gender = 'male'
		is_teamlead = false
		is_projectlead = false
		angular.element('#people_dob').attr('placeholder','Select Date of Birth')
		if $scope.edit == false
			$scope.modal_title = 'Add'

		$scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('simple_numbers').withOption('order', [1,'asc']).withOption('stateSave',true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ''
			).withOption('responsive', true)

		$scope.dtColumnDefs = [
			DTColumnDefBuilder.newColumnDef(0).notSortable()
			DTColumnDefBuilder.newColumnDef(6).notSortable()

		]
		$scope.dtOptions1 = DTOptionsBuilder.newOptions().withPaginationType('simple_numbers').withOption('order', [1,'asc']).withOption('stateSave',true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ' '
			).withColVis().withColVisOption('aiExclude', [1]).withOption('responsive', true)

		$scope.dtColumnDefs1 = [
			DTColumnDefBuilder.newColumnDef(1).notSortable()
			DTColumnDefBuilder.newColumnDef(2).notSortable()
		]

		$scope.dtOptions2 = DTOptionsBuilder.newOptions().withPaginationType('simple_numbers').withOption('order', [1,'asc']).withOption('stateSave',true).withOption('responsive', true)

		$scope.dtColumnDefs2 = [
			DTColumnDefBuilder.newColumnDef(0).notSortable()
			
		]
		$scope.maxDate=new Date


		currentUrl = $window.location.href
		pId = currentUrl.split('/')[4]||"Undefined"
		$scope.Pro_Id = pId
		angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>")
		$scope.people_array = {}

		$scope.educations = [{}]
		$scope.experiences = [{}]
		$scope.marital_status = []
		$scope.marital_status.push name: 'Single'
		$scope.marital_status.push name: 'Married'
		$scope.marital_status.push name: 'Other'

		$scope.newItem = ($event) ->
			$scope.educations.push({})
			$event.preventDefault()

		$scope.nextItem = ($event) ->
			$scope.experiences.push({})
			$event.preventDefault()

		$scope.sort = (keyname) ->
			$scope.sortKey = keyname
			$scope.reverse = !$scope.reverse
			return
		
		




		uploader = new (plupload.Uploader)(
				runtimes : 'html5,flash,silverlight,html4'
				browse_button : 'pickfiles'
				url : "../plupload/upload.php "
				flash_swf_url : "../plupload/Moxie.swf "
				silverlight_xap_url : "../plupload/Moxie.xap "
				multi_selection: false,
				max_file_size: '20mb',
				init:
					PostInit: ->

						angular.element('#filelist').innerHTML = ''
						uploader.refresh()

					FilesAdded: (up, files)->
						console.log 'file added'
						$scope.loading = true
						angular.forEach(files, (file)->

							filename=file.name
							ext = filename.substring(filename.lastIndexOf('.') + 1)
							index = filename.lastIndexOf('.')
							name=filename.substr(0, index).replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-')
							file.name=name+'.'+ext

							)
						uploader.start()



					UploadProgress: (up, file)->
						console.log 'file upload progress'
						angular.element('#photo').val(file.name)
						console.log file.name


					UploadComplete: (up, files)->

						console.log 'file completed'
						console.log  $scope.loading = false
						$timeout (->
							angular.forEach(files, (file)->
								angular.element('#preview').html('<div id="fileadded" class="'+file.id+'"><div id="' + file.id + '"> <div class="avtar inline" style="vertical-align:middle"><div class="img avatar-md"><img src=/tmp/' + file.name + '></div></div><span class="filesize">(' + plupload.formatSize(file.size) + ')</span><a href="javascript:;" class="btn btn-md btn-close removeFile ng-click=shownoimage()" id="' + file.id + '">Remove</a></div></div>')
								angular.element('a#' + file.id).on 'click', ->
									up.removeFile file
									angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>")

									return
								)
						), 1000


					Error: (up, err)->
						alert "Error #" + err.code + ": " + err.message
			)
		uploader.init()

		PEOPLE.get(pId).success (data)->
			$scope.peoples = data.peoples
			$scope.departments = data.departments
			$scope.designations=data.designations
			$scope.loading = false

		PEOPLE.getCountry().success (data)->
			$scope.countries = data
		$scope.selected_users = []

		PEOPLE.getProjectPeople(pId).success (data)->
			$scope.selected_users = data.user_ids
			$scope.projectPeople = data.project_users


		$scope.toggleSelection = (user)->
			idx = $scope.selected_users.indexOf(user);
			if idx > -1
				$scope.selected_users.splice(idx, 1)
			else
				$scope.selected_users.push(user)

		$scope.addPeopleToProject = ()->
			PEOPLE.addPeopleToProject($scope.selected_users, $scope.Pro_Id).success (data)->
				angular.element('#addPeopleToProjectModal').modal('hide')
				notify
					message: 'Added successfully.'
					duration: 1500
					position: 'right'
				PEOPLE.getProjectPeople(pId).success (user_data)->
					console.log data
					$scope.selected_users = user_data.user_ids
					$scope.projectPeople = user_data.project_users


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
					angular.element('#people_modal').modal('hide')
					$scope.submitted = false
					$scope.edit = false
					$scope.people_array = {}
					$scope.formError = 0
					$scope.people_array.email = ''
					# $scope.educations = {}
					# $scope.experiences ={}
					myEl = angular.element(document.querySelector('#fileadded'))
					myEl.remove()
					angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>")
				)
			else
				angular.element('#people_modal').modal('hide')
				$scope.educations = [
					qualification: ''
					collage: ''
					university: ''
					passing_year: ''
					percentage: ''
				]
				$scope.experiences = [
					company_name: ''
					from: ''
					to: ''
					salary: ''
					reason: ''
				]
				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.people_array = {}
					myEl = angular.element(document.querySelector('#fileadded'))
					myEl.remove()
					angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>")
				), 100
				return

		$scope.email_error=false

		$scope.submit = (form)->
			errors = form.$error
			angular.forEach errors, (val)->
				if angular.isArray(val)
					$scope.formError += val.length
				return
			$scope.loading = true
			$scope.submitted = true
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true

			if $scope.edit == false
				$scope.people_array.photo=angular.element('#photo').val()
				$scope.people_array.gender = $scope.gender
				$scope.people_array.is_teamlead = $scope.is_teamlead
				$scope.people_array.is_projectlead = $scope.is_projectlead
				PEOPLE.save($scope.people_array,$scope.is_projectlead, $scope.educations, $scope.experiences).success (data)->

					if data['error']
						$scope.email_error=true
						$scope.user_email_error = data['messages'].email
						$scope.submitted =false
						$scope.loading = false
					else
						$scope.email_error=false
						$scope.submitted = false

						$scope.people_array = {}
						$scope.formError = 0
						angular.element('#people_modal').modal('hide')
						myEl = angular.element(document.querySelector('#fileadded'))
						myEl.remove()
						angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>")

						$timeout (->
							notify
								message: 'Added successfully.'
								duration: 1500
								position: 'right'

							PEOPLE.get(pId).success (getData)->
								$scope.peoples = getData.peoples
								$scope.countries = getData.countries
								$scope.loading = false
						),100
			else
				
				$scope.people_array.photo=angular.element('#photo').val()
				$scope.people_array.gender = $scope.gender
				$scope.people_array.is_teamlead = $scope.is_teamlead
				$scope.people_array.is_projectlead = $scope.is_projectlead
				
				PEOPLE.update($scope.people_array,$scope.is_projectlead, $scope.educations, $scope.experiences).success (data)->
					$scope.submitted = false
					$scope.edit = false
					$scope.people_array = {}
					$scope.formError = 0
					angular.element('#people_modal').modal('hide')
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'

						PEOPLE.get(pId).success (getData)->
							$scope.peoples = getData.peoples
							$scope.countries = getData.countries
							$scope.loading = false
					), 100

		angular.element('#people_modal').on 'hidden.bs.modal', (form)->
			$scope.formError = 0
			angular.element('#people_mobile,#people_email').val('')
			$scope.clearAll(form)
			$scope.gender = 'male'
			$scope.is_teamlead = false
			$scope.is_projectlead = false
			$scope.email_error=false
			$scope.people_array.gender = gender
			$scope.people_array.photo = ''
			# $scope.people_array.is_teamlead = is_teamlead
			$scope.modal_title = 'Add'
			angular.element('#people_mobile,#people_email').val('')
			angular.element('#people_dob').attr('placeholder','Select Date of Birth')
			#start code for active tab after close the modal
			angular.element(".my-tabs>li").removeClass("active");
			angular.element('#default-home').addClass('active')
			angular.element('.my-tabs>ul>li a').attr('aria-expanded', false)
			angular.element('#home1').attr('aria-expanded',true)
			angular.element('.tab-content>div').removeClass('active')
			angular.element('.tab-content #tab_1').addClass('active')
			# end
			#for user detail tab active
			angular.element(".user_profile_detail .my-tabs1>li").removeClass("active")
			angular.element('.user_profile_detail #default-detail-home').addClass('active')
			angular.element('.user_profile_detail .my-tabs1>li a').attr('aria-expanded', false)
			angular.element('#detail_1').attr('aria-expanded',true)
			angular.element('.user_profile_detail .tab-content>div').removeClass('active')
			angular.element('.user_profile_detail .tab-content #detail_1').addClass('active')

			# End
			uploader.refresh()

		angular.element('#addPeopleToProjectModal').on 'hidden.bs.modal',(form) ->
			PEOPLE.getProjectPeople(pId).success (data)->
				$scope.selected_users = data.user_ids
				# $scope.projectPeople = data.project_users

		angular.element('#view_user_profile').on 'hidden.bs.modal',->
			angular.element(".user_profile_detail .my-tabs1>li").removeClass("active")
			angular.element('.user_profile_detail #default-detail-home').addClass('active')
			angular.element('.user_profile_detail .my-tabs1>li a').attr('aria-expanded', false)
			angular.element('#detail_1').attr('aria-expanded',true)
			angular.element('.user_profile_detail .tab-content>div').removeClass('active')
			angular.element('.user_profile_detail .tab-content #detail_1').addClass('active')
			return

		angular.element('#people_modal').on 'shown.bs.modal', ->
			$('#fname').focus()
			return



		# $scope.deletePeople = (id)->
		# 	$scope.loading = true
		# 	PEOPLE.destroy(id).success (data)->
		# 		PEOPLE.get().success (getData)->
		# 			$scope.peoples = getData.peoples
		# 			$scope.loading = false
		# 		notify
		# 			message: 'Deleted successfully.'
		# 			duration: 1500
		# 			position: 'right'

		
		$scope.deletePeople = (id)->
			swal(
				title: 'Are you Sure?'
				text: 'You won\'t be able to revert this!'
				type: 'warning'
				timer: 7000
				showCancelButton: true
				).then((result)->
					if result.value
						$scope.loading = true
						PEOPLE.destroy(id).success (data)->
							PEOPLE.get().success (getData)->
								$scope.peoples = getData.peoples
								$scope.loading = false
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result.dismiss == swal.DismissReason.cancel
  						swal 'Cancelled', 'Your record is safe', 'info'
				)

		$scope.statusChange = (id) ->
			$scope.loading = true
			PEOPLE.statusChanged(id).success (data)->
				PEOPLE.get().success (getData)->
					$scope.peoples = getData.peoples
					$scope.loading = false
				

		$scope.editPeople = (id)->
			PEOPLE.edit(id).success (data)->
				if data[0].photo == undefined || data[0].photo == null || data[0].photo == ''
					angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>")
				else
					angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div  class='img avatar-md'><img src=/uploads/people/" + data[0].photo + "></div></div>")
					angular.element('#photo').val(data[0].photo)
				
				$scope.edit = true
				
				if $scope.edit == true
					$scope.modal_title = 'Save'
				$scope.people_array = data[0]
				$scope.gender = data[0].gender
				$scope.is_teamlead = data[7]
				$scope.is_projectlead = data[0].user.is_projectlead
				$scope.people_array.email = data[1]
				$scope.educations = data[2]
				$scope.experiences = data[3]
				$scope.departments = data[4]
				$scope.people_array.roles = data[6]
				$scope.people_array.is_teamlead = data[7]
				$scope.people_array.is_projectlead = data[0].user.is_projectlead
				
				if $scope.people_array.department_id == 0
					$scope.people_array.department_id = ""
				if $scope.people_array.designation_id == 0
					$scope.people_array.designation_id = ""
				if $scope.people_array.management_level == '0'
					$scope.people_array.management_level = ""
				angular.element('#people_modal').modal('show')

		$scope.viewPeople = (id)->
			PEOPLE.view_people(id).success (data)->
				$scope.user_profile_detail = data[0]
				$scope.user_email = data[1]
				$scope.user_educations = data[2]
				$scope.user_experiences = data[3]
				angular.element('#view_user_profile').modal('show')
				return

		$scope.removeEducationClone = (education)->
			index = $scope.educations.indexOf(education)
			$scope.educations.splice(index, 1)
		$scope.removeEducation = (education)->
			$scope.options =
				title: 'Remove Education'
				message:'Are you sure you want to delete this education detail?'
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
			prompt($scope.options).then( ->
				PEOPLE.destroyEducation(education.id).success (data)->
					index = $scope.educations.indexOf(education);
					$scope.educations.splice(index, 1)
				notify
					message: 'Removed successfully.'
					duration: 1500
					position: 'right'
			)
		$scope.removeExperience = (id)->
			$scope.options =
				title: 'Remove Experience'
				message:'Are you sure you want to delete this experience detail?'
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
			prompt($scope.options).then( ->
				PEOPLE.destroyExperience(id).success (data)->
					index = $scope.experiences.indexOf(id);
					$scope.experiences.splice(index, 1)
				notify
					message: 'Removed successfully.'
					duration: 1500
					position: 'right'
			)
		$scope.removeExperienceClone = (experience)->
			index = $scope.experiences.indexOf(experience);
			$scope.experiences.splice(index, 1)

		$scope.submitLogin = (form)->
			$scope.loading = true
			$scope.loginSubmitted = true
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true

			PEOPLE.login($scope.login_array).success (data) ->
				if data.error
					$scope.credential_error=data.msg
					$scope.loading = false
					$scope.loginSubmitted = false
					return
				if data.success
					window.location.href = '/'
					$scope.loading = false
					$scope.loginSubmitted = false

		$scope.submitForgotPassword = (form)->
			$scope.loading = true
			$scope.forgotPasswordSubmitted = true
			$scope.credential_error = ""
			$scope.success_msg = ""
			if form.$invalid
				$scope.loading = false
				return
			else
				$scope.loading = true

			PEOPLE.fogotPassword($scope.forgotPasswod_array).success (data) ->
				# console.log data.error
				# console.log data.msg
				if data.error
					$scope.credential_error=data.msg
					$scope.loading = false
					$scope.forgotPasswordSubmitted = false
					return
				if data.success
					$scope.success_msg=data.msg
					$scope.forgotPasswod_array={}
					$scope.loading = false
					$scope.forgotPasswordSubmitted = false




