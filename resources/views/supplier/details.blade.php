@extends('layouts.app')
@section('content')
<style type="text/css">
  .detail-row-div{
    padding: 25px;
  }
  .detail-col-div{
    padding:  12px;
  }
</style>

<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-md-6">
            <h1>{{__('Supplier Details')}} ({{$supplier->supplier_name}})</h1>
          </div>
          <div class="col-md-5"></div>
          <div class="col-md-1" >
          <!-- <button onclick='PrintMemberDetails("2")' style="float: right;" type="button" class="btn btn-success">Print</button> -->
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

              <div class="row detail-row-div">
                <div class="col-md-3 detail-col-div"><b>{{__('Name')}}:</b> {{$supplier->supplier_name}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('Aaddress')}}:</b> {{$supplier->supplier_address}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('Contact Number')}}:</b> {{$supplier->supplier_contact}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('Contact Person')}}:</b> {{$supplier->contact_person}}</div>
                <!-- <div class="col-md-3 detail-col-div"><b>{{__('web.Nationality')}}:</b> ad</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.National ID')}}:</b> sfsdf</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Date of Birth')}}:</b> dfgdfg</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Age')}}:</b> sdfsd</div> -->
              </div>
              
              <hr>
             


            </div>




            




        </div>
    </section>
  	

</div>

<script type="text/javascript">
   function PrintMemberDetails(id)
   {    
        $.ajax({
            type: "GET",
            cache: false,
            url: "{{ config('app.url')}}register/print-member-details/"+id,
            beforeSend: function(){
            },
            success: function(data){
                    newWin= window.open("Print","_blank");
                    newWin.document.write(data);
                    newWin.print();
                    newWin.close();
                    },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });
   }
</script>

@endsection
 