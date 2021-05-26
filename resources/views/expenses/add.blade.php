@extends('layouts.app')
@section('content')


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
        
 
  <form method="post" action="{{route('expenses.save-expenses')}}" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
    <div class="row">
      
      <div class="form-group col-md-4">
        <label>{{__('web.Expense Type')}}</label>
        <select onchange="GetBeneficiary(this.value)" class="form-control" name="type" id="type" required="">
          <option value="">{{__('web.Select')}}</option>
          @foreach($expense_type as $key)
          <option value="{{$key['id']}}">{{$key['name']}}</option>
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
        <input required="" type="number" name="amount" id="amount" class="form-control">
      </div>
    </div>

    <div class="row">
      
      <div class="form-group col-md-4">
        <label>{{__('web.Payment Date')}}</label>
        <input required=""  type="date" name="payment_date" id="payment_date" class="form-control">
      </div>
    <!--  <div class="form-group col-md-4">
        <label>Notification Period (Days)</label>
        <input required=""  type="number" min="1" max="360" step="1" name="notification_period" id="notification_period" class="form-control" value="1">
      </div> -->
      <div class="form-group col-md-4">
        <label>{{__('web.Next Payment Date')}}</label>
        <input  type="date" name="next_payment_date" id="next_payment_date" class="form-control">
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
        <textarea class="form-control" rows="6" name="details" id="details"></textarea>
      </div>
      <div class="form-group col-md-6">
        <label>{{__('web.Account Period')}}</label>
        <textarea class="form-control" rows="6" name="account_period" id="account_period"></textarea>
      </div>

    </div>
    <hr>
    <div class="row">
      <div class="form-group col-md-12">
        <button type="submit" class="btn btn-success">{{__('web.Save')}}</button>
      </div>
    </div>
</div>
  </form>  
        </div>
      
      </div>
    </section>
  	

</div>

<script type="text/javascript">

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

</script>

@endsection
