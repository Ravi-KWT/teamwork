<div class="clearfix">
  @if(Session::has('status'))
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  @if (session('status'))
  <div class="alert alert-success">
    {{ session('status') }}
  </div>
  @endif
  @endif

    @if(Session::has('info'))
    <div class="alert alert-info" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  @if (session('info'))
  
    {{ session('info') }}
  
  @endif
  </div>
  @endif

    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      @if (session('success'))
      
        {{ session('success') }}
      
      @endif
      </div>
      @endif

{{--   @if(Session::has('success'))
  <div class="alert alert-success ">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @if(Session::get('success') != 1)
    {{ Session::get('success') }}
    @else
    An e-mail with the password reset has been sent.
    @endif
  </div>
  @endif --}}
{{--   @if(Session::has('error'))
  <div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @if(Session::get('error') != 1)
    {{ Session::get('error') }}
    @else
    {{ trans(Session::get('reason')) }}
    @endif
  </div>
  @endif --}}

   @if(Session::has('error'))
    <div class="alert alert-danger" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  @if (session('error'))
  
    {{ session('error') }}
  
  @endif
  </div>
  @endif
</div>

