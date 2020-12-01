(function() {
  angular.module('mis', ['angularjs-dropdown-multiselect', 'googlechart', 'ngResource', 'angularUtils.directives.dirPagination', 'ui.bootstrap', 'cgPrompt', '720kb.datepicker', 'angular.filter', 'angularjs-dropdown-multiselect', 'cgNotify', 'datatables', 'datatables.bootstrap', 'datatables.buttons', 'datatables.colvis', 'datatables.columnfilter', 'rzModule']).filter('timeAgo', [
    '$interval', function($interval) {
      var fromNowFilter;
      fromNowFilter = function(time) {
        return moment(time).fromNow();
      };
      $interval((function() {}), 60000);
      fromNowFilter.$stateful = true;
      return fromNowFilter;
    }
  ]).filter('stringToTimestamp', function() {
    return function(input) {
      return moment(input).format("ddd,hA");
    };
  }).filter('split', function() {
    (function(input, splitChar, splitIndex) {});
    return input.split(splitChar)[splitIndex];
  }).filter('setDecimal', function($filter) {
    return function(input, places) {
      var factor;
      if (isNaN(input)) {
        return input;
      }
      factor = '1' + Array(+(places > 0 && places + 1)).join('0');
      return Math.round(input * factor) / factor;
    };
  }).filter('groupBy', function() {
    var results;
    results = {};
    return function(data, key) {
      var groupKey, i, k, keys, result, scopeId;
      if (!(data && key)) {
        return;
      }
      result = void 0;
      if (!this.$id) {
        result = {};
      } else {
        scopeId = this.$id;
      }
      if (!results[scopeId]) {
        results[scopeId] = {};
        this.$on('$destroy', function() {});
        delete results[scopeId];
        return;
      }
      result = results[scopeId];
      for (groupKey in result) {
        result[groupKey].splice(0, result[groupKey].length);
        i = 0;
      }
      while (i < data.length) {
        if (!result[data[i][key]]) {
          result[data[i][key]] = [];
        }
        result[data[i][key]].push(data[i]);
        i++;
      }
      keys = Object.keys(result);
      k = 0;
      while (k < keys.length) {
        if (result[keys[k]].length === 0) {
          delete result[keys[k]];
        }
        k++;
      }
      return result;
    };
  }).filter('strLimit', [
    '$filter', function($filter) {
      return function(input, limit) {
        if (!input) {
          return;
        }
        if (input.length <= limit) {
          return input;
        }
        return $filter('limitTo')(input, limit) + '...';
      };
    }
  ]).filter('filterBy', function() {
    return function(array, query) {
      var keys, parts;
      parts = query && query.trim().split(/\s+/);
      keys = Object.keys(array[0]);
      if (!parts || !parts.length) {
        return array;
      }
      return array.filter(function(obj) {
        return parts.every(function(part) {
          return keys.some(function(key) {
            return String(obj[key]).toLowerCase().indexOf(part.toLowerCase()) > -1;
          });
        });
      });
    };
  }).filter('capitalize', function() {
    return function(input) {
      if (!!input) {
        return input.charAt(0).toUpperCase() + input.substr(1).toLowerCase();
      } else {
        return '';
      }
    };
  }).filter('taskViewDateFormat', function($filter) {
    return function(text) {
      var tempdate;
      tempdate = new Date(text.replace(/-/g, '/'));
      return $filter('date')(tempdate, 'dd-MM-yyyy');
    };
  }).filter('taskViewYearMonthDayFormat', function($filter) {
    return function(text) {
      var tempdate;
      tempdate = new Date(text.replace(/-/g, '/'));
      return $filter('date')(tempdate, 'yyyyMMdd');
    };
  }).filter('parseDate', function() {
    return function(input) {
      return new Date(input);
    };
  }).config(function(paginationTemplateProvider) {
    return paginationTemplateProvider.setPath('/html/dirPagination.tpl.html');
  });

}).call(this);

(function() {
  angular.module('mis').config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{%');
    return $interpolateProvider.endSymbol('%}');
  });

}).call(this);

(function() {
  angular.module('mis').controller('BodyCtrl', function($scope) {
    return $scope.title = "MIS";
  });

}).call(this);

(function() {
  angular.module('mis').controller('TaskCategoriesCtrl', function($scope, categoryTask, $timeout, DTOptionsBuilder, DTColumnDefBuilder, $window, notify, prompt) {
    var cId, currentUrl;
    $scope.loading = false;
    $scope.formError = 0;
    $scope.currentDate = new Date();
    currentUrl = $window.location.href;
    cId = currentUrl.split('/')[4] || "Undefined";
    $scope.example14model = [];
    $scope.example14settings = {
      externalIdProp: ''
    };
    $scope.showModal = function(event) {
      angular.element('#addNewAppModal').modal('show');
      categoryTask.get(cId).success(function(data) {
        $scope.users = [];
        if (data.loginUser.user_id !== 0) {
          if (data.loginUser.lname !== null) {
            $scope.example14model.push({
              id: data.loginUser.user_id,
              label: data.loginUser.fname + " " + data.loginUser.lname
            });
          } else {
            $scope.example14model.push({
              id: data.loginUser.user_id,
              label: data.loginUser.fname
            });
          }
        }
        angular.forEach(data.users, function(value, key) {
          if (value.id !== 0) {
            if (value.people.lname !== null) {
              $scope.users.push({
                id: value.id,
                label: value.people.fname + " " + value.people.lname
              });
            } else {
              $scope.users.push({
                id: value.id,
                label: value.people.fname
              });
            }
          }
        });
        return $scope.loading = false;
      });
    };
    $scope.cancelAll = function() {
      angular.element('#addNewAppModal').modal('hide');
      $timeout((function() {
        $scope.submitted = false;
        return $scope.task = {};
      }), 100);
    };
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          angular.element('.task-select .select2-container').select2('val', '');
          $scope.submitted = false;
          $scope.formError = 0;
          return $scope.task = {};
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        angular.element('.task-select .select2-container').select2('val', '');
        $timeout((function() {
          $scope.submitted = false;
          $scope.task = {};
          $scope.formError = 0;
          return $scope.task.priority = "low";
        }), 100);
      }
    };
    $scope.submit = function(form) {
      var errors;
      $scope.loading = true;
      $scope.submitted = true;
      errors = form.$error;
      angular.forEach(errors, function(val) {
        if (angular.isArray(val)) {
          $scope.formError += val.length;
        }
      });
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      $scope.task.user_id = [];
      angular.forEach($scope.example14model, function(value, key) {
        $scope.task.user_id.push(value.id);
      });
      return categoryTask.save($scope.task).success(function(data) {
        angular.element('#addNewAppModal').modal('hide');
        notify({
          message: 'Task added successfully',
          duration: 1500,
          position: 'right'
        });
        $scope.example14model.length = 0;
        $scope.loading = false;
        return $window.location.href = currentUrl;
      });
    };
    return angular.element('#addNewAppModal').on('hidden.bs.modal', function(form) {
      $scope.clearAll(form);
      $scope.modal_title = 'Add';
      $scope.example14model.length = 0;
      $scope.formError = 0;
      $scope.task.priority = 'low';
      angular.element("#addNewAppModal .my-tabs>li.active").removeClass("active");
      angular.element('#default-home').addClass('active');
      angular.element('#addNewAppModal .my-tabs>li a').attr('aria-expanded', false);
      angular.element('#home').attr('aria-expanded', true);
      angular.element('#addNewAppModal .tab-content>div').removeClass('active');
      return angular.element('#addNewAppModal .tab-content #home').addClass('active');
    });
  });

}).call(this);

(function() {
  angular.module('mis').controller('companyCtrl', function($scope, company, $interval, $timeout, prompt, DTOptionsBuilder, DTDefaultOptions, DTColumnDefBuilder, DTColumnBuilder, notify) {
    var uploader;
    $scope.company = {};
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    $scope.formError = 0;
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('simple_numbers').withOption('order', [1, 'asc']).withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = '';
    }).withColumnFilter({
      aoColumns: [null, 1, 2, 3, 4, null]
    }).withOption('responsive', true);
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(0).notSortable(), DTColumnDefBuilder.newColumnDef(5).notSortable()];
    company.get().success(function(data) {
      $scope.companies = data.companies;
      $scope.loading = false;
    });
    uploader = new plupload.Uploader({
      runtimes: 'html5,fl$scope.formError ash,silverlight,html4',
      browse_button: 'pickfiles',
      url: "../plupload/upload.php ",
      flash_swf_url: "../plupload/Moxie.swf ",
      silverlight_xap_url: "../plupload/Moxie.xap ",
      multi_selection: false,
      max_file_size: '20mb',
      init: {
        PostInit: function() {
          angular.element('#filelist').innerHTML = '';
          return uploader.refresh();
        },
        FilesAdded: function(up, files) {
          console.log('file added');
          $scope.loading = true;
          angular.forEach(files, function(file) {
            var ext, filename, index, name;
            filename = file.name;
            ext = filename.substring(filename.lastIndexOf('.') + 1);
            index = filename.lastIndexOf('.');
            name = filename.substr(0, index).replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
            return file.name = name + '.' + ext;
          });
          return uploader.start();
        },
        UploadProgress: function(up, file) {
          return $scope.company.logo = file.name;
        },
        UploadComplete: function(up, files) {
          console.log('file completed');
          $scope.loading = false;
          return $timeout((function() {
            return angular.forEach(files, function(file) {
              angular.element('#preview').html('<div id="fileadded" class="' + file.id + '"><div id="' + file.id + '"> <div class="avtar inline" style="vertical-align:middle"><div class="img avatar-md"><img src=/tmp/' + file.name + '></div></div><span class="filesize">(' + plupload.formatSize(file.size) + ')</span> <a href="javascript:;" id="' + file.id + '" class="removeFile btn btn-md btn-close" ng-click="shownoimage()">Remove</a></div></div>');
              return angular.element('a#' + file.id).on('click', function() {
                up.removeFile(file);
                angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>");
              });
            });
          }), 1000);
        },
        Error: function(up, err) {
          return alert("Error #" + err.code + ": " + err.message);
        }
      }
    });
    uploader.init();
    angular.element('#addNewAppModal').on('shown.bs.modal', function() {
      return uploader.refresh();
    });
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.company = {};
      $scope.formError = 0;
      $scope.modal_title = 'Add';
      angular.element(".my-tabs>ul>li.active").removeClass("active");
      angular.element('#default-home').addClass('active');
      angular.element('.my-tabs>ul>li a').attr('aria-expanded', false);
      angular.element('#home1').attr('aria-expanded', true);
      angular.element('.tab-content>div').removeClass('active');
      angular.element('.tab-content #home').addClass('active');
      return uploader.refresh();
    });
    company.get().success(function(data) {
      $scope.companies = data.companies;
      return $scope.loading = false;
    });
    company.getCountry().success(function(data) {
      return $scope.countries = data;
    });
    company.getIndustry().success(function(data) {
      return $scope.industries = data;
    });
    $scope.closecompany = function() {
      angular.element('#cdetail').modal('hide');
    };
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.company = {
            email: '',
            phone: '',
            zipcode: '',
            website: ''
          };
          $scope.formError = 0;
          return angular.element('#addNewAppModal').modal('hide');
        });
      } else {
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.company = {
            email: '',
            phone: '',
            zipcode: '',
            website: ''
          };
          return angular.element('#addNewAppModal').modal('hide');
        }), 100);
      }
    };
    $scope.submit = function(form) {
      var errors;
      $scope.loading = true;
      $scope.submitted = true;
      errors = form.$error;
      angular.forEach(errors, function(val) {
        if (angular.isArray(val)) {
          $scope.formError += val.length;
        }
      });
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        return company.save($scope.company).success(function(data) {
          $scope.submitted = false;
          angular.element('#addNewAppModal').modal('hide');
          angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>");
          $scope.company = {};
          notify({
            message: 'Updated successfully.',
            duration: 1500,
            position: 'right'
          });
          $scope.formError = 0;
          return company.get().success(function(getData) {
            $scope.companies = getData.companies;
            $scope.countries = getData.countries;
            $scope.industries = getData.industries;
            return $scope.loading = false;
          });
        });
      } else {
        return company.update($scope.company).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.formError = 0;
          angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>");
          angular.element('#addNewAppModal').modal('hide');
          $scope.company = {};
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            return company.get().success(function(getData) {
              $scope.companies = getData.companies;
              $scope.countries = getData.countries;
              $scope.industries = getData.industries;
              return $scope.loading = false;
            });
          }), 500);
        });
      }
    };
    $scope.deleteCompany = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          company.destroy(id).success(function(data) {
            return company.get().success(function(getData) {
              $scope.companies = getData.companies;
              $scope.countries = getData.countries;
              $scope.industries = getData.industries;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe ', 'info');
        }
      });
    };
    $scope.viewCompany = function(newid) {
      return company.showCompany(newid).success(function(companyData) {
        $scope.company_detail = companyData.company;
        return angular.element('#cdetail').modal('show');
      });
    };
    $scope.editCompany = function(id) {
      return company.edit(id).success(function(data) {
        if (data.logo === void 0 || data.logo === null || data.logo === '') {
          angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>");
        } else {
          angular.element('#preview').html("<div id='fileadded' ><div id=" + data.id + "> <div class='avtar inline " + data.id + " style='vertical-align:middle'><div id=" + data.id + " class='img avatar-md'><img src=/uploads/company/" + data.logo + " ></div></div>");
        }
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
        }
        $scope.company = data;
        $scope.industyId = data.industry_id;
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function(form) {
      $scope.clearAll($scope.company);
      angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/noCompany.png' ></div></div>");
    });
    return angular.element('#addNewAppModal').on('shown.bs.modal', function() {
      angular.element('#appName').focus();
    });
  });

}).call(this);

