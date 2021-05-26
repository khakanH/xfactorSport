@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Expense Type')}}</h1>
          </div>
           <div class="col-sm-1"></div>
          <div class="col-sm-5" ><button onclick='AddExpenseType()' style="float: right;" type="button" class="btn btn-success">{{__('web.Add Expense Type')}}</button></div>
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
                  <thead class="">
                    <tr>
                      <th width="2%">#</th>
                      <th width="20%">{{__('web.Name')}}</th>
                      <th width="10%">{{__('web.Beneficiary')}}</th>
                      <th style="text-align: center;" width="10%">{{__('web.Notification Period')}}</th>
                      <th style="text-align: center;" width="10%">{{__('web.Action')}}</th>
                     </tr>
                  </thead>
                  <tbody>
                   
                   @for($i = 0; $i < count($expense_type); $i++)
                   <tr>
                       <td>{{ $a= $i+1 }}</td>
                       <td>{{$expense_type[$i]['name']}}</td>

                       <td >@for($j = 0 ; $j < count($beneficiary[$i]); $j++)<li id="beneficiary{{$beneficiary[$i][$j]['id']}}" class=""><a data-toggle="tooltip" title="{{__('web.Edit Beneficiary')}}"  href="javascript:void(0)" onclick='EditBeneficiary("{{$beneficiary[$i][$j]['id']}}","{{$beneficiary[$i][$j]['name']}}")'>{{$beneficiary[$i][$j]['name']}}</a><a style="float: right;" title="{{__('web.Delete')}}" data-toggle="tooltip" onclick="return confirm('{{__("web.Are you sure?")}}')" href="{{route('expenses.delete-beneficiary',[$beneficiary[$i][$j]['id']])}}"><i class="fa fa-times-circle text-danger"></i></a></li>@endfor</td>
                       <td style="text-align: center;">{{__('web.Before')}} {{$expense_type[$i]['notification_period']}} {{__('web.Day(s)')}}</td>
                       <td style="text-align: center;"><a href="javascript:void(0)" onclick='AddBeneficiary("{{$expense_type[$i]['id']}}")' data-toggle="tooltip" title="{{__('web.Add Beneficiary')}}" class="fa fa-plus text-primary"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick='EditExpenseType("{{$expense_type[$i]['id']}}","{{$expense_type[$i]['name']}}","{{$expense_type[$i]['notification_period']}}")' data-toggle="tooltip" title="{{__('web.Edit Expense Type')}} " class="fa fa-edit text-success"></a>&nbsp;&nbsp;&nbsp;<a href="{{route('expenses.delete-expense-type',[$expense_type[$i]['id']])}}" data-toggle="tooltip" title="{{__('web.Delete Expense Type')}}" onclick="return confirm('{{__("web.Are you sure?")}}')"  class="fa fa-trash text-danger"></a></td>
                   </tr>
                   @endfor
                 

                  </tbody>
                  </table>
              </div>

            </div>




            




        </div>
    </section>
    

</div>

<script type="text/javascript">

  function AddExpenseType()
    {
        document.getElementById('expense_type_name').value = "";
        document.getElementById('notification_period').value = "";
        document.getElementById('expense_type_id').value = "";
        $('#ExpenseTypeModal').modal('show');
        $('#ExpenseTypeModalLabel').html('{{__("web.Add Expense Type")}}');

        document.getElementById('ExpenseTypeModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ExpenseTypeModalDialog').style.paddingTop="0px";
        document.getElementById('ExpenseTypeModalData').style.padding="20px 20px 0px 20px";

    }
   function EditExpenseType(id,name,period)
    {
        document.getElementById('expense_type_name').value = name;
        document.getElementById('notification_period').value = period;
        document.getElementById('expense_type_id').value = id;
        $('#ExpenseTypeModal').modal('show');
        $('#ExpenseTypeModalLabel').html('{{__("web.Edit Expense Type")}}');

        document.getElementById('ExpenseTypeModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ExpenseTypeModalDialog').style.paddingTop="0px";
        document.getElementById('ExpenseTypeModalData').style.padding="20px 20px 0px 20px";

    }

    function AddBeneficiary(id)
    {
        document.getElementById('name').value = "";
        document.getElementById('id').value = "";
        document.getElementById('expense_id').value = id;
        $('#BeneficiaryModal').modal('show');
        $('#BeneficiaryModalLabel').html('{{__("web.Add New Beneficiary")}}');

        document.getElementById('BeneficiaryModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('BeneficiaryModalDialog').style.paddingTop="0px";
        document.getElementById('BeneficiaryModalData').style.padding="20px 20px 0px 20px";

    }



    function EditBeneficiary(id,name)
    {
        document.getElementById('name').value = name;
        document.getElementById('id').value = id;
        document.getElementById('expense_id').value = "";
        $('#BeneficiaryModal').modal('show');
        $('#BeneficiaryModalLabel').html('{{__("web.Edit Beneficiary")}}');

        document.getElementById('BeneficiaryModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('BeneficiaryModalDialog').style.paddingTop="0px";
        document.getElementById('BeneficiaryModalData').style.padding="20px 20px 0px 20px";

    }


</script>

@endsection
