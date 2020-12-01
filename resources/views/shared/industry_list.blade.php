<nav class="page-sidebar" data-pages="sidebar">
  <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
 
  <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
  <!-- BEGIN SIDEBAR MENU HEADER-->
  <div class="sidebar-header">
    <img src="{!! asset('img/logo_white.png')!!}" alt="logo" class="brand" data-src="{!! asset('img/logo_white.png')!!}" data-src-retina="{!! asset('img/logo_white_2x.png')!!}" width="78" height="22">
    <div class="sidebar-header-controls">
      </button>
      <button type="button" class="btn btn-link visible-lg-inline" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
      </button>
    </div>
  </div>
  <!-- END SIDEBAR MENU HEADER-->
  <!-- START SIDEBAR MENU -->
  <div class="sidebar-menu">
    <!-- BEGIN SIDEBAR MENU ITEMS-->
    <ul class="menu-items">
      <li class="m-t-30 ">
        <a href="{!! url('/') !!}" class="detailed">
          <span class="title">Dashboard</span>
        </a>
        <span class="icon-thumbnail bg-success dashboard-icon"><i class="icon-dashboard"></i></span>
      </li>
      <li>
        <div class="view company_sidebar">
          <div  class="list-view boreded no-top-border">
            <div class="list-view-group-container">
              <ul>
                @foreach($projects as $project)
                  @foreach($project->users as $user)
                    @if($user->id == Auth::user()->id)
                      @foreach($companies as $company)
                        @if($project->client_id == $company->id)
                          @foreach($industries as $industry)
                            @if($company->industry_id == $industry->id)
                              <!-- BEGIN Categories List  !-->
                              <li class="chat-user categories_p clearfix">
                                <span>{!! strtoupper($industry->name) !!}</span>
                                <span class="pill">{!! DB::table('companies')->where('industry_id', $industry->id)->groupBy('industry_id')->count()!!}</span>
                                <ul>
                                  <li><a href="{!! url('/companies',$company->id)!!}">{!! ucwords($company->name) !!}</a></li>
                                </ul>
                              </li>
                            @endif
                          @endforeach
                        @endif
                      @endforeach
                    @endif
                  @endforeach
                @endforeach
                <!-- END Categories List  !-->
              </ul>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <div class="clearfix"></div>
  </div>
  <!-- END SIDEBAR MENU -->
</nav>