(function() {
  angular.module('mis').controller('DepartmentCtrl', function($resource, $scope, Department, DTOptionsBuilder, DTColumnDefBuilder, prompt, $timeout, notify, $http) {
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = ' ';
    }).withOption('responsive', true);
    $scope.department = {};
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(0), DTColumnDefBuilder.newColumnDef(1).notSortable()];
    Department.get().success(function(data) {
      $scope.departments = data;
      $scope.loading = false;
    });
    $scope.clearForm = function() {
      $scope.department = {};
    };
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.department = {};
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.department = {};
        }), 100);
      }
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.department = {};
      return $scope.modal_title = 'Add';
    });
    $scope.submit = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        return Department.save($scope.department).success(function(data) {
          $scope.loading = false;
          $scope.submitted = false;
          $scope.department = {};
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: 'Added successfully.',
            duration: 1500,
            position: 'right'
          });
          return Department.get().success(function(getData) {
            $scope.departments = getData;
            return $scope.loading = false;
          });
        });
      } else {
        return Department.update($scope.department).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.department = {};
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            return Department.get().success(function(getData) {
              $scope.departments = getData;
              return $scope.loading = false;
            });
          }), 500);
        });
      }
    };
    $scope.deleteDepartment = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          Department.destroy(id).success(function(data) {
            return Department.get().success(function(getData) {
              $scope.departments = getData;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    $scope.editDepartment = function(id) {
      return Department.edit(id).success(function(data) {
        $scope.edit = true;
        if ($scope.edit) {
          $scope.modal_title = 'Save';
        }
        $scope.department = {
          id: data.id,
          name: data.name
        };
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.clearAll($scope.department);
    });
    return angular.element('#addNewAppModal').on('shown.bs.modal', function() {
      $('#appName').focus();
    });
  });

}).call(this);

(function() {
  angular.module('mis').controller('DesignationCtrl', function($resource, $scope, Designation, DTColumnDefBuilder, DTOptionsBuilder, prompt, $timeout, notify, $interval) {
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = ' ';
    }).withOption('responsive', true);
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(0), DTColumnDefBuilder.newColumnDef(1).notSortable()];
    Designation.get().success(function(data) {
      $scope.designations = data;
      $scope.loading = false;
    });
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.designation = {};
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.designation = {};
        }), 100);
      }
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.designation = {};
      return $scope.modal_title = 'Add';
    });
    $scope.submit = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        return Designation.save($scope.designation).success(function(data) {
          $scope.loading = false;
          $scope.submitted = false;
          $scope.designation = {};
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: 'Added successfully.',
            duration: 1500,
            position: 'right'
          });
          return Designation.get().success(function(data) {
            return $scope.designations = data;
          });
        });
      } else {
        return Designation.update($scope.designation).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.designation = {};
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            return Designation.get().success(function(getData) {
              $scope.designations = getData;
              return $scope.loading = false;
            });
          }), 500);
        });
      }
    };
    $scope.deleteDesignation = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          Designation.destroy(id).success(function(data) {
            return Designation.get().success(function(getData) {
              $scope.designations = getData;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    $scope.editDesignation = function(id) {
      return Designation.edit(id).success(function(data) {
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
        }
        $scope.designation = {
          id: data.id,
          name: data.name
        };
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.clearAll($scope.designation);
    });
    return angular.element('#addNewAppModal').on('shown.bs.modal', function() {
      $('#appName').focus();
    });
  });

}).call(this);

(function() {
  angular.module('mis').controller('EverythingCtrl', function($scope, task, $timeout, $window, notify, prompt) {
    var currentUrl, pId, tId;
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    $scope.formError = 0;
    $scope.tsk_completed = '';
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    currentUrl = $window.location.href;
    pId = currentUrl.split('/')[4] || "Undefined";
    tId = currentUrl.split('/')[6] || "Undefined";
    task.everythingLog().success(function(data) {
      $scope.everythingLogs = data;
      $scope.logs_date = data;
      return $scope.loading = false;
    });
    $scope.submitSearch = function(form) {
      $scope.loading = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      return task.searchEverything($scope.searchForm).success(function(data) {
        $scope.everythingLogs = data;
        $scope.logs_date = data;
        return $scope.loading = false;
      });
    };
    task.getCat().success(function(data) {
      $scope.taskcategories = data;
      return $scope.loading = false;
    });
    $scope.Pro_Id = pId;
    $scope.task_completed = function(id, completed) {
      task.completed(id, completed).success(function(data) {});
      if (completed === true) {
        return notify({
          message: 'Task reopen',
          duration: 1500,
          position: 'right'
        });
      } else {
        return notify({
          message: 'Task completed',
          duration: 1500,
          position: 'right'
        });
      }
    };
    $scope.showLogModal = function(event, id) {
      $scope.task_id = id;
      angular.element('#logTimeModal').modal('show');
    };
    $scope.cancelAll = function() {
      angular.element('#addNewAppModal').modal('hide');
      $timeout((function() {
        $scope.submitted = false;
        $scope.edit = false;
        return $scope.task = {};
      }), 100);
    };
    $scope.logCancel = function() {
      angular.element('#logTimeModal').modal('hide');
      $timeout((function() {
        $scope.submitted = false;
        $scope.edit = false;
        return $scope.logtime = {};
      }), 100);
    };
    $scope.logClearAll = function(form) {
      $scope.optionsLog = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.optionsLog).then(function() {
          angular.element('#logTimeModal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.logtime = {};
        });
      } else {
        angular.element('#logTimeModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.logtime = {};
        }), 100);
      }
    };
    $scope.secondsToTime = function(seconds) {
      var get_minutes, get_seconds, hour_min_sec, hours, minutes;
      seconds = Math.round(seconds);
      hours = Math.floor(seconds / (60 * 60));
      get_minutes = seconds % (60 * 60);
      minutes = Math.floor(get_minutes / 60);
      get_seconds = get_minutes % 60;
      seconds = Math.ceil(get_seconds);
      hour_min_sec = {
        'h': hours,
        'm': minutes,
        's': seconds
      };
      return hour_min_sec;
    };
    angular.element('#logTimeModal').on('hidden.bs.modal', function(form) {
      $scope.logtime = {};
      return $scope.modal_title = 'Add';
    });
    $scope.submitLog = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        if (tId !== 'Undefined') {
          $scope.logtime.task_id = tId;
          console.log('task detail task_id' + $scope.logtime.task_id);
        } else {
          $scope.logtime.task_id = $scope.task_id;
          console.log($scope.logtime.task_id);
        }
        console.log($scope.logtime);
        return task.savelog($scope.logtime).success(function(data) {
          $scope.submitted = false;
          $scope.logtime = {};
          $scope.loading = false;
          angular.element('#logTimeModal').modal('hide');
          notify({
            message: 'Logtime Added successfully',
            duration: 1500,
            position: 'right'
          });
          task.show(tId, pId).success(function(data) {
            console.log(data);
            $scope.taskDetail = data.task;
            $scope.logs = data.logs;
            $scope.billable = data.billable;
            $scope.total_task_billable_hours = data.total_task_billable_hours;
            $scope.total_task_non_billable_hours = data.total_task_non_billable_hours;
            $scope.total_task_minute = data.total_task_minute;
            $scope.total_task_hours = data.total_task_hours;
            return console.log($scope.total_task_hours);
          });
          return task.get(pId).success(function(data) {
            $scope.tasks = data.tasks;
            $scope.users = data.users;
            return $scope.loading = false;
          });
        });
      } else {
        return task.updatelog($scope.logtime).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.logtime = {};
          $scope.loading = false;
          angular.element('#logTimeModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Logtime updated successfully',
              position: 'right',
              duration: 1500
            });
            task.show(tId, pId).success(function(data) {
              $scope.taskDetail = data.task;
              $scope.logs = data.logs;
              $scope.billable = data.billable;
              $scope.total_task_billable_hours = data.total_task_billable_hours;
              $scope.total_task_non_billable_hours = data.total_task_non_billable_hours;
              $scope.total_task_minute = data.total_task_minute;
              $scope.total_task_hours = data.total_task_hours;
              return $scope.loading = false;
            });
            return task.everythingLog().success(function(data) {
              $scope.everythingLogs = data;
              $scope.logs_date = data;
              return $scope.loading = false;
            });
          }), 100);
        });
      }
    };
    $scope.deleteLog = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          task.destroylog(lid).success(function(data) {
            return task.show(tId, pId).success(function(getData) {
              $scope.logs = getData.logs;
              $scope.billable = getData.billable;
              $scope.total_task_billable_hours = getData.total_task_billable_hours;
              $scope.total_task_non_billable_hours = getData.total_task_non_billable_hours;
              $scope.total_task_minute = getData.total_task_minute;
              $scope.total_task_hours = getData.total_task_hours;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    return $scope.editLog = function(id) {
      $scope.task_id = id;
      $scope.edit = true;
      if ($scope.edit === true) {
        $scope.modal_title = 'Save';
      }
      angular.element('#logTimeModal').modal('show');
      return task.editlog(id).success(function(data) {
        $scope.edit = true;
        return $scope.logtime = data;
      });
    };
  });

}).call(this);

