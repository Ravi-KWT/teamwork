elixir = require 'laravel-elixir'


bowerPath = (parts...)-> ['.','bower_components'].concat(parts).join('/')

elixir (mix)->

	mix.styles [
	  bowerPath('bootstrap','dist','css','bootstrap.min.css')
	  bowerPath('seiyria-bootstrap-slider','dist','css','bootstrap-slider.css')
	  'bootstrap-timepicker.min.css'
	  'font-awesome.css'
	  'jquery.scrollbar.css'
	  'bootstrap-select.min.css'
	  'switchery.min.css'
	  'angular-datepicker.min.css'
	  'datatables.min.css'
	  'daterangepicker.css'
	  'bootstrap-datetimepicker.css'

	  'jquery.mCustomScrollbar.min.css'
	  'jquery.fancybox.min.css'
	  'rzslider.css'
	  
	  bowerPath('angular-notify','dist','angular-notify.css')
	  bowerPath('sweetalert2','dist','sweetalert2.min.css')
	  bowerPath('datetimeRangePicker','range-picker.css')
	  bowerPath('angular-datatables','dist','css','angular-datatables.css')

	  bowerPath('angular-datatables','dist','css','buttons.dataTables.min.css')
	  bowerPath('angular-datatables','dist','css','dataTables.colVis.css')
	  bowerPath('datatables.net-responsive-dt','css','responsive.dataTables.min.css')
	  
	  
	  # bowerPath('angular-datatables','dist','plugins','responsive','responsive.dataTables.min.css')
	  # 'style.css'
	], 'public/css/vendor.css'

	mix.sass 'app.scss'

	mix.scripts [
	  bowerPath('jquery','dist', 'jquery.min.js')

	  bowerPath('bootstrap','dist','js','bootstrap.min.js')



	  #bowerPath('bootstrap-timepicker','js','bootstrap-timepicker.js')
	  'bootstrap-select.js'

	  #'jquery.scrollbar.min.js'


	  'select2.min.js'
	  'classie.js'
	  'jquery.dataTables.min.js'
	  'bootstrap-timepicker.js'
	  'bootstrap-datetimepicker.js'
      'FileSaver.js'




	  #'dataTables.tableTools.min.js'
	  #'jquery-datatable-bootstrap.js'
	  #'datatables.responsive.js'
	  'lodash.min.js'
	  #'pages.min.js'
	  #'datatables.js'
	  'plupload.full.min.js'
	  'jquery.mCustomScrollbar.concat.min.js'

	  'angular.min.js'
	  'ui-bootstrap-tpls.min.js'
	  
	
	  bowerPath('angular-bootstrap', 'ui-bootstrap.js')
	  bowerPath('angular-bootstrap', 'ui-bootstrap-tpls.js')
	  bowerPath('angular-filter','dist','angular-filter.js')
	  bowerPath('underscore', 'underscore.js')
	  bowerPath('angular-utils-pagination', 'dirPagination.js')
	  #bowerPath('angular-route', 'angular-route.js')
	  bowerPath('angular-prompt', 'dist', 'angular-prompt.js')
	  bowerPath('angular-notify','dist','angular-notify.js')

	  bowerPath('sweetalert2','dist','sweetalert2.min.js')
	  bowerPath('angular-h-sweetalert','dist','ngSweetAlert2.min.js')
	    
	  bowerPath('seiyria-bootstrap-slider','dist','bootstrap-slider.js')
	  bowerPath('angular-datatables','dist','angular-datatables.min.js')
	  bowerPath('angular-datatables','dist','plugins','bootstrap','angular-datatables.bootstrap.min.js')
	  bowerPath('angular-datatables','dist','plugins','buttons','dataTables.buttons.min.js')
	  bowerPath('angular-datatables','dist','plugins','colvis','dataTables.colVis.js')
	  bowerPath('angular-datatables','dist','plugins','columnfilter','dataTables.columnFilter.js')


	  bowerPath('angular-datatables','dist','plugins','colvis','angular-datatables.colvis.min.js')
	  bowerPath('angular-datatables','dist','plugins','flash','buttons.flash.min.js')
	  bowerPath('angular-datatables','dist','plugins','html5','buttons.html5.min.js')
	  bowerPath('angular-datatables','dist','plugins','print','buttons.print.min.js')

	  bowerPath('angular-resource','angular-resource.js')



	  bowerPath('angular-datatables','dist','plugins','buttons','angular-datatables.buttons.min.js')
	  bowerPath('angular-datatables','dist','plugins','columnfilter','angular-datatables.columnfilter.js')
	  bowerPath('angular-datatables','dist','plugins','responsive','dataTables.responsive.min.js')
	  #bowerPath('angular-country-select','dist','angular-country-select.js')
	  bowerPath('angularjs-dropdown-multiselect','dist','angularjs-dropdown-multiselect.js')

	  bowerPath('moment','moment.js')
	  bowerPath('moment-duration-format','lib','moment-duration-format.js')

	  'ng-google-chart.js'
	  'daterangepicker.js'
	  'axios.min.js'

	  'rzslider.js'
	  'angular-datepicker.min.js'
	  'jquery.fancybox.min.js'
	  'jquery.cookie.js'

	  'scripts.js'


	], 'public/js/vendor.js'

	mix.coffee [
	  '*.coffee'
	  'config/*.coffee'
	  'controllers/*.coffee'
	  'services/*.coffee'
	]

	mix.version [
	  'css/app.css'
	  'css/vendor.css'
	  'js/vendor.js'
	  'js/app.js'
	]