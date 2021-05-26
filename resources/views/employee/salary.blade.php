@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Employee Salary')}}</h1>
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


              <form method="post" action="{{route('employee.filter-salary')}}">
              <div class="row">
              @csrf

                <div class="col-sm-3">
                  <label for="from_date">{{__('web.From Date')}}:</label>
                  <input type="date" name="from_date" id="from_date" class="form-control" value="{{$from_date}}">
                </div>
                <div class="col-sm-3">
                  <label for="to_date">{{__('web.To Date')}}:</label>
                  <input type="date" name="to_date" id="to_date" class="form-control" value="{{$to_date}}">
                </div>
                


                <div class="col-sm-2">
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
                      <th width="8%">{{__('web.Salary')}}</th>
                      <th width="8%">{{__('web.Deduction')}}</th>
                      <th width="8%">{{__('web.Honorarium')}}</th>
                      <th width="18%">{{__('web.Total Salary')}}</th>
                      <th width="10%">{{__('web.Date')}}</th>
                      <th style="text-align: center;" width="20%">{{__('web.Action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $total_salary=0;
                      $total_deduction=0;
                      $total_honorarium =0 ;
                      $grand_total_salary =0 ;
                    ?>
                  @foreach($salary as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$key->employee_data->name}}</td>
                      <td>{{$key['salary']}}</td>
                      <td>{{$key['deduction']}}</td>
                      <td>{{$key['honorarium']}}</td>
                      <td>{{$key['total_salary']}}</td>
                      <td>{{date('Y-m-d',strtotime($key['salary_date']))}}</td>
                      <td style="text-align: center;"><a title="{{__('web.Details')}}" data-toggle="tooltip" onclick='ShowEmployeeSalaryDetails("{{$key['id']}}")' href="javascript:void(0)"><i class="fa fa-eye text-secondary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Edit')}}" data-toggle="tooltip" onclick='EmployeeSalaryEdit("{{$key['id']}}","{{$key['employee_id']}}","{{$key->employee_data->name}}","{{$key['salary']}}","{{$key['deduction']}}","{{$key['honorarium']}}","{{$key['deduction_reason']}}","{{$key['honorarium_reason']}}","{{date('Y-m',strtotime($key['salary_date']))}}","{{$key['total_salary']}}")' href="javascript:void(0)"><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Delete')}}" data-toggle="tooltip" onclick="return confirm('{{__("web.Are you sure?")}}')" href="{{route('employee.delete-salary',[$key['id']])}}"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                    <?php
                      $total_salary+=$key['salary'];
                      $total_deduction+=$key['deduction'];
                      $total_honorarium +=$key['honorarium'] ;
                      $grand_total_salary +=$key['total_salary'] ;
                    ?>
                  @endforeach
                  </tbody>
                   <tfoot>
                    <tr style="background: slategray; color: white; font-size: 18px;">
                      <td colspan="2" align="right"><b>{{__('web.Total')}}</b></td>
                      <td>{{$total_salary}}</td>
                      <td>{{$total_deduction}}</td>
                      <td>{{$total_honorarium}}</td>
                      <td>{{$grand_total_salary}}</td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div>




            




        </div>
    </section>
  	

</div>

<script type="text/javascript">
 function ShowEmployeeSalaryDetails(id)
  {
        $('#EmployeeDetailModal').modal('show');
        $('#EmployeeDetailModalLabel').html('{{__("web.Employee Salary Details")}}');

        $.ajax({
            type: "GET",
            cache: false,
            url: "{{ config('app.url')}}employee/details-salary/"+id,
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


  function EmployeeSalaryEdit(id,emp_id,name,salary,deduction,honorarium,deduction_reason,honorarium_reason,salary_date,total_salary)
  {
        $('#EmployeeSalaryModal').modal('show');
        $('#EmployeeSalaryModalLabel').html('{{__("web.Employee Salary")}}');
        
        document.getElementById("salary_from").action ="{{route('employee.update-salary')}}";

        document.getElementById('salary_id').value = id;
        document.getElementById('employee_id').value = emp_id;
        document.getElementById('employee_name').value = name;
        document.getElementById('employee_salary').value = salary;
        document.getElementById('deduction').value = deduction;
        document.getElementById('deduction_reason').value = deduction_reason;
        document.getElementById('honorarium').value = honorarium;
        document.getElementById('honorarium_reason').value = honorarium_reason;
        document.getElementById('salary_date').value = salary_date;
        document.getElementById('total_salary').value = total_salary;

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
