angular.module 'mis'

	.controller 'companyCtrl', ($scope, company,$interval, $timeout,prompt,DTOptionsBuilder,DTDefaultOptions, DTColumnDefBuilder,DTColumnBuilder,notify)->
		$scope.company = {}
		$scope.loading = true
		$scope.currentPage = 1
		$scope.edit = false
		$scope.formError = 0
		if $scope.edit == false
			$scope.modal_title = 'Add'

		$scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('simple_numbers').withOption('order', [1,'asc']).withOption('stateSave',true).withOption('stateLoadParams',(setting,data)->
				data.search.search = ''
			).withColumnFilter(aoColumns: [
				  null
				  1
				  2
				  3
				  4
				  null
		]).withOption('responsive', true)

		

		$scope.dtColumnDefs = [
			DTColumnDefBuilder.newColumnDef(0).notSortable()
			DTColumnDefBuilder.newColumnDef(5).notSortable()
		]

		company.get().success (data)->
			$scope.companies = data.companies
			$scope.loading = false
			return




		uploader = new (plupload.Uploader)(
				runtimes : 'html5,fl$scope.formError ash,silverlight,html4'
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
						$scope.company.logo = file.name

					UploadComplete: (up, files)->
						console.log 'file completed'
						$scope.loading = false
						$timeout (->
							angular.forEach(files, (file)->
								angular.element('#preview').html('<div id="fileadded" class="'+file.id+'"><div id="' + file.id + '"> <div class="avtar inline" style="vertical-align:middle"><div class="img avatar-md"><img src=/tmp/' + file.name + '></div></div><span class="filesize">(' + plupload.formatSize(file.size) + ')</span> <a href="javascript:;" id="' + file.id + '" class="removeFile btn btn-md btn-close" ng-click="shownoimage()">Remove</a></div></div>')
								angular.element('a#' + file.id).on 'click', ->
									up.removeFile file
									angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>")
									return
								)
						), 1000
					Error: (up, err)->
						alert "Error #" + err.code + ": " + err.message
			)
		uploader.init()

		angular.element('#addNewAppModal').on('shown.bs.modal', ->
				uploader.refresh()
			)
		angular.element('#addNewAppModal').on('hidden.bs.modal', ->
				$scope.company = {}
				$scope.formError = 0
				$scope.modal_title = 'Add'
				#start code for active tab after close the modal
				angular.element(".my-tabs>ul>li.active").removeClass("active");
				angular.element('#default-home').addClass('active')
				angular.element('.my-tabs>ul>li a').attr('aria-expanded', false);
				angular.element('#home1').attr('aria-expanded',true)
				angular.element('.tab-content>div').removeClass('active')
				angular.element('.tab-content #home').addClass('active')
				# End
				uploader.refresh()
			)

		company.get().success (data)->
			$scope.companies = data.companies
			$scope.loading = false


		company.getCountry().success (data)->
			$scope.countries = data
		company.getIndustry().success (data)->
			$scope.industries = data			

		$scope.closecompany = ()->
			angular.element('#cdetail').modal('hide')
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
					$scope.submitted = false
					$scope.edit = false

					$scope.company = {
						email : ''
						phone : ''
						zipcode:''
						website:''
					}
					$scope.formError = 0
					angular.element('#addNewAppModal').modal('hide')

				)
			else

				$timeout (->
					$scope.submitted = false
					$scope.edit = false
					$scope.company = {
						email : ''
						phone : ''
						zipcode:''
						website:''
					}

					# myEl = angular.element(document.querySelector('#fileadded'))
					# myEl.remove()
					# angular.element('#preview').html("<img src='img/noCompany.png'  style='height:100px;width:100px;'>")
					angular.element('#addNewAppModal').modal('hide')
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
				company.save($scope.company).success (data)->
					$scope.submitted = false
					angular.element('#addNewAppModal').modal('hide')
					angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>")
					$scope.company = {}

					notify
						message: 'Updated successfully.'
						duration: 1500
						position: 'right'
					$scope.formError = 0

					company.get().success (getData)->
						$scope.companies = getData.companies
						$scope.countries = getData.countries
						$scope.industries = getData.industries
						$scope.loading = false
			else
				company.update($scope.company).success (data)->
					$scope.submitted = false
					$scope.edit = false
					$scope.formError = 0
					angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>")
					angular.element('#addNewAppModal').modal('hide')
					$scope.company = {}
					$timeout (->
						notify
							message: 'Updated successfully.'
							duration: 1500
							position: 'right'

						company.get().success (getData)->
							$scope.companies = getData.companies
							$scope.countries = getData.countries
							$scope.industries = getData.industries
							$scope.loading = false
					), 500


		$scope.deleteCompany = (id)->
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
						company.destroy(id).success (data)->
							company.get().success (getData)->
								$scope.companies = getData.companies
								$scope.countries = getData.countries
								$scope.industries = getData.industries
								$scope.loading = false
						swal("Deleted!", "Your record has been deleted.", "success");
					else if result == null
  						swal 'Cancelled', 'Your record is safe ', 'info'
				)			

		$scope.viewCompany = (newid) ->
			company.showCompany(newid).success (companyData)->
				$scope.company_detail = companyData.company
				angular.element('#cdetail').modal('show')


		$scope.editCompany = (id)->
			company.edit(id).success (data)->
				if data.logo == undefined || data.logo == null || data.logo == ''
					angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>")
				else
					angular.element('#preview').html("<div id='fileadded' ><div id=" + data.id + "> <div class='avtar inline "+data.id+" style='vertical-align:middle'><div id=" + data.id + " class='img avatar-md'><img src=/uploads/company/" + data.logo + " ></div></div>")
				$scope.edit = true
				if $scope.edit == true
					$scope.modal_title = 'Save'
				$scope.company = data
				$scope.industyId = data.industry_id
				angular.element('#addNewAppModal').modal('show')

		angular.element('#addNewAppModal').on 'hidden.bs.modal', (form)->
			$scope.clearAll($scope.company)
			angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>")
			return


		angular.element('#addNewAppModal').on 'shown.bs.modal', ->
			angular.element('#appName').focus()
			return
