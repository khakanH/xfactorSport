@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Stock</h1>
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
              <form role="form" method="post" name="userForm" id="userForm" enctype="multipart/form-data" action="{{route('purchase.save-stock')}}">
                @csrf
                <span class='arrow'>
                                <label class='error'></label>
                                </span>
                <div class="card-body">
                  <div class="form-group row">
                  <div class="col-lg-3">
                      <label for="type">Select Type</label>
                        <select id="type" name="type" class="form-control" required="" >
                          <option  value="Store">Store</option>
                          <option value="Cafeteria">Cafeteria</option>
                          <option value="operational_use">Operational Use</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                      <label for="type">Supplier</label>
                        <!-- <input type="text" class="form-control" id="members" autocomplete="off" name="member_name" required="" value="{{old('member_name')}}" onkeyup="GetMemberName(this.value)" onfocus="GetMemberName(this.value)"> -->
                    <input type="text" class="form-control" id="name" list="member_list" autocomplete="off" name="Member" required="" value="{{old('Member')}}">
                    <input type="hidden" name="member_name" id="member_id" value="{{old('member_name')}}">
                    <!-- <div id="member-name-list" style="display: none; padding: 5px; text-align: center;position: absolute; width: 96%; z-index: 10;background: white;border: solid lightgray 0.9px; border-radius: 4px; max-height: 200px; overflow-y: auto;"></div>     -->
                    <datalist id="member_list" >

                    @foreach($supplier_data as $key)
                    <option class="form-control" data-value="{{$key['supplier_id']}}-{{$key['supplier_name']}}" value="{{$key['supplier_name']}}"></option>
                    @endforeach

                    </datalist>
                    </div>
                  <div class="col-md-3">
                  <div class="form-group">
                    <label>{{ __('Select Item') }}</label>
                     <!-- <input type="text" class="form-control" list="items_list" id="items" autocomplete="off" name="name" required="" value="{{old('name')}}" onkeyup="GetItemsName(this.value)" onfocus="GetItemsName(this.value)">-->
                     <input type="text" class="form-control" list="items_list" id="items" autocomplete="off" name="name" required="" value="{{old('name')}}"> 
                    <input type="hidden" name="Items" id="items_id" value="{{old('Items')}}">
                    <!-- <div id="employee-name-list" style="display: none; padding: 5px; text-align: center;position: absolute; width: 96%; z-index: 10;background: white;border: solid lightgray 0.9px; border-radius: 4px; max-height: 200px; overflow-y: auto;"></div>     -->
                    <datalist id="items_list" >

                    @foreach($products as $key)
                    <option class="form-control" data-value="{{$key['item_id']}}-{{$key['items']}}" value="{{$key['items']}}"></option>
                    @endforeach

                    </datalist>   
                  </div>
                  </div>
                  </div>
                  <div class="form-group row">
                  <div class="col-md-1">
                  <div class="form-group">
                    <label>{{ __('Item Qty') }}</label>
                    <input type="text" name="item_qty" id="Qty" class="form-control"  autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Item Batch') }}</label>
                    <input type="text" name="item_batch" class="form-control"  autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-1">
                  <div class="form-group">
                    <label>{{ __('Volume') }}</label>
                    <input type="text" name="item_volume" class="form-control"  autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-1">
                  <div class="form-group">
                    <label>{{ __('Color') }}</label>
                    <input type="color" name="item_color" class="form-control" value="#ffffff"  autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Expiry Date') }}</label>
                    <input type="text" name="expiry_date" value="{{ date('Y-m-d') }}" class="form-control" readonly  autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Current Stock') }}</label>
                    <input type="text" name="current_stock" id="Current_Stock" class="form-control" readonly  autocomplete="false" required/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Price per Item') }}</label>
                    <input type="text" name="price_per_item" id="Price" class="form-control" readonly  autocomplete="false" required/>
                  </div>
                  </div>
                  </div>
                  <div class="form-group row">
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Discount') }}</label>
                    <input type="text" name="discount" id="discount" class="form-control"  autocomplete="false"/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Total') }}</label>
                    <input type="text" name="total" id="total" class="form-control" readonly  autocomplete="false" required/>
                  </div>
                  </div>
                  </div>
                </div>
                <input type="hidden" name="Purchase_Price" id="Purchase_Price" class="form-control" readonly
                            autocomplete="false" required />
                <div class="card-footer">
                  <button type="submit" id="user-submit-btn" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
        </div>
    </section>
  	

</div>

<script type="text/javascript">


  
    $.validator.addMethod('emailFormat', function(value, element) {
        return this.optional(element) || (value.match(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/));
    },
    'Please enter a valid email address');
        $(function() {
        $("form[name='userForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "10px" ,"width":"100%"});
        label.insertBefore(element);
    },
    wrapper: 'span',

    rules: {
    

      email: {
        emailFormat: true,
      },
      confirm_password: {
        equalTo: "#password"


      },
     
    },
    messages: {
     
       confirm_password: {
        equalTo:"Password Mismatch",

      },
    },
    submitHandler: function(form) {

        document.getElementById("user-submit-btn").disabled = true;
        form.submit();

    }
  });
  });
</script>
<script type="text/javascript">
 
 $(document).ready(function() {
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
  function GetMemberName(text)
  {
    // alert(text);
    if (!text) 
    {
      document.getElementById("member-id").value = "";
      document.getElementById("member-name-list").style.display = "none";
      return false;
    }

    $.ajax({
            type: "GET",
            cache: false,
            async: false,
            url: "{{ config('app.url')}}cafeteria/members-name/"+text,
            beforeSend: function(){
                           
                        },
            success: function(data) {
              document.getElementById("member-name-list").style.display = "block";
                            $('#member-name-list').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // alert('Exception:' + errorThrown);
                console.log("Errors:" + errorThrown);
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
  function SelectMember(id,name)
  {
    document.getElementById("member-id").value = id;
    // alert(id);
    document.getElementById("members").value = name;
    document.getElementById("member-name-list").style.display = "none";
  }

  
 
</script>


@endsection
