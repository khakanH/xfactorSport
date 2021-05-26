@extends('layouts.app')
@section('content')
<style type="text/css">
   .uploaded_img_file {
    border: 0 none; 
    display: inline-block; 
    height: auto; 
    max-width: 25%;
    vertical-align: middle;
    margin: 7px;
  }
</style>

<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Edit Employee')}}</h1>
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
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('employee.update-employee')}}">
                @csrf
                <div class="card-body">
                 
                  <input type="hidden" name="id" id="id" value="{{$employee->id}}">


                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="name">{{__('web.Name')}}</label>
                      <input type="text" class="form-control" id="name" name="name" required="" value="@if(old('name')){{old('name')}}@else{{$employee->name}}@endif">
                    </div>
                    <div class="col-md-4">
                      <label for="email">{{__('web.Email')}}</label>
                      <input type="email" class="form-control" id="email" name="email" required="" value="@if(old('email')){{old('email')}}@else{{$employee->email}}@endif">
                    </div>
                    <div class="col-md-4">
                      <label for="phone">{{__('web.Phone')}}</label>
                      <input type="text" class="form-control" id="phone" name="phone" required="" value="@if(old('phone')){{old('phone')}}@else{{$employee->phone}}@endif">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="dob">{{__('web.Date of Birth')}}</label>
                      <input type="date" class="form-control datepicker_dropdown" id="dob" name="dob" required="" value="@if(old('dob')){{old('dob')}}@else{{$employee->dob}}@endif">
                    </div>
                    <div class="col-md-4">
                      <label for="nationality">{{__('web.Nationality')}}</label>
                      <input id="nationality" name="nationality" list="ntnly" class="form-control" required value="@if(old('nationality')){{old('nationality')}}@else{{$employee->nationality}}@endif">
                      <datalist id="ntnly">
                            <?php
                              $list = (new App\Helpers\MenuHelper)->getNationality();
                            ?>
                      </datalist>
                    </div>
                    <div class="col-md-4">
                      <label for="national_id">{{__('web.National ID')}}</label>
                      <input type="number" class="form-control" id="national_id" name="national_id" required="" value="@if(old('national_id')){{old('national_id')}}@else{{$employee->national_id}}@endif">
                    </div>
                  </div>




                  <div class="form-group row">
                   

                    <div class="col-md-4">
                      <label for="job_type">{{__('web.Job_Type')}}</label>
                      <select class="form-control" name="job_type" id="job_type" required="" onchange="ShowSportsInput(this.value)">
                        <option value="">Select Job Type</option>
                        @foreach($job_type as $key)
                        <option <?php if (old('job_type')==$key['id']): ?>
                            selected
                            <?php elseif($employee->job_type_id == $key['id']): ?>
                              selected
                        <?php endif; ?> value="{{$key['id']}}">{{$key['name']}}</option>
                        @endforeach
                      </select>
                    </div>


                    <div class="col-md-4" id="sport-div" style="<?php if (old('job_type') != 2 && $employee->job_type_id != 2): ?>
                      display: none;
                      <?php endif ?> ">
                       <label for="job_type">{{__('web.Sports')}}</label>
                       <select class="form-control" name="sport" id="sport" <?php if (old('job_type') == 2 && $employee->job_type_id != 2): ?>
                          required
                      <?php endif ?>>
                        <option value="">{{__('web.Select')}}</option>
                        @foreach($sport as $key)
                        <option <?php if (old('sport')==$key['id'] || $employee->sport_id == $key['id']): ?>
                            selected
                        <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
                        @endforeach
                      </select>
                    </div>


                    <div class="col-md-4">
                        <label for="job_title">{{__('web.Job Title')}}</label>
                        <input type="text" class="form-control" id="job_title" name="job_title" required="" value="@if(old('job_title')){{old('job_title')}}@else{{$employee->job_title}}@endif">
                      </div>



                    
                    
                  </div>


                  <div class="form-group row">
                   
                    <div class="col-md-4">
                      <label for="salary">{{__('web.Salary')}}</label>
                      <input type="number" class="form-control" id="salary" name="salary" required="" value="@if(old('salary')){{old('salary')}}@else{{$employee->salary - (float)$employee->commission}}@endif">
                    </div>
                    <div class="col-md-4">
                      <label for="commission">{{__('web.Commission')}}</label>
                      <input type="number" class="form-control" id="commission" name="commission" value="@if(old('commission')){{old('commission')}}@else{{$employee->commission}}@endif">
                    </div>
                     <div class="col-md-4">
                      <label for="expiry_date">{{__('web.ID Expiry Date')}}</label>
                      <input type="date" class="form-control datepicker_dropdown" id="expiry_date" name="expiry_date" required="" value="@if(old('expiry_date')){{old('expiry_date')}}@else{{$employee->id_expiry_date}}@endif">
                    </div>
                  </div>

                   <div class="form-group row">
                     <div class="col-md-4">
                        <label for="joining_date">{{__('web.Joining Date')}}</label>
                        <input type="date" class="form-control datepicker_dropdown" id="joining_date" name="joining_date" required="" autocomplete="off" value="@if(old('joining_date')){{old('joining_date')}}@else{{$employee->joining_date}}@endif">
                      </div>
                      <div class="col-md-4">
                        <label for="working_days">{{__('web.Working Days')}}</label>
                        <input type="number" class="form-control" id="working_days" name="working_days" required="" autocomplete="off" value="@if(old('working_days')){{old('working_days')}}@else{{$employee->working_days}}@endif">
                      </div>
                      <div class="col-md-4">
                        <label for="annual_leaves">{{__('web.Annual Leaves')}}</label>
                        <input type="number" class="form-control" id="annual_leaves" name="annual_leaves" required="" autocomplete="off" value="@if(old('annual_leaves')){{old('annual_leaves')}}@else{{$employee->annual_leaves}}@endif">
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
                  


                    <div class="form-group row">
                    <div class="col-md-12">
                      <label for="">{{__('web.Uploaded Files')}}:</label>
                      @if(!empty($employee->certificate_image))
                      @php($files = explode(',',$employee->certificate_image))
                      @php($count = 1)
                        @foreach($files as $file)
                        <li id="file-list-{{$count}}"><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $file ?>"><img class="uploaded_img_file" src="<?php echo config('app.img_url') ?><?php echo $file ?>"></a>&nbsp;&nbsp;&nbsp;<i class="fas fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteEmployeeFiles("{{$file}}","{{$employee->id}}","{{$count}}")'></i></li>
                        @php($count++)
                        @endforeach
                      @endif
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

  function DeleteEmployeeFiles(file,id,row_count)
  {
    var r = confirm("{{__('web.Are you sure?')}}");

    if (!r) 
    {
      return false;
    }


   $.ajax({
            type: "POST",
            cache: false,
            url: "{{ config('app.url')}}employee/delete-files",
            data:{
              file: file,
              employee_id: id,
              _token: $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend: function(){
            },
            success: function(data){
                    if (data) 
                    {
                      document.getElementById("file-list-"+row_count).style.display="none";
                    }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });
    
  }
</script>

@endsection
