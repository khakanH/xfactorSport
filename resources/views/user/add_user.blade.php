@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Add New User')}}</h1>
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
              <form role="form" method="post" name="userForm" id="userForm" enctype="multipart/form-data" action="{{route('user.save-user')}}">
                @csrf
                <span class='arrow'>
                                <label class='error'></label>
                                </span>
                <div class="card-body">

                  <div class="form-group row">
                    <div class="col-lg-6">
                      <label for="name">{{__('web.Name')}}</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter User Name" required="" value="{{old('name')}}">
                    </div>
                    <div class="col-lg-6">
                      <label for="email">{{__('web.Email')}}</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter User Email" required="" value="{{old('email')}}">
                    </div>
                  </div>

                   <div class="form-group row">
                    <div class="col-lg-6">
                      <label for="password">{{__('web.Password')}}</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="******" required="" value="{{old('password')}}">
                    </div>
                    <div class="col-lg-6">
                      <label for="confirm_password">{{__('web.Confirm Password')}}</label>
                      <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="******" required="" value="{{old('confirm_password')}}">
                    </div>
                  </div>

                   <div class="form-group row">
                    <div class="col-lg-6">
                      <label for="type">{{__('web.User Type')}}</label>
                        <select id="type" name="type" class="form-control" required="" >
                          <option selected="" value="">{{__('web.Select')}}</option>
                          @foreach($user_type as $key)
                          <option <?php if (old('type') == $key['id']): ?>
                              selected
                          <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                      <label for="">{{__('web.Profile Image')}}</label>
                      <div class="custom-file">
                      <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                      <label class="custom-file-label" for="image">{{__('web.Choose file')}}</label>
                    </div>
                    </div>
                   
                  </div>
                  

                </div>

                <div class="card-footer">
                  <button type="submit" id="user-submit-btn" class="btn btn-primary">{{__('web.Save')}}</button>
                </div>
              </form>
            </div>
        </div>
    </section>
  	

</div>

<script type="text/javascript">


  
    $.validator.addMethod('emailFormat', function(value, element) {
        return this.optional(element) || (value.match(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/));
    },
    'Please enter a valid email address');
        $(function() {
        $("form[name='userForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "10px" ,"width":"100%"});
        label.insertBefore(element);
    },
    wrapper: 'span',

    rules: {
    

      email: {
        emailFormat: true,
      },
      confirm_password: {
        equalTo: "#password"


      },
     
    },
    messages: {
     
       confirm_password: {
        equalTo:"Password Mismatch",

      },
    },
    submitHandler: function(form) {

        document.getElementById("user-submit-btn").disabled = true;
        form.submit();

    }
  });
  });
</script>


@endsection
