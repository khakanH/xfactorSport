
@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Unions</h1>
          </div>
        </div>
      </div>
</section>

  	<section class="content">
    	<div class="container-fluid">

              @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{session('success')}}
                </div>
                {{ session()->forget('success') }}
              @elseif(session('failed'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{session('failed')}}
                </div>
                {{ session()->forget('failed') }}
              @endif


        <div class="card">
        <form id="formCafe" action="{{route('unions.save-unions')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <span class='arrow'>
                                <label class='error'></label>
                                </span>
                <div class="card-body">
                        <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Union Name') }}</label>
                                <input type="text" name="name" id="name" class="form-control" 
                                autocomplete="false" placeholder="X-Factor" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Union Fee') }}</label>
                                <input type="number" name="fee" id="fee" class="form-control" 
                                autocomplete="false" placeholder="500" required />
                            </div>
                        </div>
                        </div>
                        
                        </div>
                        <input type="hidden" name="Purchase_Price" id="Purchase_Price" class="form-control" readonly
                            autocomplete="false" required />

                        <div class="card-footer">
                        <button class="btn btn-success" id="btnSave"
                            >Save</button>
                    </div>
                    </form>
            </div>
        </div>
    </section>
  	

</div>
<script type="text/javascript">
 
  
 
</script>



@endsection


