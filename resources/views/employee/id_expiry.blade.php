@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.ID Expiry')}}</h1>
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



              <form method="post" action="{{route('employee.filter-expiry')}}">
              <div class="row">
              @csrf

                <div class="col-sm-3" id="from_date_div">
                  <label for="from_date">{{__('web.From Date')}}:</label>
                  <input type="date" name="from_date" id="from_date" class="form-control" value="{{$from_date}}">
                </div>
                <div class="col-sm-3" id="to_date_div">
                  <label for="to_date">{{__('web.To Date')}}:</label>
                  <input type="date" name="to_date" id="to_date" class="form-control" value="{{$to_date}}">
                </div>
                
                <div class="col-sm-3">
                  <label for="">&nbsp;</label><br>
                  <button type="submit" class="btn btn-success">{{__('web.Filter')}}</button>
                </div>
              

              </div>
              </form>
              <br>
            


            <div class="card">

                <div class="card-body p-4 table-responsive">
                <table class="table table-striped CommonDataTables" id="">
                  <thead>
                    <tr>
                      <th width="2%">#</th>
                      <th width="8%">{{__('web.Name')}}</th>
                      <th width="8%">{{__('web.Email')}}</th>
                      <th width="8%">{{__('web.Phone')}}</th>
                      <th width="8%">{{__('web.Job_Type')}}</th>
                      <th width="18%">{{__('web.ID Expiry Date')}}</th>
                      <th width="10%">{{__('web.Nationality')}}</th>
                      <th width="15%">{{__('web.Added on')}}</th>
                      <th style="text-align: center;" width="20%">{{__('web.Action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($employee as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$key['name']}}</td>
                      <td>{{$key['email']}}</td>
                      <td>{{$key['phone']}}</td>
                      <td>{{$key->job_type_name->name}}</td>
                      <td <?php if (time() > strtotime($key['id_expiry_date'])): ?>
                        class="bg-danger"
                        <?php else: ?>
                        class="bg-warning"
                      <?php endif ?> >{{date('d-M-Y',strtotime($key['id_expiry_date']))}}</td>
                      <td>{{$key['nationality']}}</td>
                      <td>{{date('d-M-Y',strtotime($key['created_at']))}}</td>
                      <td style="text-align: center;"><a title="{{__('web.Salary')}}" data-toggle="tooltip" onclick='EmployeeSalary("{{$key['id']}}","{{$key['name']}}","{{$key['salary']}}")' href="javascript:void(0)"><i class="fa fa-dollar-sign text-success"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Employee Details')}}" data-toggle="tooltip" onclick='ShowEmployeeDetails("{{$key['id']}}")' href="javascript:void(0)"><i class="fa fa-eye text-secondary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Edit')}}" data-toggle="tooltip" href="{{route('employee.edit-employee',[$key['id']])}}"><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Delete')}}" data-toggle="tooltip" onclick="return confirm('{{__("web.Are you sure?")}}')" href="{{route('employee.delete-employee',[$key['id']])}}"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>

            </div>




            




        </div>
    </section>
  	

</div>

<script type="text/javascript">

  function ShowEmployeeDetails(id)
  {
        $('#EmployeeDetailModal').modal('show');
        $('#EmployeeDetailModalLabel').html('{{__("web.Employee Details")}}');

        $.ajax({
            type: "GET",
            cache: false,
            url: "{{ config('app.url')}}employee/details-employee/"+id,
            beforeSend: function(){
                            $('#EmployeeDetailModalData').html('<center><i class="fas fa-2x fa-sync-alt fa-spin"></i></center>');
                        },
            success: function(data) {
                            $('#EmployeeDetailModalData').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });


        document.getElementById('EmployeeDetailModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('EmployeeDetailModalDialog').style.paddingTop="0px";
        document.getElementById('EmployeeDetailModalData').style.padding="20px 20px 0px 20px";
  }


  function EmployeeSalary(id,name,salary)
  {
        $('#EmployeeSalaryModal').modal('show');
        $('#EmployeeSalaryModalLabel').html('{{__("web.Employee Salary")}}');

        document.getElementById("salary_from").action ="{{route('employee.save-salary')}}";

        document.getElementById('salary_id').value = "";
        document.getElementById('employee_id').value = id;
        document.getElementById('employee_name').value = name;
        document.getElementById('employee_salary').value = salary;
        document.getElementById('deduction').value = 0;
        document.getElementById('deduction_reason').value = "";
        document.getElementById('honorarium').value = 0;
        document.getElementById('honorarium_reason').value = "";
        document.getElementById('salary_date').value = "";
        document.getElementById('total_salary').value = salary;

        document.getElementById('EmployeeSalaryModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('EmployeeSalaryModalDialog').style.paddingTop="0px";
        document.getElementById('EmployeeSalaryModalData').style.padding="20px 20px 0px 20px";
  }



  function CalculateTotalSalary()
    {   

        var deduction   = parseFloat(document.getElementById("deduction").value);
        var honorarium  = parseFloat(document.getElementById("honorarium").value);
        var salary      = parseFloat(document.getElementById("employee_salary").value); 

        if (!deduction) 
        {
            deduction =0;
        }
        if (!honorarium) 
        {
            honorarium =0;
        }

        document.getElementById("total_salary").value = salary + honorarium - deduction;

    }

</script>

@endsection
