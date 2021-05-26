@extends('layouts.app')
@section('content')

<style type="text/css">
   .uploaded_img_file {
    border: 0 none; 
    display: inline-block; 
    height: auto; 
    max-width: 45%;
    vertical-align: middle;
    margin: 7px;
  }
</style>
<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Expenses')}}</h1>
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




      <form class="row" method="post" action="{{route('expenses.filter-expenses')}}">
        @csrf

      <div class="col-lg-2">
        <label>{{__('web.Expense Type')}}:</label>
        <select onchange="GetBeneficiary(this.value)" class="form-control" name="type" id="type">
          <option value="">{{__('web.Select')}}</option>
          @foreach($expense_type as $key)
            <option <?php if ($selected_expense_type == $key['id']): ?>
                selected
            <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
          @endforeach
        </select>
      </div>
       <div class="col-lg-2">
        <label>{{__('web.Beneficiary')}}:</label>
        <select class="form-control" name="beneficiary" id="beneficiary" onfocus="return style.boxShadow='0 0 0px';">
          <option value="">{{__('web.Select')}}</option>
        </select>
      </div>
       <div class="col-lg-2">
       <label>{{__('web.Start Date')}}:</label>
       <input type="date" class="form-control" name="from_date" id="from_date" value="{{$from_date}}">
      </div>
       <div class="col-lg-2">
       <label>{{__('web.End Date')}}:</label>
       <input type="date" class="form-control" name="to_date" id="to_date" value="{{$to_date}}">
      </div>
       <div class="col-lg-2" style="">
        <label>{{__('web.Payment Check')}}:</label><br>
        <input type="checkbox" name="upcoming" id="upcoming" <?php if (!empty($upcoming_check)): ?>
          checked
        <?php endif ?>> <label for="upcoming" style="font-weight: normal;">{{__('web.Upcoming Payment')}}</label><br>
        <input type="checkbox" name="overdue" id="overdue" <?php if (!empty($overdue_check)): ?>
          checked
        <?php endif ?>> <label for="overdue" style="font-weight: normal;">{{__('web.Overdue Payment')}}</label>
      </div>
      <div class="col-lg-2" style="margin-top: 24px;">
        <button class="btn btn-success" style="width: 100%;">{{__('web.Filter')}}</button>
      </div>

    </form>


    <br>











            <div class="card">

                <div class="card-body p-4 table-responsive">
                <table class="table table-striped CommonDataTables" id="">
                  <thead >
                    <tr>
                      <th width="2%">#</th>
                      <th width="10%">{{__('web.Expense Type')}}</th>
                      <th width="5%">{{__('web.Beneficiary')}}</th>
                      <th width="8%">{{__('web.Amount')}}</th>
                      <th width="15%">{{__('web.Payment Date')}}</th>
                      <th width="20%">{{__('web.Next Payment Date')}}</th>
                      <th width="15%">{{__('web.Files')}}</th>
                      <!-- <th style="text-align: center;" width="5%"> Next Payment Paid</th> -->
                      <th width="15%"> {{__('web.Action')}}</th>
                     </tr>
                  </thead>
                  <tbody>
   
                    @foreach($expense_data as $key)

                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{isset($key->ExpenseTypeName->name)?$key->ExpenseTypeName->name:"-"}}</td>
                      <td>{{isset($key->BeneficiaryName->name)?$key->BeneficiaryName->name:"-"}}</td>
                      <td>{{$key['amount']}}</td>
                      <td>{{$key['payment_date']}}</td>
                      <td>{{(strtotime($key['next_payment_date']) < 0)?'none':$key['next_payment_date']}}</td>
                      <td>

                        @if(!empty($key['attach_files']))
                        @php($files = explode(",",$key['attach_files']))
                        @foreach($files as $file)
                        <li style="word-break: break-all;">  <a href="{{ config('app.img_url').$file }}" target="_BLANK"><img class="uploaded_img_file" src="{{ config('app.img_url').$file }}"> </a></li>
                        @endforeach
                        @endif
                      </td>
                      <!-- <td style="text-align: center; vertical-align: middle;">
                        @if(strtotime($key['next_payment_date']) > 0)
                          @if($key['is_paid'] ==0)
                          <span class="text-danger">No</span><button onclick='MarkPaid("{{$key['id']}}")' type="button" class="btn btn-success">Mark Paid</button>
                          @else
                          <span class="text-success">Yes</span>
                          @endif
                        @endif
                        
                        

                      </td> -->
                      <td style="text-align: center; vertical-align: middle;">
                        
                        <a href="javascript:void(0)" onclick='ShowExpenseDetails("{{$key['id']}}")' data-toggle="tooltip" title="{{__('web.View')}}" class="fa fa-eye text-secondary"></a>&nbsp;&nbsp;&nbsp;
                         <a href="{{route('expenses.edit-expenses',[$key['id']])}}"><i data-toggle="tooltip" title="{{__('web.Edit')}}" class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;
                          <a onclick="return confirm('Are you sure?')" href="{{route('expenses.delete-expenses',[$key['id']])}}"><i data-toggle="tooltip" title="{{__('web.Delete')}}" class="fa fa-trash text-danger"></i></a>

                      </td>
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
 $('input[type="checkbox"]').on('change', function() {
   $(this).siblings('input[type="checkbox"]').prop('checked', false);
});

      $( document ).ready(function() {

        var expense_type_id = '<?php echo empty($selected_expense_type)?999:$selected_expense_type; ?>';
        var beneficiary_id = '<?php echo empty($selected_beneficiary)?999:$selected_beneficiary; ?>';

            $.ajax({
                type: "GET",
                cache: false,
                url: "{{ config('app.url')}}expenses/get-beneficiary-names/"+expense_type_id+"/"+beneficiary_id,
                beforeSend: function(){
                },
                success: function(data) {
                    
                    document.getElementById("beneficiary").innerHTML=data;
                                   
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Exception:' + errorThrown);
                }
            });
     

    });


  function GetBeneficiary(id)
  { 
    if (!id) 
    {
      var id = 999;
    }


   
            $.ajax({
              type: "GET",
              cache: false,
              url: "{{ config('app.url')}}expenses/get-beneficiary-names/"+id+"/0",
              beforeSend: function(){
              },
                success: function(data) {
                    
                    document.getElementById("beneficiary").innerHTML=data;
                    document.getElementById("beneficiary").style.boxShadow = " 0 0 10px #9ecaed";
                                   
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Exception:' + errorThrown);
                }
            });
  }
   function ShowExpenseDetails(id)
  {
     $('#ExpenseDetailModal').modal('show');
        $('#ExpenseDetailModalLabel').html('{{__("web.Expense Details")}}');

        document.getElementById('ExpenseDetailModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('ExpenseDetailModalDialog').style.paddingTop="0px";
        document.getElementById('ExpenseDetailModalData').style.padding="20px 20px 0px 20px";


        $.ajax({
            type: "GET",
            cache: false,
            url: "{{ config('app.url')}}expenses/details-expenses/"+id,
             beforeSend: function(){
                $('#ExpenseDetailModalData').html("");                          
             },
            success: function(data) {
                $('#ExpenseDetailModalData').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });

  }
 
</script>

@endsection