(function() {
  angular.module('mis').controller('IndustryCtrl', function($scope, Industry, DTOptionsBuilder, DTColumnDefBuilder, $interval, prompt, $timeout, notify) {
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = ' ';
    }).withOption('responsive', true);
    $scope.industry = {};
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(0), DTColumnDefBuilder.newColumnDef(1).notSortable()];
    Industry.get().success(function(data) {
      $scope.industries = data;
      $scope.loading = false;
    });
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.industry = {};
      return $scope.modal_title = 'Add';
    });
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.industry = {};
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.industry = {};
        }), 100);
      }
    };
    $scope.submit = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        return Industry.save($scope.industry).success(function(data) {
          $scope.loading = false;
          $scope.submitted = false;
          $scope.industry = {};
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: 'Added successfully.',
            duration: 1500,
            position: 'right'
          });
          return Industry.get().success(function(getData) {
            $scope.industries = getData;
            return $scope.loading = false;
          });
        });
      } else {
        return Industry.update($scope.industry).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.industry = {};
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            return Industry.get().success(function(getData) {
              $scope.industries = getData;
              return $scope.loading = false;
            });
          }), 10);
        });
      }
    };
    $scope.deleteIndustry = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          Industry.destroy(id).success(function(data) {
            return Industry.get().success(function(getData) {
              $scope.industries = getData;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    $scope.editIndustry = function(id) {
      return Industry.edit(id).success(function(data) {
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
        }
        $scope.industry = {
          id: data.id,
          name: data.name
        };
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.clearAll($scope.industry);
    });
    return angular.element('#addNewAppModal').on('shown.bs.modal', function() {
      $('#appName').focus();
    });
  });

}).call(this);

(function() {
  angular.module('mis').controller('milestoneCtrl', function($scope, milestone, $filter, $timeout, $window, notify, $resource, $http) {
    var currentUrl, pId;
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    $scope.modal_title = 'Add';
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    currentUrl = $window.location.href;
    pId = currentUrl.split('/')[4] || "Undefined";
    milestone.get(pId).success(function(data) {
      $scope.milestones = data.milestones;
      $scope.users = [];
      angular.forEach(data.users, function(value, key) {
        if (value.id !== 0) {
          if (value.people.lname) {
            $scope.users.push({
              id: value.id,
              label: value.people.fname + " " + value.people.lname
            });
          } else {
            $scope.users.push({
              id: value.id,
              label: value.people.fname
            });
          }
        }
      });
      return $scope.loading = false;
    });
    $scope.example14model = [];
    $scope.example14settings = {
      externalIdProp: ''
    };
    $scope.Pro_Id = pId;
    $scope.milestone_completed = function(id, completed) {
      milestone.completed(id, completed).success(function(data) {});
      if (completed === true) {
        notify({
          message: 'Milestone reopen',
          duration: 1500,
          position: 'right'
        });
      } else {
        notify({
          message: 'Milestone completed',
          duration: 1500,
          position: 'right'
        });
      }
    };
    $scope.clearAll = function() {
      angular.element('#addNewAppModal').modal('hide');
      $timeout((function() {
        $scope.submitted = false;
        $scope.edit = false;
        return $scope.milestone = {};
      }), 100);
    };
    $scope.submit = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        $scope.milestone.project_id = pId;
        $scope.milestone.user_id = [];
        angular.forEach($scope.example14model, function(value, key) {
          $scope.milestone.user_id.push(value.id);
        });
        return milestone.save($scope.milestone).success(function(data) {
          $scope.submitted = false;
          $scope.milestone = {};
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: "Added successfully",
            duration: 1500,
            position: 'right'
          });
          $scope.example14model.length = 0;
          return milestone.get(pId).success(function(getData) {
            $scope.milestones = getData.milestones;
            $scope.users = [];
            angular.forEach(getData.users, function(value, key) {
              if (value.id !== 0) {
                if (value.people.lname) {
                  $scope.users.push({
                    id: value.id,
                    label: value.people.fname + " " + value.people.lname
                  });
                } else {
                  $scope.users.push({
                    id: value.id,
                    label: value.people.fname
                  });
                }
              }
            });
            return $scope.loading = false;
          });
        });
      } else {
        $scope.milestone.user_id = [];
        angular.forEach($scope.example14model, function(value, key) {
          $scope.milestone.user_id.push(value.id);
        });
        return milestone.update($scope.milestone).success(function(data) {
          console.log(data);
          $scope.submitted = false;
          $scope.edit = false;
          $scope.milestone = {};
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: "Updated successfully",
              duration: 1500,
              position: 'right'
            });
            return milestone.get(pId).success(function(getData) {
              $scope.milestones = getData.milestones;
              $scope.users = [];
              angular.forEach(getData.users, function(value, key) {
                if (value.id !== 0) {
                  if (value.people.lname) {
                    $scope.users.push({
                      id: value.id,
                      label: value.people.fname + " " + value.people.lname
                    });
                  } else {
                    $scope.users.push({
                      id: value.id,
                      label: value.people.fname
                    });
                  }
                }
              });
              return $scope.loading = false;
            });
          }), 100);
        });
      }
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function(form) {
      $scope.clearAll(form);
      $scope.modal_title = 'Add';
      return $scope.example14model.length = 0;
    });
    $scope.deleteMilestone = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          milestone.destroy(id).success(function(data) {
            return milestone.get(pId).success(function(getData) {
              $scope.milestones = getData.milestones;
              $scope.users = [];
              angular.forEach(getData.users, function(value, key) {
                if (value.id !== 0) {
                  if (value.people.lname) {
                    $scope.users.push({
                      id: value.id,
                      label: value.people.fname + " " + value.people.lname
                    });
                  } else {
                    $scope.users.push({
                      id: value.id,
                      label: value.people.fname
                    });
                  }
                }
              });
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe ', 'info');
        }
      });
    };
    return $scope.editMilestone = function(id) {
      return milestone.edit(id).success(function(data) {
        $scope.milestone = data.milestone;
        $scope.milestone.due_date1 = data.milestone.due_date;
        $scope.milestone.due_date = $filter('date')(data.milestone.due_date, 'dd-MM-yyyy');
        $scope.users = [];
        angular.forEach(data.allUsers, function(value, key) {
          if (value.id !== 0) {
            if (value.people.lname) {
              $scope.users.push({
                id: value.id,
                label: value.people.fname + " " + value.people.lname
              });
            } else {
              $scope.users.push({
                id: value.id,
                label: value.people.fname
              });
            }
          }
        });
        $scope.example14model = [];
        angular.forEach(data.milestone_users, function(value, key) {
          if (value.id !== 0) {
            if (value.lname !== null) {
              $scope.example14model.push({
                id: value.user_id,
                label: value.fname + ' ' + value.lname
              });
            } else {
              $scope.example14model.push({
                id: value.user_id,
                label: value.fname
              });
            }
          }
        });
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
        }
        return angular.element('#addNewAppModal').modal('show');
      });
    };
  });

}).call(this);

