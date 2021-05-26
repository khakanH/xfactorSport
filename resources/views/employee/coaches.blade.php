@extends('layouts.app')
@section('content')
<style type="text/css">
   .uploaded_img_file {
    border: 0 none; 
    display: inline-block; 
    height: auto; 
    max-width: 55%;
    vertical-align: middle;
    margin: 7px;
  }
</style>


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Coaches')}}</h1>
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

                <div class="card-body p-4 table-responsive">
                <table class="table table-striped CommonDataTables" id="">
                  <thead>
                    <tr>
                      <th width="2%">#</th>
                      <th width="8%">{{__('web.Name')}}</th>
                      <th width="8%">{{__('web.Email')}}</th>
                      <th width="8%">{{__('web.Phone')}}</th>
                      <th width="8%">{{__('web.Sports')}}</th>
                      <th width="18%">{{__('web.ID Expiry Date')}}</th>
                      <th width="10%">{{__('web.Nationality')}}</th>
                      <th width="15%">{{__('web.Added on')}}</th>
                      <th style="text-align: center;" width="20%">{{__('web.Action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($coach as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$key['name']}}</td>
                      <td>{{$key['email']}}</td>
                      <td>{{$key['phone']}}</td>
                      <td>{{$key->sport_name->name}}</td>
                      <td>{{date('d-M-Y',strtotime($key['id_expiry_date']))}}</td>
                      <td>{{$key['nationality']}}</td>
                      <td>{{date('d-M-Y',strtotime($key['created_at']))}}</td>
                      <td style="text-align: center;"><!-- <a title="{{__('web.Salary')}}" data-toggle="tooltip" onclick='EmployeeSalary("{{$key['id']}}","{{$key['name']}}","{{$key['salary']}}")' href="javascript:void(0)"><i class="fa fa-dollar-sign text-success"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; --><a title="{{__('web.Details')}}" data-toggle="tooltip" onclick='ShowEmployeeDetails("{{$key['id']}}")' href="javascript:void(0)"><i class="fa fa-eye text-secondary"></i></a><!-- &nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Edit')}}" data-toggle="tooltip" href="{{route('employee.edit-employee',[$key['id']])}}"><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Delete')}}" data-toggle="tooltip" onclick="return confirm('{{__("web.Are you sure?")}}')" href="{{route('employee.delete-employee',[$key['id']])}}"><i class="fa fa-trash text-danger"></i></a> --> &nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0)" onclick='GetCoachTimeTable("{{$key['id']}}")'>{{__('web.Time Table')}}</a></td>
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
        $('#EmployeeDetailModalLabel').html('{{__("web.Coach Details")}}');

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


 
    function SortByResult(type)
    {
      if (type == 1) 
      {
          document.getElementById("from_date_div").style.display = "block";
          document.getElementById("to_date_div").style.display = "block";
          document.getElementById("from_date").required = true;
          document.getElementById("to_date").required = true;
      }
      else
      {
          document.getElementById("from_date").required = false;
          document.getElementById("to_date").required = false;
          document.getElementById("from_date_div").style.display = "none";
          document.getElementById("to_date_div").style.display = "none";

      }
    }


    function GetCoachTimeTable(id)
    {
      $('#ClassesTimeTableModal').modal('show');
      $('#ClassesTimeTableModalLabel').html('{{__("web.Time Table")}}');



     
     $.ajax({
              type: "GET",
              cache: false,
              async: false,
              url: "{{ config('app.url')}}coaches/view-coaches-time-table/"+id,
              beforeSend: function(){
                              $('#ClassesTimeTableModalData').html('<center><i class="fas fa-2x fa-sync-alt fa-spin"></i></center>');
                          },
              success: function(data) {
                              $('#ClassesTimeTableModalData').html(data);
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  alert('Exception:' + errorThrown);
              }
          });

      document.getElementById('ClassesTimeTableModal').style.backgroundColor="rgba(0,0,0,0.8)";
      document.getElementById('ClassesTimeTableModalDialog').style.paddingTop="0px";

}

</script>

@endsection
