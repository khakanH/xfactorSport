@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Add Employee')}}</h1>
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

        <div class="card">
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('employee.save-employee')}}">
                @csrf
                <div class="card-body">

                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="name">{{__('web.Name')}}</label>
                      <input type="text" class="form-control" id="name" name="name" required="" value="{{old('name')}}">
                    </div>
                    <div class="col-md-4">
                      <label for="email">{{__('web.Email')}}</label>
                      <input type="email" class="form-control" id="email" name="email" required="" value="{{old('email')}}">
                    </div>
                    <div class="col-md-4">
                      <label for="phone">{{__('web.Phone')}}</label>
                      <input type="text" class="form-control" id="phone" name="phone" required="" value="{{old('phone')}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="dob">{{__('web.Date of Birth')}}</label>
                      <input type="date" class="form-control datepicker_dropdown" name="dob" id="dob" autocomplete="off" value="{{ old('dob') }}">
                    </div>
                    <div class="col-md-4">
                      <label for="nationality">{{__('web.Nationality')}}</label>
                      <input id="nationality" name="nationality" list="ntnly" class="form-control" required value="{{ old('nationality') }}">
                      <datalist id="ntnly">
                            <?php
                              $list = (new App\Helpers\MenuHelper)->getNationality();
                            ?>
                      </datalist>
                    </div>
                    <div class="col-md-4">
                      <label for="national_id">{{__('web.National ID')}}</label>
                      <input type="number" class="form-control" id="national_id" name="national_id" required="" value="{{old('national_id')}}">
                    </div>
                  </div>

                  

                  <div class="form-group row">
                   
                    <div class="col-md-4">
                      <label for="job_type">{{__('web.Job_Type')}}</label>
                      <select class="form-control" name="job_type" id="job_type" required="" onchange="ShowSportsInput(this.value)">
                        <option value="">{{__('web.Select')}}</option>
                        @foreach($job_type as $key)
                        <option <?php if (old('job_type')==$key['id']): ?>
                            selected
                        <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-4" id="sport-div" style="<?php if (old('job_type') != 2): ?>
                      display: none;
                      <?php endif ?> ">
                       <label for="job_type">{{__('web.Sports')}}</label>
                      <select class="form-control" name="sport" id="sport" <?php if (old('job_type') == 2): ?>
                          required
                      <?php endif ?>>
                        <option value="">{{__('web.Select')}}</option>
                        @foreach($sport as $key)
                        <option <?php if (old('sport')==$key['id']): ?>
                            selected
                        <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
                        @endforeach
                      </select>
                    </div>


                      <div class="col-md-4">
                        <label for="job_title">{{__('web.Job Title')}}</label>
                        <input type="text" class="form-control" id="job_title" name="job_title" required="" value="{{old('job_title')}}">
                      </div>


                                        
                  </div>

                  <div class="form-group row">
                    
                    <div class="col-md-4">
                      <label for="salary">{{__('web.Salary')}}</label>
                      <input type="number" class="form-control" id="salary" name="salary" required="" value="{{old('salary')}}">
                    </div>
                    <div class="col-md-4">
                      <label for="commission">{{__('web.Commission')}}</label>
                      <input type="number" class="form-control" id="commission" name="commission" value="{{old('commission')}}">
                    </div>
                     <div class="col-md-4">
                      <label for="expiry_date">{{__('web.ID Expiry Date')}}</label>
                      <input type="date" class="form-control datepicker_dropdown" autocomplete="off" id="expiry_date" name="expiry_date" required="" value="{{old('expiry_date')}}">
                    </div>
                  </div>

                  <div class="form-group row">
                     <div class="col-md-4">
                        <label for="joining_date">{{__('web.Joining Date')}}</label>
                        <input type="date" class="form-control datepicker_dropdown" id="joining_date" name="joining_date" required="" autocomplete="off" value="{{old('joining_date')}}">
                      </div>
                      <div class="col-md-4">
                        <label for="working_days">{{__('web.Working Days')}}</label>
                        <input type="number" class="form-control" id="working_days" name="working_days" required="" autocomplete="off" value="{{old('working_days')}}">
                      </div>
                      <div class="col-md-4">
                        <label for="annual_leaves">{{__('web.Annual Leaves')}}</label>
                        <input type="number" class="form-control" id="annual_leaves" name="annual_leaves" required="" autocomplete="off" value="{{old('annual_leaves')}}">
                      </div>
                  </div>

                  <div class="form-group row">
                     <div class="col-md-12">
                      <label for="">{{__('web.Certificate Image')}}</label>
                      <div class="custom-file">
                      <input type="file" class="custom-file-input" id="certificate_image" name="certificate_image[]" multiple="" accept="application/msword, application/pdf, image/*">
                      <label class="custom-file-label" for="image">{{__('web.Choose file')}}</label>
                      </div>
                    </div>
                  </div>
                  

                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{__('web.Save')}}</button>
                </div>
              </form>
            </div>
        </div>
    </section>
  	

</div>
<script type="text/javascript">
 

  function ShowSportsInput(type)
  {
    if (type == 2) 
    {
      document.getElementById("sport-div").style.display = "block"; 
      document.getElementById("sport").required = true; 
    }
    else
    {
      document.getElementById("sport").required = false; 
      document.getElementById("sport-div").style.display = "none"; 
    }
  }
</script>


@endsection
