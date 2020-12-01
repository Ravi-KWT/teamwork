// const mix = require('laravel-mix');
const mix = require('laravel-elixir');
// const coffee = 'coffee-loader!./file.coffee';


// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');


// bowerPath = (parts...)-> ['.','bower_components'].concat(parts).join('/')
var bowerPath = 'bower_components/';

mix.styles([
  'resources/assets/css/bootstrap-timepicker.min.css',
  'resources/assets/css/font-awesome.css',
  'resources/assets/css/jquery.scrollbar.css',
  'resources/assets/css/bootstrap-select.min.css',
  'resources/assets/css/switchery.min.css',
  'resources/assets/css/angular-datepicker.min.css',
  'resources/assets/css/datatables.min.css',
  'resources/assets/css/daterangepicker.css',
  'resources/assets/css/bootstrap-datetimepicker.css',
  'resources/assets/css/jquery.mCustomScrollbar.min.css',
  'resources/assets/css/jquery.fancybox.min.css',
  'resources/assets/css/rzslider.css',
  bowerPath + 'bootstrap/dist/css/bootstrap.min.css',
  // bowerPath + 'seiyria-bootstrap-slider/dist/css/bootstrap-slider.css',
  bowerPath + 'angular-notify/dist/angular-notify.css',
  bowerPath + 'sweetalert2/dist/sweetalert2.min.css',
  bowerPath + 'datetimeRangePicker/range-picker.css',
  // bowerPath + 'angular-datatables/dist/css/angular-datatables.css',
  // bowerPath + 'angular-datatables/dist/css/buttons.dataTables.min.css',
  // bowerPath + 'angular-datatables/dist/css/dataTables.colVis.css',
  bowerPath + 'datatables.net-responsive-dt/css/responsive.dataTables.min.css',
], 'public/css/vendor.css').sourceMaps();

mix.scripts([
  'resources/assets/js/bootstrap-select.js',
  'resources/assets/js/jquery.scrollbar.min.js',
  'resources/assets/js/select2.min.js',
  'resources/assets/js/classie.js',
  'resources/assets/js/jquery.dataTables.min.js',
  'resources/assets/js/bootstrap-timepicker.js',
  'resources/assets/js/bootstrap-datetimepicker.js',
  'resources/assets/js/FileSaver.js',
  // 'resources/assets/js/dataTables.tableTools.min.js',
  'resources/assets/js/jquery-datatable-bootstrap.js',
  // 'resources/assets/js/datatables.responsive.js',
  'resources/assets/js/lodash.min.js',
  'resources/assets/js/pages.min.js',
  // 'resources/assets/js/datatables.js',
  'resources/assets/js/plupload.full.min.js',
  'resources/assets/js/jquery.mCustomScrollbar.concat.min.js',
  'resources/assets/js/angular.min.js',
  'resources/assets/js/ui-bootstrap-tpls.min.js',
  'resources/assets/js/ng-google-chart.js',
  'resources/assets/js/daterangepicker.js',
  'resources/assets/js/axios.min.js',
  'resources/assets/js/rzslider.js',
  'resources/assets/js/angular-datepicker.min.js',
  'resources/assets/js/jquery.fancybox.min.js',
  'resources/assets/js/jquery.cookie.js',
  'resources/assets/js/scripts.js',
  bowerPath + 'jquery/dist/jquery.min.js',
  bowerPath + 'bootstrap/dist/js/bootstrap.min.js',
  bowerPath + 'bootstrap-timepicker/js/bootstrap-timepicker.js',
  bowerPath + 'angular-bootstrap/ui-bootstrap.js',
  bowerPath + 'angular-bootstrap/ui-bootstrap-tpls.js',
  bowerPath + 'angular-filter/dist/angular-filter.js',
  bowerPath + 'underscore/underscore.js',
  bowerPath + 'angular-utils-pagination/dirPagination.js',
  bowerPath + 'angular-prompt/dist/angular-prompt.js',
  bowerPath + 'angular-notify/dist/angular-notify.js',
  bowerPath + 'sweetalert2/dist/sweetalert2.min.js',
  // bowerPath + 'angular-h-sweetalert/dist/ngSweetAlert2.min.js',
  // bowerPath + 'seiyria-bootstrap-slider/dist/bootstrap-slider.js',
  // bowerPath + 'angular-datatables/dist/angular-datatables.min.js',
  // bowerPath + 'angular-datatables/dist/plugins/bootstrap/angular-datatables.bootstrap.min.js',
  // bowerPath + 'angular-datatables/dist/plugins/buttons/dataTables.buttons.min.js',
  // bowerPath + 'angular-datatables/dist/plugins/colvis/dataTables.colVis.js',
  // bowerPath + 'angular-datatables/dist/plugins/columnfilter/dataTables.columnFilter.js',
  // bowerPath + 'angular-datatables/dist/plugins/colvis/angular-datatables.colvis.min.js',
  // bowerPath + 'angular-datatables/dist/plugins/flash/buttons.flash.min.js',
  // bowerPath + 'angular-datatables/dist/plugins/html5/buttons.html5.min.js',
  // bowerPath + 'angular-datatables/dist/plugins/print/buttons.print.min.js',
  // bowerPath + 'angular-resource/angular-resource.js',
  // bowerPath + 'angular-datatables/dist/plugins/buttons/angular-datatables.buttons.min.js',
  // bowerPath + 'angular-datatables/dist/plugins/columnfilter/angular-datatables.columnfilter.js',
  // bowerPath + 'angular-datatables/dist/plugins/responsive/dataTables.responsive.min.js',
  bowerPath + 'angular-country-select/dist/angular-country-select.js',
  // bowerPath + 'angularjs-dropdown-multiselect/dist/angularjs-dropdown-multiselect.js',
  bowerPath + 'moment/moment.js',
  // bowerPath + 'moment-duration-format/lib/moment-duration-format.js'
], 'public/js/vendor.js').sourceMaps();

mix.coffee([
  '*.coffee'
  'config/*.coffee'
  'controllers/*.coffee'
  'services/*.coffee'
], 'public/js')

mix.version([
  'css/app.css'
  'css/vendor.css'
  'js/vendor.js'
  'js/app.js'
])
