@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Account Settings')}}</h1>
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
        
    <form method="post" action="{{route('account.save-account-setting')}}" enctype="multipart/form-data">
      @csrf
      <div class="card-header">
                  <h3 class="card-title">
                    {{__('web.Profile Settings')}}
                  </h3>
      </div>
      <div class="card-body">



        <div class="row">
          
         
          <div class="form-group col-md-6">
            <label>{{__('web.Name')}}</label>
            <input required="" type="text" name="name" id="name" class="form-control" value="@if(old('name')){{old('name')}}@else{{$data->name}}@endif">
          </div>

           <div class="form-group col-md-6">
            <label>{{__('web.Email')}}</label>
            <input required="" type="email" name="email" id="email" class="form-control" value="@if(old('email')){{old('email')}}@else{{$data->email}}@endif">
          </div>


        </div>


        <div class="row">

          <div class="form-group col-md-12">
             <label for="">{{__('web.Profile Picture')}}</label>
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


  <div class="card card-warning card-outline">
        
    <form method="post" name="passwordForm" action="{{route('account.change-account-password')}}" enctype="multipart/form-data">
      @csrf
      <div class="card-header">
                  <h3 class="card-title">
                    {{__('web.Change Password')}}
                  </h3>
      </div>
      <div class="card-body">



        <div class="row">
                   
          <div class="form-group col-md-6">
            <label>{{__('web.Current Password')}}</label>
            <input required="" type="password" name="current_password" id="current_password" class="form-control">
          </div>


        </div>


        <div class="row">

           <div class="form-group col-md-6">
            <label>{{__('web.New Password')}}</label>
            <input required="" type="password" name="new_password" id="new_password" class="form-control">
          </div>
          
          
        </div>

         <div class="row">

           <div class="form-group col-md-6">
            <label>{{__('web.Confirm Password')}}</label>
            <input required="" type="password" name="confirm_password" id="confirm_password" class="form-control">
          </div>
          
          
        </div>
     
        <hr>
        <div class="row">
          <div class="form-group col-md-12">
            <button type="submit" id="save-btn" class="btn btn-success">Save</button>
          </div>
        </div>
      </div>
    </form>  

  </div>
      
      </div>
    </section>
  	

</div>

<script type="text/javascript">

       $(function() {
        $("form[name='passwordForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "10px" ,"width":"100%"});
        label.insertBefore(element);
    },
    wrapper: 'span',

    rules: {
    

      
      confirm_password: {
        equalTo: "#new_password"


      },
     
    },
    messages: {
     
       confirm_password: {
        equalTo:"Password Mismatch",

      },
    },
    submitHandler: function(form) {

        document.getElementById("save-btn").disabled = true;
        form.submit();

    }
  });
  });
</script>

@endsection
