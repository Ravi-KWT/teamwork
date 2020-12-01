<nav class="page-sidebar" data-pages="sidebar" id="leftmenu">
<div class="mCustomScrollbar" data-mcs-theme="minimal-dark" data-height="100%">
<div class="panel">
    <div class="panel-heading">Categories</div>
    <div class="panel-body">
        <ul>
            @forelse($task_categories as $task_category)
                <?php
                    $count = 0;
                    foreach($tasks as $task)
                    {
                        if($task->category_id == $task_category->id)
                        {
                            $count++;
                        }
                    }
                ?>

                <li >
                    <a href="{{url('/task-categories',$task_category->id)}}">
                    {{$task_category->name}}
                        <span class="badge">
                            {{$count}}
                        </span>
                    </a>
                </li>
            @empty
               No Category
            @endforelse
        </ul>
    </div>
</div>
</div>
</nav>
