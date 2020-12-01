<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web'], function () {
    // Route::auth();
	Auth::routes();
});

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('/', 'DashboardController@index')->name('home');
    //this is for theme setting
    Route::get('/theme','DashboardController@getTheme');
    Route::post('/change-theme', 'ThemeController@changeTheme');
    //import task list
    Route::post('/import-task-list', 'TaskCategoriesController@postImportExcel');
    // add task category
    Route::post('/add-task-category', 'TaskCategoriesController@addTaskCategory')->name('add-task-category');
    
    //Route resource for Project Categories in this route we can use store, index, update, show and destroy method
    Route::resource('/project-categories','ProjectCategoriesController',[
        'names'=>['index'=>'project-categories']]);

    //Route resource for Projects in this route we can use store, index, update, show and destroy method
    Route::resource('/projects','ProjectsController',[
        'names'=>['index'=>'projects'], 'except' => ['show']]);
    //Route resource for Tasks Categories in this route we can use store, index, update, show and destroy method
    Route::resource('/task-categories','TaskCategoriesController',[
        'names'=>['index'=>'task-categories']]);
    //Route resource for Tasks  in this route we can use store, index, update, show and destroy method
    Route::resource('projects.tasks','TasksController');
    //Route resource for milestone  in this route we can use store, index, update, show and destroy method

    Route::resource('projects.milestones','MilestonesController');

    //Route for current project list with project manager
    Route::get('/current-projects','ProjectsController@getCurrentProjects')->name('current-projects');
    //Route resource for People or user profile  in this route we can use store, index, update, show and destroy method
    Route::resource('/people','PeoplesController');
        //Route for get people into project
    Route::get('projects/{id}/people','PeoplesController@getProjectPeople');

    //Route for add people into project
    Route::post('add-people-to-project','PeoplesController@postProjectPeople');

    //Route for complete task
    Route::post('task-status','TasksController@postTaskStatus');

    //Route for Change Billable status

    Route::post('log-billable','TasksController@postLogBillable');

    //Route for complete milestone
    Route::post('milestone-status','MilestonesController@postMilestoneStatus');

    // Route for Password Update
    Route::post('change-password', 'UserController@updatePassword');

    // Route for form open change password
    Route::get('change-password', 'UserController@changePassword');

    // Route for  update User Profile
    Route::post('change-profile', 'UserController@postAccount');
    Route::post('/update-user-profile-photo','PeoplesController@updateUserProfilePhoto');
    // Route for  open form of User Profile
    Route::get('change-profile', 'UserController@getAccount');

    // Route for  Export Tasks into excel file
    Route::post('exportTask','TasksController@exportTask');

    // Route for  view all task logs
    Route::get('/everything',['as'=>'everything','uses'=>'TasksController@getEverything']);
    // Route for  view Filter log with tasks

    // Route::post('/searchEverything',['as'=>'everything','uses'=>'TasksController@searchEverything']);
    Route::get('/everything/search',['as'=>'searchEverything','uses'=>'TasksController@postSearch']);


    // Route for  add logtime
    Route::post('/logtimes','TasksController@logStore');
    // Route for start log timer
    Route::post('/start-log-timer','TasksController@startLogTimer');
    // Route for pause log timer
    Route::post('/pause-log-timer','TasksController@pauseLogTimer')->name('pause-log-timer');
    // Route for delete log timer
    Route::post('/delete-log-timer','TasksController@deleteLogTimer')->name('delete-log-timer');
    // Route for submit log timer
    Route::post('/submit-log-timer','TasksController@submitLogTimer')->name('submit-log-timer');

    // Route for  update logtime
    Route::put('/logtimes/{id}','TasksController@logUpdate');

    // Route for   delete logtime
    Route::delete('/logtimes/{id}','TasksController@logDestroy');


    // Route for   delete Eduction detail of user profile
    Route::delete('/education/{id}','PeoplesController@educationDestroy');

    // Route for   delete experience detail of user profile
    Route::delete('/experience/{id}','PeoplesController@experienceDestroy');
    Route::get('/category-wise-tasks/{id}','TasksController@categoryWiseTasks');
    Route::post('/task-category-modal-add-task','TaskCategoriesController@addTask');
    Route::get('filter-resource-availability', 'ResourceAvailabilityController@getfillterResourceAvailability')->name('filter-resource-availability');
    Route::post('/update-resource-workload','ResourceAvailabilityController@updateWorkload')->name('update-resource-workload');
     Route::resource('resources','ResourceAvailabilityController',[
    'names'=>['index'=>'resources']]);
     Route::post('/change-leave-status','ResourceAvailabilityController@changeLeaveStatus')->name('change-leave-status');
     Route::get('/user-permissions','UserController@getUserList')->name('user-permissions');
    Route::post('/change-permission','UserController@postUserPermission')->name('change-permission');
    Route::post('/user-status-changed','PeoplesController@changeStatus')->name('changeStatus');
    Route::get('/birthdays', 'UserController@getBirthdayList')->name('birthdays');
     
     Route::post('/get-projects','TasksController@getProjects')->name('get-projects');
     Route::get('/search-projects','ProjectsController@postProjects')->name('search-projects');

     //user -availability
     Route::get('/user-availability','ResourceAvailabilityController@getUserAvailability')->name('user-availability');

     Route::get('/get-available-users','ResourceAvailabilityController@getAvailableUsers')->name('get-available-users');
    
    Route::get('/user-fcm-token','UserController@userToken')->name('user-fcm-token');
});

