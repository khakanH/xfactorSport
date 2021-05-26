@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Supplier</h1>
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
              <form role="form" method="post" name="userForm" id="userForm" action="{{route('supplier.update-supplier')}}">
                @csrf
                <span class='arrow'>
                                <label class='error'></label>
                                </span>
                <div class="card-body">

                <input type="hidden" name="id" id="id" value="{{$supplier_data->supplier_id}}">
                  <div class="form-group row">
                  <div class="col-md-4">
                  <div class="form-group">
                    <label>{{ __('Supplier Name') }}</label>
                    <input type="text" name="supplier_name" id="Current_Stock" class="form-control" value="@if(old('supplier_name')){{old('supplier_name')}}@else{{$supplier_data->supplier_name}}@endif" autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Supplier Address') }}</label>
                    <input type="text" name="supplier_address" id="Current_Stock" value="@if(old('supplier_address')){{old('supplier_address')}}@else{{$supplier_data->supplier_address}}@endif" class="form-control"  autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Supplier Contact') }}</label>
                    <input type="number" name="supplier_contact" id="Current_Stock" class="form-control" value="@if(old('supplier_contact')){{old('supplier_contact')}}@else{{$supplier_data->supplier_contact}}@endif"  autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-4">
                  <div class="form-group">
                    <label>{{ __('Name of Contact Person') }}</label>
                    <input type="text" name="contact_person" id="Current_Stock" class="form-control" value="@if(old('contact_person')){{old('contact_person')}}@else{{$supplier_data->contact_person}}@endif"  autocomplete="false" required/>
                  </div>
                  </div>
                  </div>
                </div>

                <div class="card-footer">
                  <button type="submit" id="user-submit-btn" class="btn btn-primary">Save</button>
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
