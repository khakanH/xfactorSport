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
              <form role="form" method="post" name="userForm" id="userForm" enctype="multipart/form-data" action="{{route('purchase.save-items')}}">
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
                        <select id="type" name="supplier" class="form-control" required="" >
                          <option selected="" value="">Select Supplier</option>
                          @foreach($supplier_data as $key)
                          <option <?php if (old('supplier') == $key['supplier_id']): ?>
                              selected
                          <?php endif ?> value="{{$key['supplier_id']}}">{{$key['supplier_name']}}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                      <label for="name">Items</label>
                      <input type="text" class="form-control" id="name" name="item_name" placeholder="Enter Item Name" required="" value="{{old('item_name')}}">
                    </div>
                    <!-- <div class="col-lg-3">
                      <label for="email">Expiry Date</label>
                      <input type="date" class="form-control" name="expiry_date" placeholder="Enter Expiry Date" required="" value="{{old('expiry_date')}}">
                    </div> -->
                  </div>

                   <div class="form-group row">
                    <!-- <div class="col-lg-3">
                      <label for="password">Batch</label>
                      <input type="text" class="form-control" name="batch" placeholder="Enter Batch" required="" value="{{old('password')}}">
                    </div> -->
                    <div class="col-lg-3">
                      <label for="password">Size</label>
                      <input type="text" class="form-control" name="item_size" placeholder="Enter Size" value="{{old('item_size')}}">
                    </div>
                      <!-- <div class="col-lg-3">
                        <label for="password">Qty</label>
                        <input type="number" class="form-control" name="item_qty" placeholder="Enter Qty" required="" value="{{old('item_qty')}}">
                      </div> -->
                    <div class="col-lg-3">
                      <label for="confirm_password">Sale Price</label>
                      <input type="number" class="form-control" id="confirm_password" name="sale_price" placeholder="Sale Price" required="" step="0.01" value="{{old('sale_price')}}">
                    </div>
                  <div class="col-md-3">
                  <div class="form-group">
                    <label>{{ __('Color') }}</label>
                    <input type="color" name="item_color" class="form-control" value="#ffffff"  autocomplete="false" required/>
                  </div>
                  </div>
                  </div>
                   <div class="form-group row">
                   <div class="col-lg-3">
                      <label for="confirm_password">Purchase Price</label>
                      <input type="number" class="form-control" id="confirm_password" name="purchase_price" placeholder="Sale Price" step="0.01" required="" value="{{old('purchase_price')}}">
                    </div>
                    <div class="col-lg-3">
                      <label for="">Profile Image</label>
                      <div class="custom-file">
                      <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                      <label class="custom-file-label" for="image">Choose file</label>
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