Route::group(['middleware' => 'web',  'prefix' => 'api'], function () {
    // Route for get all compnay data
    Route::get('companies', 'CompaniesController@getCompanies');
    // Route for get compnay data
    Route::get('company/{id}','CompaniesController@getCompany');

    // Route for get compnay data
    Route::get('showCompany/{id}','CompaniesController@showCompany');

    // Route for get all designations data
    Route::get('designations', 'DesignationsController@getDesignations');
    // Route for get  designation data
    Route::get('designation/{id}','DesignationsController@getDesignation');

    // Route for get all department data
    Route::get('departments', 'DepartmentsController@getDepartments');
    // Route for get department data
    Route::get('department/{id}','DepartmentsController@getDepartment');

    // Route for get all industries data
    Route::get('industries', 'IndustriesController@getIndustries');
    // Route for get industries data
    Route::get('industry/{id}','IndustriesController@getIndustry');

    // Route for get all Project Categories data
    Route::get('project-categories', 'ProjectCategoriesController@getProjectCategories');
    // Route for get Project Category data
    Route::get('project-category/{id}','ProjectCategoriesController@getCategory');

    // Route for get all Projects data
    Route::get('projects','ProjectsController@getProjects');
    // Route for get  Project data
    Route::get('projects/{id}','ProjectsController@getProject');

    // Route for get all Task Categories data
    Route::get('task-categories', 'TaskCategoriesController@getTaskCategories');
    // Route for get  Task Category data
    Route::get('task-category/{id}','TaskCategoriesController@getTaskCategory');

    Route::get('task-category-modal-users-list','TaskCategoriesController@getUsers');

    // Route for get all Tasks  data
    Route::get('tasks', 'TasksController@getTasks');
    // Route for get Tasks  data
    Route::get('task/{id}','TasksController@getTask');
    Route::get('projects/{project_id}/tasks/{id}','TasksController@getTaskDetail');

    Route::get('task-Categories', 'TasksController@getCategories');

    // Route for get all Milestones data
    Route::get('milestones', 'MilestonesController@getMilestones');
    // Route for get Milestones  data
    Route::get('milestone/{id}','MilestonesController@getMilestone');

    // Route for get all Logtimes data
    Route::get('logtimes', 'TasksController@getLogtimes');
    // Route for get Logtime data
    Route::get('logtime/{id}','TasksController@getLogtime');

    // Route for get Task with logintimes data
    Route::get('everything', 'TasksController@getEverything');


    // Route for get all people data
    Route::get('people', 'PeoplesController@getPeoples');
    // Route for get people data
    Route::get('people/{id}','PeoplesController@getPeople');
    Route::get('viewpeople/{id}','PeoplesController@getPeople');

    // Route for get people fullname
    Route::get('people-name/{id}','PeoplesController@getFullName');

    // Route for get Task Name
    Route::get('task-name/{id}','TasksController@getTaskName');
    // Route for get project peoples data
    Route::get('project-people/{id}','PeoplesController@getPeopleofProject');
    // Route for county data
    Route::get('country', 'CountryController@getCountry');


});


/* All Admin routes start from here: */
Route::group(['middleware'=>['web','admin']], function(){
        // Route for get all compnay data
        //Route resource for Companies in this we can use store, index, update, show and destroy method
    Route::resource('/companies','CompaniesController',[
        'names'=>['index'=>'companies']]);
    Route::get('company/{id}','CompaniesController@getCompany');
    // Route for get compnay data
    Route::get('showCompany/{id}','CompaniesController@showCompany');
    //Route resource for Designations in this we can use store, index, update, show and destroy method
    Route::resource('/designations','DesignationsController',[
        'names'=>['index'=>'designations']]);
    //Route resource for Industries in this route we can use store, index, update, show and destroy method
    Route::resource('/industries','IndustriesController',[
        'names'=>['index'=>'industries']]);
    //Route resource for Departments in this route we can use store, index, update, show and destroy method
    Route::resource('/departments','DepartmentsController',[
        'names'=>['index'=>'department']]);
    Route::resource('team-members','TeamMembersController',[
        'names'=>['index'=>'team-members']]);
    Route::get('projects-list/{status}','ProjectsController@getProjectList')->name('projects-list');
    Route::post('change-project-status','ProjectsController@postChangeProjectStatus')->name('change-project-status');
    

});



Route::group(['middleware' => ['web','admin'],  'prefix' => 'api'], function () {
    Route::get('designations', 'DesignationsController@getDesignations');
    // Route for get  designation data
    Route::get('designation/{id}','DesignationsController@getDesignation');
    // Route for get all department data
    Route::get('departments', 'DepartmentsController@getDepartments');
    // Route for get department data
    Route::get('department/{id}','DepartmentsController@getDepartment');
    // Route for get all industries data
    Route::get('industries', 'IndustriesController@getIndustries');
    // Route for get industries data
    Route::get('industry/{id}','IndustriesController@getIndustry');

    Route::get('country', 'CountryController@getCountry');

});
