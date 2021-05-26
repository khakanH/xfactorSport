@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Edit Leave')}}</h1>
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
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('leave.update-leave')}}">
                @csrf
                <div class="card-body">
                  <input type="hidden" name="id" id="id" value="{{$leave->id}}">
                  <input type="hidden" name="employee_id" id="employee_id" value="@if(old('employee_id')){{old('employee_id')}}@else{{$leave->employee_id}}@endif">
                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="name">{{__('web.Name')}}</label>
                     <input autocomplete="off" id="name" name="name" list="employee_list" class="form-control" required value="@if(old('name')){{old('name')}}@else{{$leave->employee_data->name}}@endif">

                      <datalist id="employee_list" >

                      @foreach($employee as $key)
                      <option data-value="{{$key['id']}}-{{$key['name']}}-{{$key['job_title']}}-{{$key['remaining_employees']}}" value="{{$key['name']}}"></option>
                      @endforeach

                      </datalist>
                    </div>
                    <div class="col-md-4">
                      <label for="type">{{__('web.Leave Type')}}</label>
                      <select required="" name="type" id="type" class="form-control">
                        <option selected="" value="">{{__('web.Select')}}</option>
                        @foreach($leave_type as $key)
                        <option <?php if (old('type') == $key['id']): ?>
                            selected
                            <?php elseif ($leave->leave_type_id == $key['id']): ?>
                              selected
                        <?php endif ?>  value="{{$key['id']}}">{{$key['name']}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label for="title">{{__('web.Title')}} ({{__('web.Auto')}})</label>
                      <input type="text" class="form-control" id="title" name="title" required="" value="@if(old('title')){{old('title')}}@else{{$leave->title}}@endif" readonly="">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="leave_date">{{__('web.Leave Date')}}</label>
                      <input type="date" class="form-control" id="leave_date" name="leave_date" required="" value="@if(old('leave_date')){{old('leave_date')}}@else{{$leave->leave_date}}@endif">
                    </div>
                    <div class="col-md-4">
                      <label for="return_date">{{__('web.Return Date')}}</label>
                      <input type="date" id="return_date" name="return_date" class="form-control" required value="@if(old('return_date')){{old('return_date')}}@else{{$leave->return_date}}@endif">
                    </div>
                    <div class="col-md-4">
                      <label for="reason">{{__('web.Reason')}}</label>
                      <input type="text" class="form-control" id="reason" name="reason" required="" value="@if(old('reason')){{old('reason')}}@else{{$leave->reason}}@endif">
                    </div>
                  </div>

                  <div class="form-group row">
                     <div class="col-md-4">
                      <label for="">{{__('web.Documents')}}</label>
                      <div class="custom-file">
                      <input type="file" class="custom-file-input" id="document" name="document[]" multiple="" accept=
"application/msword, application/pdf, image/*">
                      <label class="custom-file-label" for="document">{{__('web.Choose file')}}</label>
                    </div>
                    </div>

                    <div class="col-md-4">
                      <label for="condition">{{__('web.Condition')}}</label>
                      <select class="form-control" name="condition" id="condition" required="" onchange="AmountInput(this.value)">
                        <option selected="" value="">{{__('web.Select')}}</option>
                        <option <?php if (old('condition') == 1 || $leave->is_paid == 1): ?>
                            selected
                        <?php endif ?>  value="1">{{__('web.Paid')}}</option>
                        <option  <?php if (old('condition') == 2 || $leave->is_paid == 2): ?>
                            selected
                        <?php endif ?>  value="2">{{__('web.Non Paid')}}</option>
                      </select>
                    </div>

                    <div class="col-md-4" id="amount-div" style="<?php if (old('condition') != 1 && $leave->is_paid != 1): ?>
                              display: none;
                             <?php else: ?> 
                              display: block;
                    <?php endif ?>">
                      <label for="amount">{{__('web.Amount')}}</label>
                      <input type="number" name="amount" id="amount" <?php if (old('condition') == 1 && $leave->is_paid != 1): ?>
                            required
                      <?php endif ?> class="form-control" value="@if(old('amount')){{old('amount')}}@else{{$leave->paid_amount}}@endif">
                    </div>
                   
                   
                  </div>

                   <div class="form-group row">
                    <div class="col-md-4">
                      <label for="details">{{__('web.No of Colleagues')}} ({{__('web.Auto')}})</label>
                      <input type="number" readonly="" class="form-control"  name="no_of_colleagues" id="no_of_colleagues" required="" value="@if(old('no_of_colleagues')){{old('no_of_colleagues')}}@else{{$leave->no_of_colleagues}}@endif">
                    </div>
                  </div>



                   <div class="form-group row">
                    <div class="col-md-12">
                      <label for="details">{{__('web.Details')}}</label>
                      <textarea name="details" id="details" class="form-control" rows="3">@if(old('details')){{old('details')}}@else{{$leave->details}}@endif</textarea>
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
 
 
  $(function() {
    $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
    });
  });

 $(document).ready(function() {

    $('#name').change(function()
    {
        var value = $('#name').val();
        var info = $('#employee_list [value="' + value + '"]').data('value').split("-");

        document.getElementById("employee_id").value = info[0];
        document.getElementById("name").value = info[1];
        document.getElementById("title").value = info[2];
        document.getElementById("no_of_colleagues").value = info[3];
    });
});

  function AmountInput(val)
  {
    if (val == 1) 
    {
      document.getElementById("amount-div").style.display = "block"; 
      document.getElementById("amount").required = true; 
    }
    else
    {
      document.getElementById("amount").required = false; 
      document.getElementById("amount-div").style.display = "none"; 
    }
  }
</script>

@endsection