(function() {
  angular.module('mis').controller('PeopleCtrl', function($scope, PEOPLE, $interval, DTOptionsBuilder, DTColumnDefBuilder, $timeout, prompt, $window, notify) {
    var currentUrl, gender, is_projectlead, is_teamlead, pId, uploader;
    $scope.loading = true;
    $scope.edit = false;
    $scope.formError = 0;
    $scope.max_date = new Date();
    gender = 'male';
    is_teamlead = false;
    is_projectlead = false;
    angular.element('#people_dob').attr('placeholder', 'Select Date of Birth');
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('simple_numbers').withOption('order', [1, 'asc']).withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = '';
    }).withOption('responsive', true);
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(0).notSortable(), DTColumnDefBuilder.newColumnDef(6).notSortable()];
    $scope.dtOptions1 = DTOptionsBuilder.newOptions().withPaginationType('simple_numbers').withOption('order', [1, 'asc']).withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = ' ';
    }).withColVis().withColVisOption('aiExclude', [1]).withOption('responsive', true);
    $scope.dtColumnDefs1 = [DTColumnDefBuilder.newColumnDef(1).notSortable(), DTColumnDefBuilder.newColumnDef(2).notSortable()];
    $scope.dtOptions2 = DTOptionsBuilder.newOptions().withPaginationType('simple_numbers').withOption('order', [1, 'asc']).withOption('stateSave', true).withOption('responsive', true);
    $scope.dtColumnDefs2 = [DTColumnDefBuilder.newColumnDef(0).notSortable()];
    $scope.maxDate = new Date;
    currentUrl = $window.location.href;
    pId = currentUrl.split('/')[4] || "Undefined";
    $scope.Pro_Id = pId;
    angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>");
    $scope.people_array = {};
    $scope.educations = [{}];
    $scope.experiences = [{}];
    $scope.marital_status = [];
    $scope.marital_status.push({
      name: 'Single'
    });
    $scope.marital_status.push({
      name: 'Married'
    });
    $scope.marital_status.push({
      name: 'Other'
    });
    $scope.newItem = function($event) {
      $scope.educations.push({});
      return $event.preventDefault();
    };
    $scope.nextItem = function($event) {
      $scope.experiences.push({});
      return $event.preventDefault();
    };
    $scope.sort = function(keyname) {
      $scope.sortKey = keyname;
      $scope.reverse = !$scope.reverse;
    };
    uploader = new plupload.Uploader({
      runtimes: 'html5,flash,silverlight,html4',
      browse_button: 'pickfiles',
      url: "../plupload/upload.php ",
      flash_swf_url: "../plupload/Moxie.swf ",
      silverlight_xap_url: "../plupload/Moxie.xap ",
      multi_selection: false,
      max_file_size: '20mb',
      init: {
        PostInit: function() {
          angular.element('#filelist').innerHTML = '';
          return uploader.refresh();
        },
        FilesAdded: function(up, files) {
          console.log('file added');
          $scope.loading = true;
          angular.forEach(files, function(file) {
            var ext, filename, index, name;
            filename = file.name;
            ext = filename.substring(filename.lastIndexOf('.') + 1);
            index = filename.lastIndexOf('.');
            name = filename.substr(0, index).replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
            return file.name = name + '.' + ext;
          });
          return uploader.start();
        },
        UploadProgress: function(up, file) {
          console.log('file upload progress');
          angular.element('#photo').val(file.name);
          return console.log(file.name);
        },
        UploadComplete: function(up, files) {
          console.log('file completed');
          console.log($scope.loading = false);
          return $timeout((function() {
            return angular.forEach(files, function(file) {
              angular.element('#preview').html('<div id="fileadded" class="' + file.id + '"><div id="' + file.id + '"> <div class="avtar inline" style="vertical-align:middle"><div class="img avatar-md"><img src=/tmp/' + file.name + '></div></div><span class="filesize">(' + plupload.formatSize(file.size) + ')</span><a href="javascript:;" class="btn btn-md btn-close removeFile ng-click=shownoimage()" id="' + file.id + '">Remove</a></div></div>');
              return angular.element('a#' + file.id).on('click', function() {
                up.removeFile(file);
                angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>");
              });
            });
          }), 1000);
        },
        Error: function(up, err) {
          return alert("Error #" + err.code + ": " + err.message);
        }
      }
    });
    uploader.init();
    PEOPLE.get(pId).success(function(data) {
      $scope.peoples = data.peoples;
      $scope.departments = data.departments;
      $scope.designations = data.designations;
      return $scope.loading = false;
    });
    PEOPLE.getCountry().success(function(data) {
      return $scope.countries = data;
    });
    $scope.selected_users = [];
    PEOPLE.getProjectPeople(pId).success(function(data) {
      $scope.selected_users = data.user_ids;
      return $scope.projectPeople = data.project_users;
    });
    $scope.toggleSelection = function(user) {
      var idx;
      idx = $scope.selected_users.indexOf(user);
      if (idx > -1) {
        return $scope.selected_users.splice(idx, 1);
      } else {
        return $scope.selected_users.push(user);
      }
    };
    $scope.addPeopleToProject = function() {
      return PEOPLE.addPeopleToProject($scope.selected_users, $scope.Pro_Id).success(function(data) {
        angular.element('#addPeopleToProjectModal').modal('hide');
        notify({
          message: 'Added successfully.',
          duration: 1500,
          position: 'right'
        });
        return PEOPLE.getProjectPeople(pId).success(function(user_data) {
          console.log(data);
          $scope.selected_users = user_data.user_ids;
          return $scope.projectPeople = user_data.project_users;
        });
      });
    };
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        return prompt($scope.options).then(function() {
          var myEl;
          angular.element('#people_modal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          $scope.people_array = {};
          $scope.formError = 0;
          $scope.people_array.email = '';
          myEl = angular.element(document.querySelector('#fileadded'));
          myEl.remove();
          return angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>");
        });
      } else {
        angular.element('#people_modal').modal('hide');
        $scope.educations = [
          {
            qualification: '',
            collage: '',
            university: '',
            passing_year: '',
            percentage: ''
          }
        ];
        $scope.experiences = [
          {
            company_name: '',
            from: '',
            to: '',
            salary: '',
            reason: ''
          }
        ];
        $timeout((function() {
          var myEl;
          $scope.submitted = false;
          $scope.edit = false;
          $scope.people_array = {};
          myEl = angular.element(document.querySelector('#fileadded'));
          myEl.remove();
          return angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>");
        }), 100);
      }
    };
    $scope.email_error = false;
    $scope.submit = function(form) {
      var errors;
      errors = form.$error;
      angular.forEach(errors, function(val) {
        if (angular.isArray(val)) {
          $scope.formError += val.length;
        }
      });
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        $scope.people_array.photo = angular.element('#photo').val();
        $scope.people_array.gender = $scope.gender;
        $scope.people_array.is_teamlead = $scope.is_teamlead;
        $scope.people_array.is_projectlead = $scope.is_projectlead;
        return PEOPLE.save($scope.people_array, $scope.is_projectlead, $scope.educations, $scope.experiences).success(function(data) {
          var myEl;
          if (data['error']) {
            $scope.email_error = true;
            $scope.user_email_error = data['messages'].email;
            $scope.submitted = false;
            return $scope.loading = false;
          } else {
            $scope.email_error = false;
            $scope.submitted = false;
            $scope.people_array = {};
            $scope.formError = 0;
            angular.element('#people_modal').modal('hide');
            myEl = angular.element(document.querySelector('#fileadded'));
            myEl.remove();
            angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>");
            return $timeout((function() {
              notify({
                message: 'Added successfully.',
                duration: 1500,
                position: 'right'
              });
              return PEOPLE.get(pId).success(function(getData) {
                $scope.peoples = getData.peoples;
                $scope.countries = getData.countries;
                return $scope.loading = false;
              });
            }), 100);
          }
        });
      } else {
        $scope.people_array.photo = angular.element('#photo').val();
        $scope.people_array.gender = $scope.gender;
        $scope.people_array.is_teamlead = $scope.is_teamlead;
        $scope.people_array.is_projectlead = $scope.is_projectlead;
        return PEOPLE.update($scope.people_array, $scope.is_projectlead, $scope.educations, $scope.experiences).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.people_array = {};
          $scope.formError = 0;
          angular.element('#people_modal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            return PEOPLE.get(pId).success(function(getData) {
              $scope.peoples = getData.peoples;
              $scope.countries = getData.countries;
              return $scope.loading = false;
            });
          }), 100);
        });
      }
    };
    angular.element('#people_modal').on('hidden.bs.modal', function(form) {
      $scope.formError = 0;
      angular.element('#people_mobile,#people_email').val('');
      $scope.clearAll(form);
      $scope.gender = 'male';
      $scope.is_teamlead = false;
      $scope.is_projectlead = false;
      $scope.email_error = false;
      $scope.people_array.gender = gender;
      $scope.people_array.photo = '';
      $scope.modal_title = 'Add';
      angular.element('#people_mobile,#people_email').val('');
      angular.element('#people_dob').attr('placeholder', 'Select Date of Birth');
      angular.element(".my-tabs>li").removeClass("active");
      angular.element('#default-home').addClass('active');
      angular.element('.my-tabs>ul>li a').attr('aria-expanded', false);
      angular.element('#home1').attr('aria-expanded', true);
      angular.element('.tab-content>div').removeClass('active');
      angular.element('.tab-content #tab_1').addClass('active');
      angular.element(".user_profile_detail .my-tabs1>li").removeClass("active");
      angular.element('.user_profile_detail #default-detail-home').addClass('active');
      angular.element('.user_profile_detail .my-tabs1>li a').attr('aria-expanded', false);
      angular.element('#detail_1').attr('aria-expanded', true);
      angular.element('.user_profile_detail .tab-content>div').removeClass('active');
      angular.element('.user_profile_detail .tab-content #detail_1').addClass('active');
      return uploader.refresh();
    });
    angular.element('#addPeopleToProjectModal').on('hidden.bs.modal', function(form) {
      return PEOPLE.getProjectPeople(pId).success(function(data) {
        return $scope.selected_users = data.user_ids;
      });
    });
    angular.element('#view_user_profile').on('hidden.bs.modal', function() {
      angular.element(".user_profile_detail .my-tabs1>li").removeClass("active");
      angular.element('.user_profile_detail #default-detail-home').addClass('active');
      angular.element('.user_profile_detail .my-tabs1>li a').attr('aria-expanded', false);
      angular.element('#detail_1').attr('aria-expanded', true);
      angular.element('.user_profile_detail .tab-content>div').removeClass('active');
      angular.element('.user_profile_detail .tab-content #detail_1').addClass('active');
    });
    angular.element('#people_modal').on('shown.bs.modal', function() {
      $('#fname').focus();
    });
    $scope.deletePeople = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          PEOPLE.destroy(id).success(function(data) {
            return PEOPLE.get().success(function(getData) {
              $scope.peoples = getData.peoples;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    $scope.statusChange = function(id) {
      $scope.loading = true;
      return PEOPLE.statusChanged(id).success(function(data) {
        return PEOPLE.get().success(function(getData) {
          $scope.peoples = getData.peoples;
          return $scope.loading = false;
        });
      });
    };
    $scope.editPeople = function(id) {
      return PEOPLE.edit(id).success(function(data) {
        if (data[0].photo === void 0 || data[0].photo === null || data[0].photo === '') {
          angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div class='img avatar-md'><img src='img/user.png'></div></div>");
        } else {
          angular.element('#preview').html("<div class='avtar inline' style='vertical-align:middle'><div  class='img avatar-md'><img src=/uploads/people/" + data[0].photo + "></div></div>");
          angular.element('#photo').val(data[0].photo);
        }
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
        }
        $scope.people_array = data[0];
        $scope.gender = data[0].gender;
        $scope.is_teamlead = data[7];
        $scope.is_projectlead = data[0].user.is_projectlead;
        $scope.people_array.email = data[1];
        $scope.educations = data[2];
        $scope.experiences = data[3];
        $scope.departments = data[4];
        $scope.people_array.roles = data[6];
        $scope.people_array.is_teamlead = data[7];
        $scope.people_array.is_projectlead = data[0].user.is_projectlead;
        if ($scope.people_array.department_id === 0) {
          $scope.people_array.department_id = "";
        }
        if ($scope.people_array.designation_id === 0) {
          $scope.people_array.designation_id = "";
        }
        if ($scope.people_array.management_level === '0') {
          $scope.people_array.management_level = "";
        }
        return angular.element('#people_modal').modal('show');
      });
    };
    $scope.viewPeople = function(id) {
      return PEOPLE.view_people(id).success(function(data) {
        $scope.user_profile_detail = data[0];
        $scope.user_email = data[1];
        $scope.user_educations = data[2];
        $scope.user_experiences = data[3];
        angular.element('#view_user_profile').modal('show');
      });
    };
    $scope.removeEducationClone = function(education) {
      var index;
      index = $scope.educations.indexOf(education);
      return $scope.educations.splice(index, 1);
    };
    $scope.removeEducation = function(education) {
      $scope.options = {
        title: 'Remove Education',
        message: 'Are you sure you want to delete this education detail?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      return prompt($scope.options).then(function() {
        PEOPLE.destroyEducation(education.id).success(function(data) {
          var index;
          index = $scope.educations.indexOf(education);
          return $scope.educations.splice(index, 1);
        });
        return notify({
          message: 'Removed successfully.',
          duration: 1500,
          position: 'right'
        });
      });
    };
    $scope.removeExperience = function(id) {
      $scope.options = {
        title: 'Remove Experience',
        message: 'Are you sure you want to delete this experience detail?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      return prompt($scope.options).then(function() {
        PEOPLE.destroyExperience(id).success(function(data) {
          var index;
          index = $scope.experiences.indexOf(id);
          return $scope.experiences.splice(index, 1);
        });
        return notify({
          message: 'Removed successfully.',
          duration: 1500,
          position: 'right'
        });
      });
    };
    $scope.removeExperienceClone = function(experience) {
      var index;
      index = $scope.experiences.indexOf(experience);
      return $scope.experiences.splice(index, 1);
    };
    $scope.submitLogin = function(form) {
      $scope.loading = true;
      $scope.loginSubmitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      return PEOPLE.login($scope.login_array).success(function(data) {
        if (data.error) {
          $scope.credential_error = data.msg;
          $scope.loading = false;
          $scope.loginSubmitted = false;
          return;
        }
        if (data.success) {
          window.location.href = '/';
          $scope.loading = false;
          return $scope.loginSubmitted = false;
        }
      });
    };
    return $scope.submitForgotPassword = function(form) {
      $scope.loading = true;
      $scope.forgotPasswordSubmitted = true;
      $scope.credential_error = "";
      $scope.success_msg = "";
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      return PEOPLE.fogotPassword($scope.forgotPasswod_array).success(function(data) {
        if (data.error) {
          $scope.credential_error = data.msg;
          $scope.loading = false;
          $scope.forgotPasswordSubmitted = false;
          return;
        }
        if (data.success) {
          $scope.success_msg = data.msg;
          $scope.forgotPasswod_array = {};
          $scope.loading = false;
          return $scope.forgotPasswordSubmitted = false;
        }
      });
    };
  });

}).call(this);

