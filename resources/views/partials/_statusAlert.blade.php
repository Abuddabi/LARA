<div class="container">
  <div class="row">
    <div class="col-md-12">
      @if(session('status'))
        <div class="alert alert-danger">
          {{session('status')}}
        </div>
      @endif
    </div>
  </div>
</div>