@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Leave')}}</h1>
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


               <form method="post" action="{{route('leave.filter-leave')}}">
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
                      <th width="10%">{{__('web.Job_Type')}}</th>
                      <th width="10%">{{__('web.Leave Type')}}</th>
                      <th width="8%">{{__('web.Reason')}}</th>
                      <th width="13%">{{__('web.Leave Date')}}</th>
                      <th width="13%">{{__('web.Return Date')}}</th>
                      <th width="2%">{{__('web.Condition')}}</th>
                      <th width="2%">{{__('web.Amount')}}</th>
                      <th style="text-align: center;" width="20%">{{__('web.Action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($leave as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{isset($key->employee_data->name)?$key->employee_data->name:"-"}}</td>
                      <td>{{isset($key->employee_data->job_type_name->name)?$key->employee_data->job_type_name->name:"-"}}</td>
                      <td>{{isset($key->leave_type_name->name)?$key->leave_type_name->name:"-"}}</td>
                      <td>{{$key['reason']}}</td>
                      <td>{{date('d-M-Y',strtotime($key['leave_date']))}}</td>
                      <td>{{date('d-M-Y',strtotime($key['return_date']))}}</td>
                      <td>{{($key['is_paid']==1)?__('web.Paid'):__('web.Non Paid')}}</td>
                      <td>{{empty($key['paid_amount'])?"-":$key['paid_amount']}}</td>
                      <td style="text-align: center;"><a title="{{__('web.Leave Details')}}" data-toggle="tooltip" onclick='ShowLeaveDetails("{{$key['id']}}")' href="javascript:void(0)"><i class="fa fa-eye text-secondary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Edit')}}" data-toggle="tooltip" href="{{route('leave.edit-leave',[$key['id']])}}"><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Delete')}}" data-toggle="tooltip" onclick="return confirm('{{__("web.Are you sure?")}}')" href="{{route('leave.delete-leave',[$key['id']])}}"><i class="fa fa-trash text-danger"></i></a></td>
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

  function ShowLeaveDetails(id)
  {
        $('#LeaveDetailModal').modal('show');
        $('#LeaveDetailModalLabel').html('{{__("web.Leave Details")}}');

        $.ajax({
            type: "GET",
            cache: false,
            url: "{{ config('app.url')}}leave/details-leave/"+id,
            beforeSend: function(){
                            $('#LeaveDetailModalData').html('<center><i class="fas fa-2x fa-sync-alt fa-spin"></i></center>');
                        },
            success: function(data) {
                            $('#LeaveDetailModalData').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });


        document.getElementById('LeaveDetailModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('LeaveDetailModalDialog').style.paddingTop="0px";
        document.getElementById('LeaveDetailModalData').style.padding="20px 20px 0px 20px";
  }

 
</script>

@endsection