(function() {
  angular.module('mis').controller('ProjectCategoryCtrl', function($scope, DTOptionsBuilder, DTColumnDefBuilder, projectCategory, prompt, $timeout, notify) {
    $scope.loading = true;
    $scope.edit = false;
    $scope.project_category = {};
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = ' ';
    }).withOption('responsive', true);
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(0), DTColumnDefBuilder.newColumnDef(1).notSortable()];
    projectCategory.get().success(function(data) {
      $scope.categories = data;
      $scope.loading = false;
    });
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.project_category = {};
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.project_category = {};
        }), 100);
      }
    };
    $scope.submit = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        return projectCategory.save($scope.project_category).success(function(data) {
          $scope.loading = false;
          $scope.submitted = false;
          $scope.project_category = {};
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: 'Added successfully.',
            duration: 1500,
            position: 'right'
          });
          return projectCategory.get().success(function(getData) {
            $scope.categories = getData;
            return $scope.loading = false;
          });
        });
      } else {
        return projectCategory.update($scope.project_category).success(function(data) {
          $scope.loading = false;
          $scope.submitted = false;
          $scope.edit = false;
          $scope.project_category = {};
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            return projectCategory.get().success(function(getData) {
              $scope.categories = getData;
              return $scope.loading = false;
            });
          }), 500);
        });
      }
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.project_category.name = '';
      return $scope.modal_title = 'Add';
    });
    $scope.deleteCategory = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          projectCategory.destroy(id).success(function(data) {
            return projectCategory.get().success(function(getData) {
              $scope.categories = getData;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    $scope.editCategory = function(id) {
      return projectCategory.edit(id).success(function(data) {
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
        }
        $scope.project_category = {
          id: data.id,
          name: data.name
        };
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.clearAll($scope.project_category.name);
    });
    return angular.element('#addNewAppModal').on('shown.bs.modal', function() {
      $('#appName').focus();
    });
  });

}).call(this);

(function() {
  angular.module('mis').controller('ProjectCtrl', function($scope, $interval, PROJECT, $timeout, prompt, $window, notify) {
    var currentUrl, pId, price_types, projectHours, projectlead_id;
    $scope.loading = false;
    $scope.currentPage = 1;
    projectHours = 0;
    $scope.totalPages = 0;
    $scope.range = [];
    $scope.edit = false;
    price_types = 'per_hour';
    projectlead_id = '';
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
      angular.element('.status-detail').hide();
    }
    currentUrl = $window.location.href;
    pId = currentUrl.split('/')[4] || "Undefined";
    $scope.Pro_Id = pId;
    $scope.formError = 0;
    angular.element('#fix_hours').hide();
    $scope.showModal = function(event) {
      console.log(event);
      $scope.client_id = event.target.id;
      angular.element('#addNewAppModal').modal('show');
    };
    $scope.viewHours = function() {
      angular.element('#fix_hours').show();
      if ($scope.edit === true) {
        $scope.project_array.fix_hours = projectHours;
      }
    };
    $scope.hideHours = function() {
      angular.element('#fix_hours').hide();
      $scope.project_array.fix_hours = 0;
    };
    $scope.cancelAll = function() {
      angular.element('#addNewAppModal').modal('hide');
      $timeout((function() {
        $scope.submitted = false;
        if ($scope.edit === false) {
          $scope.modal_title = 'Add';
          angular.element('.status-detail').hide();
        }
        $scope.project_array = {};
        angular.element('.status-detail').hide();
        return $scope.formError = 0;
      }), 1000);
    };
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          $scope.formError = 0;
          if ($scope.edit === false) {
            $scope.modal_title = 'Add';
            angular.element('.status-detail').hide();
          }
          $scope.project_array = {};
          angular.element('.status-detail').hide();
          return $scope.formError = 0;
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.formError = 0;
          $scope.project_array = {};
          return $scope.formError = 0;
        }), 100);
      }
    };
    $scope.submit = function(form) {
      var errors;
      $scope.loading = true;
      $scope.submitted = true;
      errors = form.$error;
      angular.forEach(errors, function(val) {
        if (angular.isArray(val)) {
          $scope.formError += val.length;
        }
      });
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        $scope.project_array.price_types = $scope.price_types;
        if ($scope.client_id) {
          $scope.project_array.client_id = $scope.client_id;
          $scope.project_array.projectlead_id = $scope.projectlead_id;
        }
        return PROJECT.save($scope.project_array).success(function(data) {
          $scope.submitted = false;
          $scope.project_array = {};
          $scope.formError = 0;
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: 'Added successfully.',
            duration: 1500,
            position: 'right'
          });
          $scope.formError = 0;
          PROJECT.getCompany().success(function(data) {
            $scope.companies = data.companies;
            return $scope.loading = false;
          });
          return PROJECT.get().success(function(getData) {
            $scope.projects = getData.projects;
            $scope.projectsCategories = getData.projectsCategories;
            $scope.loading = false;
            return window.location.href = '/projects';
          });
        });
      } else {
        $scope.project_array.price_types = $scope.price_types;
        $scope.project_array.client_id = $scope.client_id;
        $scope.project_array.projectlead_id = $scope.projectlead_id;
        return PROJECT.update($scope.project_array).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.formError = 0;
          if ($scope.edit === false) {
            $scope.modal_title = 'Add';
            angular.element('.status-detail').hide();
          }
          $scope.formError = 0;
          $scope.project_array = {};
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            PROJECT.getCompany().success(function(dataCompany) {
              $scope.companies = dataCompany.companies;
              return $scope.loading = false;
            });
            PROJECT.get().success(function(getData) {
              $scope.projects = getData.projects;
              $scope.projectsCategories = getData.projectsCategories;
              return $scope.loading = false;
            });
            return window.location.href = '/projects';
          }), 500);
        });
      }
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function(form) {
      $scope.clearAll(form);
      $scope.modal_title = 'Add';
      $scope.price_types = price_types;
      $scope.client_id = '';
      $scope.projectlead_id = '';
      $scope.formError = 0;
      angular.element(".my-tabs>li").removeClass("active");
      angular.element('#default-home').addClass('active');
      angular.element('.my-tabs>li a').attr('aria-expanded', false);
      angular.element('#home').attr('aria-expanded', true);
      angular.element('.tab-content>div').removeClass('active');
      return angular.element('.tab-content #home').addClass('active');
    });
    $scope.deleteProject = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          PROJECT.destroy(id).success(function(data) {
            window.location.href = '/projects';
            PROJECT.getCompany().success(function(dataCompany) {
              $scope.companies = dataCompany.companies;
              return $scope.loading = false;
            });
            return PROJECT.get().success(function(getData) {
              $scope.projects = getData.projects;
              $scope.projectsCategories = getData.projectsCategories;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal("Cancelled", "Your record is safe", "info");
        }
      });
    };
    $scope.editProject = function(id) {
      return PROJECT.edit(id).success(function(data) {
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
          angular.element('.status-detail').show();
        }
        $scope.project_array = data;
        $scope.price_types = data.price_types;
        projectHours = data.fix_hours;
        $scope.client_id = data.client_id;
        $scope.projectlead_id = data.projectlead_id;
        $scope.projCategoryryId = data.category_id;
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    $scope.showProjectCategory = function() {
      angular.element('#addProjectCategory').modal('show');
    };
    $scope.submitProjectCategory = function(form) {
      $scope.submittedCategory = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      return PROJECT.saveProjectCategory($scope.project_category).success(function(data) {
        console.log($scope.project_category);
        $scope.submittedCategory = false;
        $scope.project_category = {};
        $scope.loading = false;
        angular.element('#addProjectCategory').modal('hide');
        notify({
          message: 'Added successfully.',
          duration: 1500,
          position: 'right'
        });
        PROJECT.getCompany().success(function(dataCompany) {
          $scope.companies = dataCompany.companies;
          return $scope.loading = false;
        });
        return PROJECT.get().success(function(getData) {
          return $scope.projectsCategories = getData.projectsCategories;
        });
      });
    };
    return $scope.clearProjectCategory = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addProjectCategory').modal('hide');
          $scope.submittedCategory = false;
          return $scope.project_category = {};
        });
      } else {
        angular.element('#addProjectCategory').modal('hide');
        $timeout((function() {
          $scope.submittedCategory = false;
          return $scope.project_category = {};
        }), 100);
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').controller('ResourceCtrl', function($resource, $scope, Resource, DTOptionsBuilder, DTColumnDefBuilder, prompt, $timeout, notify, $http) {
    var formatToPercentage;
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = ' ';
    }).withOption('responsive', true);
    $scope.resouce = {};
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(0), DTColumnDefBuilder.newColumnDef(1).notSortable()];
    formatToPercentage = function(value) {
      return value + '%';
    };
    $scope.slider = {
      options: {
        ceil: 100,
        floor: 0,
        step: 10,
        translate: formatToPercentage,
        showSelectionBar: true,
        showTicks: true,
        getTickColor: function(value) {
          if (value < 50) {
            return 'red';
          }
          if (value < 70) {
            return 'orange';
          }
          if (value < 100) {
            return 'yellow';
          }
          return '#2AE02A';
        }
      }
    };
    Resource.get().success(function(data) {
      $scope.resouces = data;
      $scope.loading = false;
    });
    $scope.clearForm = function() {
      $scope.resouce = {};
    };
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.resouce = {};
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.resouce = {};
        }), 100);
      }
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.resouce = {};
      return $scope.modal_title = 'Add';
    });
    $scope.submit = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        return Resource.save($scope.resouce).success(function(data) {
          $scope.loading = false;
          $scope.submitted = false;
          $scope.resouce = {};
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: 'Added successfully.',
            duration: 1500,
            position: 'right'
          });
          return Resource.get().success(function(getData) {
            $scope.resouces = getData;
            return $scope.loading = false;
          });
        });
      } else {
        return Resource.update($scope.resouce).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.resouce = {};
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            return Resource.get().success(function(getData) {
              $scope.resouces = getData;
              return $scope.loading = false;
            });
          }), 500);
        });
      }
    };
    $scope.deleteResource = function(id) {
      $scope.loading = true;
      return Resource.destroy(id).success(function(data) {
        notify({
          message: 'Deleted successfully.',
          duration: 1500,
          position: 'right'
        });
        return Resource.get().success(function(getData) {
          $scope.resouces = getData;
          return $scope.loading = false;
        });
      });
    };
    $scope.editResource = function(id) {
      return Resource.edit(id).success(function(data) {
        $scope.edit = true;
        if ($scope.edit) {
          $scope.modal_title = 'Save';
        }
        $scope.resouce = {
          id: data.id,
          name: data.name
        };
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.clearAll($scope.resouce);
    });
    return angular.element('#addNewAppModal').on('shown.bs.modal', function() {
      $('#appName').focus();
    });
  });

}).call(this);

