
@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add sales</h1>
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
        <form id="formCafe" action="{{route('store.sales-update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <span class='arrow'>
                                <label class='error'></label>
                                </span>
                <div class="card-body">
                <input type="hidden" name="id" value="{{$sales_data->sale_id}}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('Member') }}</label>
                                 <!-- <input type="text" class="form-control" id="members" autocomplete="off" name="member_name" required="" value="{{old('member_name')}}" onkeyup="GetMemberName(this.value)" onfocus="GetMemberName(this.value)"> -->
                    <input type="text" class="form-control" id="name" list="member_list" autocomplete="off" name="member_new" required="" value="{{$sales_data->name}}">
                    <input type="hidden" name="Member" id="member_id" value="{{$sales_data->id}}">
                    <!-- <div id="member-name-list" style="display: none; padding: 5px; text-align: center;position: absolute; width: 96%; z-index: 10;background: white;border: solid lightgray 0.9px; border-radius: 4px; max-height: 200px; overflow-y: auto;"></div>     -->
                    <datalist id="member_list" >

                    @foreach($members as $key)
                    <option class="form-control" data-value="{{$key['id']}}-{{$key['name']}}" value="{{$key['name']}}"></option>
                    @endforeach

                    </datalist>
                               
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('Select Item') }}</label>
                                 <!-- <input type="text" class="form-control" list="items_list" id="items" autocomplete="off" name="name" required="" value="{{old('name')}}" onkeyup="GetItemsName(this.value)" onfocus="GetItemsName(this.value)">-->
                    <input type="text" class="form-control" list="items_list" id="items" autocomplete="off" name="name" required="" value="{{$sales_data->items}}"> 
                    <input type="hidden" name="Items" id="items_id" value="{{$sales_data->item_id}}">
                    <!-- <div id="employee-name-list" style="display: none; padding: 5px; text-align: center;position: absolute; width: 96%; z-index: 10;background: white;border: solid lightgray 0.9px; border-radius: 4px; max-height: 200px; overflow-y: auto;"></div>     -->
                    <datalist id="items_list" >

                    @foreach($products as $key)
                    <option class="form-control" data-value="{{$key['item_id']}}-{{$key['items']}}" value="{{$key['items']}}"></option>
                    @endforeach

                    </datalist>
                               
                            </div>
                        </div>
                        <div class="form-group row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>{{ __('Item Qty') }}</label>
                                <input type="text" name="Qty" value="{{$sales_data->qty}}" id="Qty" class="form-control" 
                                autocomplete="false" required />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ __('Item Batch') }}</label>
                                <input type="text" name="Batch" value="{{$sales_data->batch}}" id="Batch" class="form-control" readonly
                                    autocomplete="false"  />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ __('Item Volume') }}</label>
                                <input type="text" name="Volume" id="Volume" value="{{$sales_data->volume}}" class="form-control" readonly
                                    autocomplete="false" required />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ __('Expiry Date') }}</label>
                                <input type="text" value="{{$sales_data->expiry_date}}" name="Expiry_Date" value="{{ date('Y-m-d') }}" class="form-control"
                                    readonly autocomplete="false" required />
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ __('Current Stock') }}</label>
                                <input type="text" name="Current_Stock" id="Current_Stock" class="form-control" readonly
                                    autocomplete="false" required />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ __('Price Per Item') }}</label>
                                <input type="text" name="Sale_Price" value="{{$sales_data->sale_price}}" id="Price" class="form-control" readonly
                                    autocomplete="false" required />
                            </div>
                        </div>
                        </div>
                        <div class="form-group row">
                        <div class="col-md-2">
                        <div class="form-group">
                        <label>{{ __('Discount') }}</label>
                        <input type="text" name="discount" value="{{$sales_data->discount}}" id="discount" class="form-control"  autocomplete="false"/>
                        </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>{{ __('Total') }}</label>
                                <input type="text" name="TotalAmount" value="{{$sales_data->total_amount}}" id="total" class="form-control" readonly
                                    autocomplete="false" required />
                            </div>
                        </div>
                        </div>
                        </div>
                        <input type="hidden" name="Purchase_Price" value="{{$sales_data->purchase_price}}" id="Purchase_Price" class="form-control" readonly
                            autocomplete="false" required />

                        <div class="card-footer">
                        <button class="btn btn-success" id="btnSave"
                            >Save</button>
                    </div>
                    </form>
            </div>
        </div>
    </section>
  	

</div>
<script type="text/javascript">
 
  function GetItemsName(text)
  {
    // alert(text);
    if (!text) 
    {
      document.getElementById("id").value = "";
      document.getElementById("employee-name-list").style.display = "none";
      return false;
    }

    $.ajax({
            type: "GET",
            cache: false,
            async: false,
            url: "{{ config('app.url')}}store/items-name/"+text,
            beforeSend: function(){
                           
                        },
            success: function(data) {
              document.getElementById("employee-name-list").style.display = "block";
                            $('#employee-name-list').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });
  }
  function SelectEmployee(id,name)
  {
    document.getElementById("id").value = id;
    // alert(id);
    document.getElementById("items").value = name;
    document.getElementById("employee-name-list").style.display = "none";
    $.post("{{ url('/store/sales-ajax') }}", {_token:"{{csrf_token()}}", id}, function(result){
     	console.log(result)
       $('#Price').val(result[0].sale_price);
       $('#Current_Stock').val(result[0].qty);
       $('#Volume').val(result[0].size);
       $('#Batch').val(result[0].batch);
       $('#Purchase_Price').val(result[0].purchase_price);
      });
  }
  $(document).ready(function() {
    //   if($sales_data['qty']);
      $('#name').change(function()
    {
        var value = $('#name').val();
        var info = $('#member_list [value="' + value + '"]').data('value').split("-");
        document.getElementById("member_id").value = info[0];
        document.getElementById("name").value = info[1];
    });    
  $('#items').change(function()
    {
        var value = $('#items').val();
        var info = $('#items_list [value="' + value + '"]').data('value').split("-");
        // alert(info[0]);
        var id = info[0];
        document.getElementById("items_id").value = info[0];
        // document.getElementById("name").value = info[1];
        $.post("{{ url('/store/sales-ajax') }}", {_token:"{{csrf_token()}}", id}, function(result){
     	console.log(result)
       $('#Price').val(result[0].sale_price);
       $('#Current_Stock').val(result[0].qty);
       $('#Volume').val(result[0].size);
       $('#Batch').val(result[0].batch);
       $('#Purchase_Price').val(result[0].purchase_price);
      });
    });
  });
 
</script>



@endsection


<!-- @section('script') -->

@endsections