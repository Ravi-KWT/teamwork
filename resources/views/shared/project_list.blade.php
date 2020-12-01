<nav class="page-sidebar" data-pages="sidebar" id="leftmenu">
<div class="mCustomScrollbar" data-mcs-theme="minimal-dark" data-height="100%">
<div class="panel">
  <div class="panel-heading"><a href="#">Categories</a></div>
  <div class="panel-body">
          <ul>
            @php 
            // dd($projectsCategories);
                asort($project_categories);
            @endphp
            @forelse($project_categories as $key => $category)
                <?php
                    $count = 0;
                    foreach($projects as $project)
                    {
                        if (!empty($project->category_id)) {
                            if($project->category_id == $key)
                            {
                                $count++;
                            }
                        }
                    }
                ?>
                 <li >
                    <a href="{!!url('/project-categories',$key)!!}">{!! $category !!}
                    <span class="badge">{{$count}}</span>
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