(function() {
  angular.module('mis').controller('TaskCategoryCtrl', function($scope, taskCategory, DTOptionsBuilder, DTColumnDefBuilder, $interval, $timeout, prompt, notify) {
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withOption('stateSave', true).withOption('stateLoadParams', function(setting, data) {
      return data.search.search = ' ';
    }).withOption('responsive', true);
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(0), DTColumnDefBuilder.newColumnDef(1), DTColumnDefBuilder.newColumnDef(2).notSortable()];
    taskCategory.get().success(function(data) {
      $scope.task_categories = data.categories;
      $scope.projects = data.projects;
      $scope.loading = false;
    });
    angular.element('#addNewAppModal').on('hidden.bs.modal', function() {
      $scope.project_category = {};
      return $scope.modal_title = 'Add';
    });
    $scope.cancelAll = function() {
      angular.element('#addNewAppModal').modal('hide');
      $timeout((function() {
        $scope.submitted = false;
        $scope.edit = false;
        return $scope.task_category = {};
      }), 100);
    };
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.task_category = {};
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.task_category = {};
        }), 100);
      }
    };
    $scope.submit = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        return taskCategory.save($scope.task_category).success(function(data) {
          $scope.submitted = false;
          $scope.task_category = {};
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: 'Added successfully.',
            duration: 1500,
            position: 'right'
          });
          return taskCategory.get().success(function(getData) {
            $scope.task_categories = getData.categories;
            $scope.projects = getData.projects;
            return $scope.loading = false;
          });
        });
      } else {
        return taskCategory.update($scope.task_category).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.task_category = {};
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Updated successfully.',
              duration: 1500,
              position: 'right'
            });
            return taskCategory.get().success(function(getData) {
              $scope.task_categories = getData.categories;
              $scope.projects = getData.projects;
              return $scope.loading = false;
            });
          }), 500);
        });
      }
    };
    $scope.deleteCategory = function(id) {
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          taskCategory.destroy(id).success(function(data) {
            return taskCategory.get().success(function(getData) {
              $scope.task_categories = getData.categories;
              $scope.projects = getData.projects;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    $scope.editCategory = function(id) {
      return taskCategory.edit(id).success(function(data) {
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
        }
        $scope.task_category = data;
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function(form) {
      $scope.clearAll(form);
    });
    return angular.element('#addNewAppModal').on('shown.bs.modal', function() {
      $('#appName').focus();
    });
  });

}).call(this);

(function() {
  angular.module('mis').controller('TasksCtrl', function($scope, task, $timeout, DTOptionsBuilder, DTColumnDefBuilder, $window, notify, prompt) {
    var currentUrl, pId, tId;
    $scope.loading = true;
    $scope.currentPage = 1;
    $scope.edit = false;
    $scope.formError = 0;
    $scope.tsk_completed = '';
    $scope.currentDate = new Date();
    $scope.minDate = new Date();
    $scope.single = false;
    $scope.substractDate = function(date) {
      var temp;
      temp = new Date(date);
      return $scope.minDate = new Date(temp.getFullYear(), temp.getMonth(), temp.getDate());
    };
    if ($scope.edit === false) {
      $scope.modal_title = 'Add';
    }
    $scope.dtOptions = DTOptionsBuilder.newOptions().withOption('stateLoadParams', function(setting, data) {
      return data.search.search = ' ';
    }).withOption('responsive', true).withOption('order', [0, 'desc']).withOption('lengthChange', false).withOption('paging', false);
    $scope.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(6).notSortable(), DTColumnDefBuilder.newColumnDef(7).notSortable()];
    currentUrl = $window.location.href;
    pId = currentUrl.split('/')[4] || "Undefined";
    tId = currentUrl.split('/')[6] || "Undefined";
    task.get(pId).success(function(data) {
      $scope.tasks = data.tasks;
      $scope.taskcategories = data.taskcategories;
      $scope.users = [];
      angular.forEach(data.users, function(value, key) {
        if (value.id !== 0) {
          if (value.people.lname !== null) {
            $scope.users.push({
              id: value.id,
              label: value.people.fname + " " + value.people.lname
            });
          } else {
            $scope.users.push({
              id: value.id,
              label: value.people.fname
            });
          }
        }
      });
      return $scope.loading = false;
    });
    $scope.example14model = [];
    $scope.calc_spent_time = function(et, st) {
      $scope.lg_start_time = st;
      $scope.lg_end_time = et;
      if (moment.duration(moment($scope.lg_end_time, 'HH:mm a') - moment($scope.lg_start_time, 'HH:mm a')) > 0) {
        return moment.duration(moment.duration(moment($scope.lg_end_time, 'HH:mm a') - moment($scope.lg_start_time, 'HH:mm a')), "miliseconds").format("h [hrs] m [min]");
      } else {
        return '0 min';
      }
    };
    $scope.example14settings = {
      externalIdProp: ''
    };
    task.show(tId, pId).success(function(data) {
      $scope.taskDetail = data.task;
      $scope.tsk1.completed = $scope.taskDetail.completed;
      $scope.logs = data.logs;
      $scope.billable = data.billable;
      $scope.total_task_billable_hours = data.total_task_billable_hours;
      $scope.total_task_non_billable_hours = data.total_task_non_billable_hours;
      $scope.total_task_minute = data.total_task_minute;
      $scope.total_task_hours = data.total_task_hours;
      return $scope.loading = false;
    });
    $scope.Pro_Id = pId;
    $scope.task_completed = function(id, completed) {
      task.completed(id, completed).success(function(data) {});
      if (completed === true) {
        notify({
          message: 'Task completed',
          duration: 1500,
          position: 'right'
        });
        return task.get($scope.Pro_Id).success(function(data) {
          $scope.tasks = data.tasks;
          $scope.taskcategories = data.taskcategories;
          $scope.users = [];
          return angular.forEach(data.users, function(value, key) {
            if (value.id !== 0) {
              if (value.people.lname !== null) {
                $scope.users.push({
                  id: value.id,
                  label: value.people.fname + " " + value.people.lname
                });
              } else {
                $scope.users.push({
                  id: value.id,
                  label: value.people.fname
                });
              }
            }
          });
        });
      } else {
        task.get($scope.Pro_Id).success(function(data) {
          $scope.tasks = data.tasks;
          $scope.taskcategories = data.taskcategories;
          $scope.users = [];
          return angular.forEach(data.users, function(value, key) {
            if (value.id !== 0) {
              if (value.people.lname !== null) {
                $scope.users.push({
                  id: value.id,
                  label: value.people.fname + " " + value.people.lname
                });
              } else {
                $scope.users.push({
                  id: value.id,
                  label: value.people.fname
                });
              }
            }
          });
        });
        return notify({
          message: 'Task reopen',
          duration: 1500,
          position: 'right'
        });
      }
    };
    $scope.showModal = function(event) {
      $scope.task.category_id = event.target.id;
      angular.element('#addNewAppModal').modal('show');
      task.get(pId).success(function(data) {
        $scope.users = [];
        if (data.loginUser.user_id !== 0) {
          if (data.loginUser.lname !== null) {
            $scope.example14model.push({
              id: data.loginUser.user_id,
              label: data.loginUser.fname + " " + data.loginUser.lname
            });
          } else {
            $scope.example14model.push({
              id: data.loginUser.user_id,
              label: data.loginUser.fname
            });
          }
        }
        console.log(data.loginUser.user_id);
        angular.forEach(data.users, function(value, key) {
          if (value.id !== 0) {
            if (value.people.lname !== null) {
              $scope.users.push({
                id: value.id,
                label: value.people.fname + " " + value.people.lname
              });
            } else {
              $scope.users.push({
                id: value.id,
                label: value.people.fname
              });
            }
          }
        });
        return $scope.loading = false;
      });
    };
    $scope.showTaskCategoryModal = function(event) {
      angular.element('#addTaskCategoryModal').modal('show');
      $scope.loading = false;
      return $scope.task_category_name = '';
    };
    $scope.showLogModal = function(event, id) {
      $scope.task_id = id;
      $scope.project_id = pId;
      $scope.logtimeInit();
      angular.element('#logTimeModal').modal('show');
    };
    $scope.cancelAll = function() {
      angular.element('#addNewAppModal').modal('hide');
      $timeout((function() {
        $scope.submitted = false;
        $scope.edit = false;
        return $scope.task = {};
      }), 100);
    };
    $scope.clearAll = function(form) {
      $scope.options = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'Ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.options).then(function() {
          angular.element('#addNewAppModal').modal('hide');
          if ($scope.edit === false) {
            angular.element('.task-select .select2-container').select2('val', '');
          }
          $scope.submitted = false;
          $scope.formError = 0;
          $scope.edit = false;
          return $scope.task = {};
        });
      } else {
        angular.element('#addNewAppModal').modal('hide');
        angular.element('.task-select .select2-container').select2('val', '');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.task = {};
          $scope.formError = 0;
          return $scope.task.priority = "medium";
        }), 100);
      }
    };
    $scope.logCancel = function() {
      angular.element('#logTimeModal').modal('hide');
      $timeout((function() {
        $scope.submitted = false;
        $scope.edit = false;
        return $scope.logtime = {};
      }), 100);
    };
    $scope.logClearAll = function(form) {
      $scope.optionsLog = {
        title: 'You have changes.',
        message: 'Are you sure you want to discard changes?',
        input: false,
        label: '',
        value: '',
        values: false,
        buttons: [
          {
            label: 'ok',
            primary: true
          }, {
            label: 'Cancel',
            cancel: true
          }
        ]
      };
      if (form.$dirty) {
        prompt($scope.optionsLog).then(function() {
          angular.element('#logTimeModal').modal('hide');
          $scope.submitted = false;
          return $scope.edit = false;
        });
      } else {
        angular.element('#logTimeModal').modal('hide');
        $timeout((function() {
          $scope.submitted = false;
          $scope.edit = false;
          return $scope.logtimeInit();
        }), 500);
      }
    };
    $scope.submitTaskCategory = function(form) {
      var errors;
      $scope.loading = true;
      $scope.submitted = true;
      errors = form.$error;
      angular.forEach(errors, function(val) {
        if (angular.isArray(val)) {
          $scope.formError += val.length;
        }
      });
      if (form.$invalid) {
        $scope.loading = false;
      } else {
        $scope.loading = true;
        $scope.task_category = {};
        $scope.task_category.name = $scope.task_category_name;
        $scope.task_category.project_id = $scope.Pro_Id;
        return task.saveTaskCategory($scope.task_category).success(function(data) {
          $scope.submitted = false;
          $scope.task_category = {};
          angular.element('#addTaskCategoryModal').modal('hide');
          notify({
            message: 'Added successfully.',
            duration: 1500,
            position: 'right'
          });
          return task.get(pId).success(function(response) {
            $scope.tasks = response.tasks;
            $scope.taskcategories = response.taskcategories;
            $scope.users = [];
            angular.forEach(response.users, function(value, key) {
              if (value.id !== 0) {
                if (value.people.lname !== null) {
                  $scope.users.push({
                    id: value.id,
                    label: value.people.fname + " " + value.people.lname
                  });
                } else {
                  $scope.users.push({
                    id: value.id,
                    label: value.people.fname
                  });
                }
              }
            });
            return $scope.loading = false;
          });
        });
      }
    };
    $scope.submit = function(form) {
      var errors;
      $scope.loading = true;
      $scope.submitted = true;
      errors = form.$error;
      angular.forEach(errors, function(val) {
        if (angular.isArray(val)) {
          $scope.formError += val.length;
        }
      });
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        $scope.task.user_id = [];
        angular.forEach($scope.example14model, function(value, key) {
          $scope.task.user_id.push(value.id);
        });
        $scope.task.project_id = pId;
        return task.save($scope.task).success(function(data) {
          $scope.submitted = false;
          $scope.task = {};
          $scope.formError = 0;
          angular.element('#addNewAppModal').modal('hide');
          notify({
            message: 'Task added successfully',
            duration: 1500,
            position: 'right'
          });
          $scope.example14model.length = 0;
          return task.get(pId).success(function(getData) {
            $scope.tasks = getData.tasks;
            $scope.users = [];
            angular.forEach(getData.users, function(value, key) {
              if (value.id !== 0) {
                if (value.people.lname !== null) {
                  $scope.users.push({
                    id: value.id,
                    label: value.people.fname + " " + value.people.lname
                  });
                } else {
                  $scope.users.push({
                    id: value.id,
                    label: value.people.fname
                  });
                }
              }
            });
            return $scope.loading = false;
          });
        });
      } else {
        $scope.task.user_id = [];
        angular.forEach($scope.example14model, function(value, key) {
          $scope.task.user_id.push(value.id);
        });
        return task.update($scope.task).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.task = {};
          $scope.formError = 0;
          angular.element('#addNewAppModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Task updated successfully',
              duration: 1500,
              position: 'right'
            });
            $scope.example14model.length = 0;
            task.show(tId, pId).success(function(data) {
              return $scope.taskDetail = data.task;
            });
            return task.get(pId).success(function(getData) {
              $scope.tasks = getData.tasks;
              $scope.users = [];
              angular.forEach(getData.users, function(value, key) {
                if (value.id !== 0) {
                  if (value.people.lname !== null) {
                    $scope.users.push({
                      id: value.id,
                      label: value.people.fname + " " + value.people.lname
                    });
                  } else {
                    $scope.users.push({
                      id: value.id,
                      label: value.people.fname
                    });
                  }
                }
              });
              return $scope.loading = false;
            });
          }), 100);
        });
      }
    };
    $scope.secondsToTime = function(seconds) {
      var get_minutes, get_seconds, hour_min_sec, hours, minutes;
      seconds = Math.round(seconds);
      hours = Math.floor(seconds / (60 * 60));
      get_minutes = seconds % (60 * 60);
      minutes = Math.floor(get_minutes / 60);
      get_seconds = get_minutes % 60;
      seconds = Math.ceil(get_seconds);
      hour_min_sec = {
        'h': hours,
        'm': minutes,
        's': seconds
      };
      return hour_min_sec;
    };
    angular.element('#addNewAppModal').on('hidden.bs.modal', function(form) {
      $scope.clearAll(form);
      $scope.modal_title = 'Add';
      $scope.example14model.length = 0;
      $scope.formError = 0;
      $scope.task.priority = 'medium';
      angular.element("#addNewAppModal .my-tabs>li.active").removeClass("active");
      angular.element('#default-home').addClass('active');
      angular.element('#addNewAppModal .my-tabs>li a').attr('aria-expanded', false);
      angular.element('#home').attr('aria-expanded', true);
      angular.element('#addNewAppModal .tab-content>div').removeClass('active');
      return angular.element('#addNewAppModal .tab-content #home').addClass('active');
    });
    angular.element('#logTimeModal').on('hidden.bs.modal', function(form) {
      $scope.logtimeInit();
      return $scope.modal_title = 'Add';
    });
    angular.element('#logTimeModal').on('shown.bs.modal', function(form) {
      return $scope.logtimeInit();
    });
    $scope.logtimeInit = function() {
      var current_time, current_time2, d;
      if ($scope.logtime.date) {
        $scope.logtime.date;
      } else {
        $scope.logtime.date = moment($scope.currentDate.toDateString()).format('DD-MM-YYYY');
      }
      if ($scope.logtime.description) {
        $scope.logtime.discription;
      } else {
        $scope.logtime.discription = '';
      }
      current_time = '';
      current_time2 = '';
      d = '';
      d = $scope.currentDate;
      if ($scope.logtime.start_time) {
        current_time = moment($scope.logtime.start_time, 'hh::mm A').format('hh:mm A');
      } else {
        current_time = moment($scope.currentDate, 'hh::mm A').format('hh:mm A');
        angular.element('#timepicker_1').timepicker({
          minuteStep: 5,
          snapToStep: true,
          defaultTime: current_time,
          forceRoundTime: true
        });
      }
      if ($scope.logtime.end_time) {
        return current_time2 = moment($scope.logtime.end_time, 'hh::mm A').format('hh:mm A');
      } else {
        current_time2 = moment($scope.currentDate, 'hh::mm A').format('hh:mm A');
        return angular.element('#timepicker_2').timepicker({
          minuteStep: 5,
          snapToStep: true,
          defaultTime: current_time2,
          forceRoundTime: true
        });
      }
    };
    $scope.toggleStatus = function() {
      return task.update($scope.task.status).success(function(data) {
        return task.get(pId).success(function(getData) {
          $scope.tasks = getData.tasks;
          return $scope.users = getData.users;
        });
      });
    };
    $scope.submitLog = function(form) {
      $scope.loading = true;
      $scope.submitted = true;
      if (form.$invalid) {
        $scope.loading = false;
        return;
      } else {
        $scope.loading = true;
      }
      if ($scope.edit === false) {
        if (tId !== 'Undefined') {
          $scope.logtime.task_id = tId;
          $scope.logtime.project_id = $scope.project_id;
        } else {
          $scope.logtime.task_id = $scope.task_id;
          $scope.logtime.project_id = $scope.project_id;
        }
        return task.savelog($scope.logtime, pId).success(function(data) {
          $scope.submitted = false;
          $scope.logtimeInit();
          $scope.logtime.description = '';
          $scope.loading = false;
          angular.element('#logTimeModal').modal('hide');
          notify({
            message: 'Logtime Added successfully',
            duration: 1500,
            position: 'right'
          });
          task.show(tId, pId).success(function(data) {
            $scope.taskDetail = data.task;
            $scope.logs = data.logs;
            $scope.billable = data.billable;
            $scope.total_task_billable_hours = data.total_task_billable_hours;
            $scope.total_task_non_billable_hours = data.total_task_non_billable_hours;
            $scope.total_task_minute = data.total_task_minute;
            return $scope.total_task_hours = data.total_task_hours;
          });
          return task.get(pId).success(function(data) {
            $scope.tasks = data.tasks;
            $scope.users = data.users;
            return $scope.loading = false;
          });
        });
      } else {
        return task.updatelog($scope.logtime).success(function(data) {
          $scope.submitted = false;
          $scope.edit = false;
          $scope.logtime = {};
          $scope.loading = false;
          angular.element('#logTimeModal').modal('hide');
          return $timeout((function() {
            notify({
              message: 'Logtime updated successfully',
              position: 'right',
              duration: 1500
            });
            return task.show(tId, pId).success(function(data) {
              $scope.taskDetail = data.task;
              $scope.logs = data.logs;
              $scope.billable = data.billable;
              $scope.total_task_billable_hours = data.total_task_billable_hours;
              $scope.total_task_non_billable_hours = data.total_task_non_billable_hours;
              $scope.total_task_minute = data.total_task_minute;
              $scope.total_task_hours = data.total_task_hours;
              return $scope.loading = false;
            });
          }), 100);
        });
      }
    };
    $scope.deleteTask = function(id) {
      $scope.loading = false;
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          task.destroy(id).success(function(data) {
            task.get(pId).success(function(getData) {
              $scope.loading = false;
              $scope.tasks = getData.tasks;
              $scope.users = [];
              return angular.forEach(getData.users, function(value, key) {
                if (value.id !== 0) {
                  if (value.people.lname !== null) {
                    $scope.users.push({
                      id: value.id,
                      label: value.people.fname + " " + value.people.lname
                    });
                  } else {
                    $scope.users.push({
                      id: value.id,
                      label: value.people.fname
                    });
                  }
                }
              });
            });
            if ($scope.single) {
              return window.location.href = '/projects/' + pId + '/tasks';
            }
          });
          if ($scope.single === false) {
            $scope.loading = false;
            return swal("Deleted!", "Your record has been deleted.", "success");
          }
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    $scope.deleteSingleTask = function(id) {
      $scope.single = true;
      $scope.loading = true;
      return $scope.deleteTask(id);
    };
    $scope.editTask = function(id) {
      return task.edit(id, pId).success(function(data) {
        $scope.edit = true;
        if ($scope.edit === true) {
          $scope.modal_title = 'Save';
        }
        $scope.task = data.task;
        $scope.users = [];
        angular.forEach(data.allUsers, function(value, key) {
          if (value.id !== 0) {
            if (value.people.lname !== null) {
              $scope.users.push({
                id: value.id,
                label: value.people.fname + " " + value.people.lname
              });
            } else {
              $scope.users.push({
                id: value.id,
                label: value.people.fname
              });
            }
          }
        });
        $scope.task.priority = data.task.priority;
        $scope.example14model = [];
        angular.forEach(data.task_users, function(value, key) {
          if (value.id !== 0) {
            if (value.lname !== null) {
              $scope.example14model.push({
                id: value.user_id,
                label: value.fname + ' ' + value.lname
              });
            } else {
              $scope.example14model.push({
                id: value.user_id,
                label: value.fname
              });
            }
          }
        });
        return angular.element('#addNewAppModal').modal('show');
      });
    };
    $scope.deleteLog = function(lid) {
      $scope.loading = false;
      return swal({
        title: 'Are you Sure?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        timer: 7000,
        showCancelButton: true
      }).then(function(result) {
        if (result.value) {
          $scope.loading = true;
          task.destroylog(lid).success(function(data) {
            return task.show(tId, pId).success(function(getData) {
              $scope.logs = getData.logs;
              $scope.billable = getData.billable;
              $scope.total_task_billable_hours = getData.total_task_billable_hours;
              $scope.total_task_non_billable_hours = getData.total_task_non_billable_hours;
              $scope.total_task_minute = getData.total_task_minute;
              $scope.total_task_hours = getData.total_task_hours;
              return $scope.loading = false;
            });
          });
          return swal("Deleted!", "Your record has been deleted.", "success");
        } else if (result.dismiss === swal.DismissReason.cancel) {
          return swal('Cancelled', 'Your record is safe', 'info');
        }
      });
    };
    $scope.editLog = function(id) {
      $scope.logtimeInit();
      $scope.task_id = id;
      $scope.edit = true;
      if ($scope.edit === true) {
        $scope.modal_title = 'Save';
      }
      angular.element('#logTimeModal').modal('show');
      return task.editlog(id).success(function(data) {
        $scope.edit = true;
        $scope.logtime = data.logtime;
        return $scope.username = data.username;
      });
    };
    $scope.getUserName = function(id) {
      return task.getName(id).success(function(data) {
        return data.fname;
      });
    };
    $scope.getTaskName = function(id) {
      return task.getTName(id).success(function(data) {
        return data.name;
      });
    };
    return $scope.changeBillable = function(id, value) {
      $scope.loading = true;
      task.changeBillabled(id, value).success(function(data) {
        return task.show(tId, pId).success(function(data) {
          $scope.billable = data.billable;
          $scope.total_task_billable_hours = data.total_task_billable_hours;
          $scope.total_task_non_billable_hours = data.total_task_non_billable_hours;
          $scope.total_task_minute = data.total_task_minute;
          $scope.total_task_hours = data.total_task_hours;
          return $scope.loading = false;
        });
      });
    };
  });

}).call(this);

