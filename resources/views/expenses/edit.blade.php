@extends('layouts.app')
@section('content')

<style type="text/css">
   .uploaded_img_file {
    border: 0 none; 
    display: inline-block; 
    height: auto; 
    max-width: 15%;
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


            @if ($errors->any())
              <div class="callout callout-danger">
                  <h5>Form Submission Errors</h5>
                  @foreach ($errors->all() as $error)
                   <li class="text-danger">{{$error}}</li>
                  @endforeach
              </div>
           @endif

        <div class="card">
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('expenses.update-expenses')}}">
                @csrf

                <div class="card-body">
                  <input type="hidden" name="expense_id" value="{{$expense->id}}">
    <div class="row">
      
      <div class="form-group col-md-4">
        <label>{{__('web.Expense Type')}}</label>
        <select onchange="GetBeneficiary(this.value)" class="form-control" name="type" id="type" required="">
          <option value="">{{__('web.Select')}}</option>
          @foreach($expense_type as $key)
          <option <?php if ($expense->expense_type_id == $key['id']): ?>
            selected
          <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group col-md-4">
        <label>{{__('web.Beneficiary')}}</label>
        <select class="form-control" name="beneficiary" id="beneficiary" onfocus="return style.boxShadow='0 0 0px';">
          <option value="">{{__('web.Select')}}</option>
        </select>
      </div>
      <div class="form-group col-md-4">
        <label>{{__('web.Amount')}}</label>
        <input required="" type="number" name="amount" id="amount" class="form-control" value="{{$expense->amount}}">
      </div>
    </div>

    <div class="row">
      
      <div class="form-group col-md-4">
        <label>{{__('web.Payment Date')}}</label>
        <input required=""  type="date" name="payment_date" id="payment_date" class="form-control" value="{{$expense->payment_date}}">
      </div>
    <!--  <div class="form-group col-md-4">
        <label>Notification Period (Days)</label>
        <input required=""  type="number" min="1" max="360" step="1" name="notification_period" id="notification_period" class="form-control" value="1">
      </div> -->
      <div class="form-group col-md-4">
        <label>{{__('web.Next Payment Date')}}</label>
        <input  type="date" name="next_payment_date" id="next_payment_date" class="form-control" value="{{$expense->next_payment_date}}">
      </div>
       <div class="form-group col-md-4">
        <label>{{__('web.Attach File')}}</label>
        <input type="file" class="form-control" name="attach_file[]" id="attach_file" multiple="" accept="image/*,application/pdf">
      </div>
      
    </div>

  <!--  <div class="row">
      <div class="form-group col-md-12">
        <label>Attach File</label>
        <input type="file" class="form-control" name="attach_file[]" id="attach_file" multiple="" accept="image/*,application/pdf">
      </div>
    </div> -->


    <div class="row">
      
      <div class="form-group col-md-6">
        <label>{{__('web.Details')}}</label>
        <textarea class="form-control" rows="6" name="details" id="details">{{$expense->details}}</textarea>
      </div>
      <div class="form-group col-md-6">
        <label>{{__('web.Account Period')}}</label>
        <textarea class="form-control" rows="6" name="account_period" id="account_period">{{$expense->account_period}}</textarea>
      </div>

    </div>


 <div class="form-group row">
                    <div class="col-md-12">
                      <label for="">{{__('web.Uploaded Files')}}:</label>
                      @if(!empty($expense->attach_files))
                      @php($files = explode(',',$expense->attach_files))
                      @php($count = 1)
                        @foreach($files as $file)
                        <li id="file-list-{{$count}}"><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $file ?>"><img class="uploaded_img_file" src="<?php echo config('app.img_url') ?><?php echo $file ?>"></a>&nbsp;&nbsp;&nbsp;<i class="fas fa-times-circle text-danger" style="cursor: pointer;" onclick='DeleteExpensesFiles("{{$file}}","{{$expense->id}}","{{$count}}")'></i></li>
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


   $( document ).ready(function() {

        var expense_type_id = '<?php echo empty($expense->expense_type_id)?999:$expense->expense_type_id; ?>';
        var beneficiary_id = '<?php echo empty($expense->beneficiary_id)?999:$expense->beneficiary_id; ?>';

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


  function DeleteExpensesFiles(file,id,row_count)
  {
    var r = confirm("{{__('web.Are you sure?')}}");

    if (!r) 
    {
      return false;
    }


   $.ajax({
            type: "POST",
            cache: false,
            url: "{{ config('app.url')}}expenses/delete-files",
            data:{
              file: file,
              expense_id: id,
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
