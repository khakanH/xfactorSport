@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.General Settings')}}</h1>
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


            @if ($errors->any())
              <div class="callout callout-danger">
                  <h5>Form Submission Errors</h5>
                  @foreach ($errors->all() as $error)
                   <li class="text-danger">{{$error}}</li>
                  @endforeach
              </div>
           @endif

          <div class="card card-primary card-outline">
            <form method="post" action="{{route('setting.save-general-setting')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                


                <div class="row">
                  <div class="form-group col-md-12">
                  <label>{{__('web.Printout Head Letter')}}</label>
                  <input type="text" name="head_letter" id="head_letter" class="form-control" value="@if(old('head_letter')){{old('head_letter')}}@else{{$general_setting->printout_head_letter}}@endif">
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-md-12">
                  <label>{{__('web.System Logo')}}</label>
                  <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                        <label class="custom-file-label" for="image">{{__('web.Choose file')}}</label>
                        </div>
                  </div>
                </div>



                <hr>
                <div class="row">
                  <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-success">Save</button>
                  </div>
                </div>



            </div>
          </form>
          </div>

      
      </div>
    </section>
  	

</div>

<script type="text/javascript">

</script>

@endsection