(function() {
  angular.module('mis').controller('ThemeCtrl', function($scope, Theme, $interval, prompt, $timeout, notify) {
    return $scope.changeTheme = function(class_name) {
      $scope.loading = true;
      return Theme.update(class_name).success(function(data) {
        $scope.loading = true;
        angular.element("body").removeAttr("class").addClass(data.class_name);
        return notify({
          message: 'Theme changed successfully.',
          duration: 1500,
          position: 'right'
        });
      });
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('categoryTask', function($http) {
    return {
      get: function(cId) {
        return $http.get('/api/task-category-modal-users-list', {
          params: {
            category_id: cId
          }
        });
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/task-category-modal-add-task',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('company', function($http) {
    return {
      get: function() {
        return $http.get('api/companies');
      },
      getCountry: function() {
        return $http.get('api/country');
      },
      getIndustry: function() {
        return $http.get('api/industries');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: 'companies',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id) {
        return $http.get('api/company/' + id);
      },
      update: function(formData) {
        return $http({
          method: 'PUT',
          url: 'companies/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(id) {
        return $http["delete"]('companies/' + id);
      },
      getCompany: function(id) {
        return $http.get('api/company/' + id);
      },
      showCompany: function(id) {
        return $http.get('api/showCompany/' + id);
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('Department', function($http) {
    return {
      get: function() {
        return $http.get('/api/departments');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/departments',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id) {
        return $http.get('/api/department/' + id);
      },
      update: function(formData) {
        return $http({
          method: 'PUT',
          url: '/departments/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(id) {
        return $http["delete"]('/departments/' + id);
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('Designation', function($http) {
    return {
      get: function() {
        return $http.get('/api/designations');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/designations',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id) {
        return $http.get('/api/designation/' + id);
      },
      update: function(formData) {
        return $http({
          method: 'PUT',
          url: '/designations/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(id) {
        return $http["delete"]('/designations/' + id);
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('everything', function($http) {
    return {
      get: function(pId) {
        return $http.get('/api/tasks', {
          params: {
            project_id: pId
          }
        });
      },
      everythingLog: function() {
        return $http.get('/api/everything');
      },
      searchEverything: function(formData) {
        return $http({
          method: 'POST',
          url: '/searchEverything',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      getCat: function() {
        return $http.get('/api/task-categories');
      },
      save: function(formData) {
        console.log(formData);
        return $http({
          method: 'POST',
          url: '/projects/' + formData.id + '/tasks',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      show: function(tId, pId) {
        return $http.get('/api/projects/' + pId + '/tasks/' + tId, {
          params: {
            project_id: pId,
            task_id: tId
          }
        });
      },
      savelog: function(formData, tId) {
        return $http({
          method: 'POST',
          url: '/logtimes',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData, {
            'task_id': tId
          })
        });
      },
      edit: function(id, pId) {
        return $http.get('/api/task/' + id, {
          params: {
            project_id: pId
          }
        });
      },
      getTask: function(id, pId) {
        return $http.get('/api/task/' + id, {
          params: {
            project_id: pId
          }
        });
      },
      editlog: function(id, tId) {
        return $http.get('/api/logtime/' + id, {
          params: {
            task_id: tId
          }
        });
      },
      update: function(formData, id) {
        return $http({
          method: 'PUT',
          url: '/projects/' + formData.project_id + '/tasks/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      updatelog: function(formData, id) {
        return $http({
          method: 'PUT',
          url: '/logtimes/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(pId, id) {
        return $http["delete"]('/projects/' + pId + '/tasks/' + id);
      },
      destroylog: function(id) {
        return $http["delete"]('/logtimes/' + id);
      },
      completed: function(id, status) {
        return $http({
          method: 'POST',
          url: '/task-status',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            id: id,
            completed: status
          })
        });
      },
      getName: function(id) {
        return $http.get('/api/people-name' + id);
      },
      getTName: function(id) {
        return $http.get('/api/task-name' + id);
      },
      changeBillabled: function(id, value) {
        return $http({
          method: 'POST',
          url: '/log-billable',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            id: id,
            billable: value
          })
        });
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('Industry', function($http) {
    return {
      get: function() {
        return $http.get('/api/industries');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/industries',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id) {
        return $http.get('/api/industry/' + id);
      },
      update: function(formData) {
        return $http({
          method: 'PUT',
          url: '/industries/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(id) {
        return $http["delete"]('/industries/' + id);
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('milestone', function($http) {
    return {
      get: function(pId) {
        return $http.get('/api/milestones', {
          params: {
            project_id: pId
          }
        });
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/projects/' + formData.id + '/milestones',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id, pId) {
        return $http.get('/api/milestone/' + id, {
          params: {
            project_id: pId
          }
        });
      },
      update: function(formData, id) {
        return $http({
          method: 'PUT',
          url: '/projects/' + formData.project_id + '/milestones/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(pId, id) {
        return $http["delete"]('/projects/' + pId + '/milestones/' + id);
      },
      completed: function(id, status) {
        return $http({
          method: 'POST',
          url: '/milestone-status',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            id: id,
            completed: status
          })
        });
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('PEOPLE', function($http) {
    return {
      get: function() {
        return $http.get('/api/people');
      },
      getCountry: function() {
        return $http.get('/api/country');
      },
      save: function(formData, educations, experiences) {
        return $http({
          method: 'POST',
          url: '/people',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            is_teamlead: formData.is_teamlead,
            is_projectlead: formData.is_projectlead,
            user_detail: formData,
            educations: educations,
            experiences: experiences
          })
        });
      },
      getProjectPeople: function(id) {
        return $http.get('/api/project-people/' + id);
      },
      addPeopleToProject: function(users, project_id) {
        return $http({
          method: 'POST',
          url: '/add-people-to-project',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            users: users,
            project_id: project_id
          })
        });
      },
      statusChanged: function(id) {
        return $http({
          method: 'POST',
          url: '/user-status-changed',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            id: id
          })
        });
      },
      edit: function(id) {
        return $http.get('/api/people/' + id);
      },
      view_people: function(id) {
        return $http.get('/api/viewpeople/' + id);
      },
      update: function(formData, educations, experiences) {
        return $http({
          method: 'PUT',
          url: '/people/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            is_teamlead: formData.is_teamlead,
            is_projectlead: formData.is_projectlead,
            roles: formData.roles,
            user_detail: formData,
            educations: educations,
            experiences: experiences
          })
        });
      },
      destroy: function(id) {
        return $http["delete"]('/people/' + id);
      },
      destroyEducation: function(id) {
        return $http["delete"]('/education/' + id);
      },
      destroyExperience: function(id) {
        return $http["delete"]('/experience/' + id);
      },
      login: function(formData) {
        return $http({
          method: 'POST',
          url: '/login',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      fogotPassword: function(forgotPasswordData) {
        return $http({
          method: 'POST',
          url: '/password/email',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(forgotPasswordData)
        });
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('projectCategory', function($http) {
    return {
      get: function() {
        return $http.get('/api/project-categories');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/project-categories',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id) {
        return $http.get('/api/project-category/' + id);
      },
      update: function(formData) {
        return $http({
          method: 'PUT',
          url: '/project-categories/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(id) {
        return $http["delete"]('/project-categories/' + id);
      },
      getProjects: function(id) {
        return $http.get('/api/project-categories-projects' + id);
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('PROJECT', function($http) {
    return {
      get: function() {
        return $http.get('/api/projects');
      },
      getCompany: function() {
        return $http.get('/api/companies');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/projects',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id) {
        return $http.get('/api/projects/' + id);
      },
      update: function(formData) {
        return $http({
          method: 'PUT',
          url: '/projects/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(id) {
        return $http["delete"]('/projects/' + id);
      },
      saveProjectCategory: function(formData) {
        return $http({
          method: 'POST',
          url: '/project-categories',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('Resource', function($http) {
    return {
      get: function() {
        return $http.get('/api/resources');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/resources',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id) {
        return $http.get('/api/resource/' + id);
      },
      update: function(formData) {
        return $http({
          method: 'PUT',
          url: '/resources/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(id) {
        return $http["delete"]('/resources/' + id);
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('taskCategory', function($http) {
    return {
      get: function() {
        return $http.get('/api/task-categories');
      },
      getTask: function() {
        return $http.get('/api/tasks');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/task-categories',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      edit: function(id) {
        return $http.get('/api/task-category/' + id);
      },
      update: function(formData) {
        return $http({
          method: 'PUT',
          url: '/task-categories/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(id) {
        return $http["delete"]('/task-categories/' + id);
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('task', function($http) {
    return {
      get: function(pId) {
        return $http.get('/api/tasks', {
          params: {
            project_id: pId
          }
        });
      },
      getCat: function() {
        return $http.get('/api/task-categories');
      },
      save: function(formData) {
        return $http({
          method: 'POST',
          url: '/projects/' + formData.id + '/tasks',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      saveTaskCategory: function(formData) {
        return $http({
          method: 'POST',
          url: '/add-task-category',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      show: function(tId, pId) {
        return $http.get('/api/projects/' + pId + '/tasks/' + tId, {
          params: {
            project_id: pId,
            task_id: tId
          }
        });
      },
      savelog: function(formData, tId) {
        return $http({
          method: 'POST',
          url: '/logtimes',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData, {
            'task_id': tId
          })
        });
      },
      edit: function(id, pId) {
        return $http.get('/api/task/' + id, {
          params: {
            project_id: pId
          }
        });
      },
      getTask: function(id, pId) {
        return $http.get('/api/task/' + id, {
          params: {
            project_id: pId
          }
        });
      },
      editlog: function(id, tId) {
        return $http.get('/api/logtime/' + id, {
          params: {
            task_id: tId
          }
        });
      },
      update: function(formData, id) {
        return $http({
          method: 'PUT',
          url: '/projects/' + formData.project_id + '/tasks/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      updatelog: function(formData, id) {
        return $http({
          method: 'PUT',
          url: '/logtimes/' + formData.id,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param(formData)
        });
      },
      destroy: function(pId, id) {
        return $http["delete"]('/projects/' + pId + '/tasks/' + id);
      },
      destroylog: function(id) {
        return $http["delete"]('/logtimes/' + id);
      },
      completed: function(id, status) {
        return $http({
          method: 'POST',
          url: '/task-status',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            id: id,
            completed: status
          })
        });
      },
      getName: function(id) {
        return $http.get('/api/people-name' + id);
      },
      getTName: function(id) {
        return $http.get('/api/task-name' + id);
      },
      changeBillabled: function(id, value) {
        return $http({
          method: 'POST',
          url: '/log-billable',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            id: id,
            billable: value
          })
        });
      }
    };
  });

}).call(this);

(function() {
  angular.module('mis').factory('Theme', function($http) {
    return {
      update: function(class_name) {
        return $http({
          method: 'POST',
          url: '/change-theme',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          data: $.param({
            name: class_name
          })
        });
      }
    };
  });

}).call(this);

//# sourceMappingURL=app.js.map
