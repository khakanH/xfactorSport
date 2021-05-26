@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Item</h1>
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
              <form role="form" method="post" name="userForm" id="userForm" enctype="multipart/form-data" action="{{route('store.update-items')}}">
                @csrf
                <span class='arrow'>
                                <label class='error'></label>
                                </span>
                <div class="card-body">
                <input type="hidden" name="id" value="{{$data['items_data']['item_id']}}">
                  <div class="form-group row">
                  <div class="col-lg-3">
                      <label for="type">Select Type</label>
                        <select id="type" name="type" class="form-control" required="" >
                            <option value="Store" {{$data['items_data']['type'] == 'Store' ? 'selected':''}} >Store</option>
                            <option value="Cafeteria" {{$data['items_data']['type'] == 'Cafeteria' ? 'selected':''}} >Cafeteria</option>
                            <option value="operational_use" {{$data['items_data']['type'] == 'operational_use' ? 'selected':''}} >Operational Use</option>
                       
                          
                        </select>
                    </div>
                    <div class="col-lg-3">
                      <label for="type">Supplier</label>
                        <select id="type" name="supplier" class="form-control" required="" >
                          <option selected="" value="">Select Supplier</option>
                          @foreach($data['supplier_data'] as $key)
                          @if($key['supplier_id'] == $data['items_data']['supplier'])
                            <option value="{{$key['supplier_id']}}" selected>{{$key['supplier_name']}}</option>
                        @else
                        <option <?php if (old('supplier') == $key['supplier_id']): ?>
                              selected
                          <?php endif ?> value="{{$key['supplier_id']}}">{{$key['supplier_name']}}</option>
                        @endif
                          @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                      <label for="name">Items</label>
                      <input type="text" class="form-control" id="name" name="item_name" placeholder="Enter Item Name" required="" value="@if(old('item_name')){{old('item_name')}}@else{{$data['items_data']['items']}}@endif">
                    </div>
                    <div class="col-lg-3">
                      <label for="email">Expiry Date</label>
                      <input type="date" class="form-control" name="expiry_date" placeholder="Enter Expiry Date" required="" value="@if(old('expiry_date')){{old('expiry_date')}}@else{{$data['items_data']['expiry_date']}}@endif">
                    </div>
                  </div>

                   <div class="form-group row">
                    <div class="col-lg-3">
                      <label for="password">Batch</label>
                      <input type="text" class="form-control" name="batch" placeholder="Enter Batch" required="" value="@if(old('batch')){{old('batch')}}@else{{$data['items_data']['batch']}}@endif">
                    </div>
                    <div class="col-lg-3">
                      <label for="password">Size</label>
                      <input type="text" class="form-control" name="item_size" placeholder="Enter Size" required="" value="@if(old('item_size')){{old('item_size')}}@else{{$data['items_data']['size']}}@endif">
                    </div>
                    <div class="col-lg-3">
                      <label for="password">Qty</label>
                      <input type="number" class="form-control" name="item_qty" placeholder="Enter Qty" required="" value="@if(old('item_qty')){{old('item_qty')}}@else{{$data['items_data']['qty']}}@endif">
                    </div>
                    <div class="col-lg-3">
                      <label for="confirm_password">Sale Price</label>
                      <input type="number" class="form-control" id="confirm_password" name="sale_price" placeholder="Sale Price" required="" value="@if(old('sale_price')){{old('sale_price')}}@else{{$data['items_data']['sale_price']}}@endif">
                    </div>
                  </div>

                   <div class="form-group row">
                   <div class="col-lg-3">
                      <label for="confirm_password">Purchase Price</label>
                      <input type="text" class="form-control" id="confirm_password" name="purchase_price" placeholder="Sale Price" required="" value="@if(old('purchase_price')){{old('purchase_price')}}@else{{$data['items_data']['purchase_price']}}@endif">
                    </div>
                    <div class="col-lg-3">
                      <label for="">Profile Image</label>
                      <div class="custom-file">
                      @if($data['items_data']['item_img'])
                      <input type="hidden" name="image_old" value="{{$data['items_data']['item_img']}}" accept="image/*">
                      <img src="<?php echo asset("images/{$data['items_data']['item_img']}")?>" width="150"></img>
                      <a href="{{route('store.delete-item-image',$data['items_data']['item_id'])}}" class="btn btn-danger" >Delete Image</a>
                      @else
                      <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                      <label class="custom-file-label" for="image">Choose file</label>
                      @endif
                    </div>
                    </div>
                   
                  </div>
                  

                </div>

                <div class="card-footer">
                  <button type="submit" id="user-submit-btn" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
        </div>
    </section>
  	

</div>




@endsection
