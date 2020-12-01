<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/project/{id}/people',
        '/login',
        '/update-user-profile-photo',
        '/searchEverything',
        '/project-categories',
        '/project-categories/{project_category_id}',
        '/task-categories',
        '/people/{id}',
        '/task-categories/{task_category_id}',
        'api/task-categories/{task_category_id}',
	    'api/projects/{project_id}',
        'api/people/{id}',
        'api/exportTask',
        '/task-category-modal-add-task',
        '/resources'
    ];
}
