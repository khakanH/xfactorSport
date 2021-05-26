@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sales List</h1>
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
            <form role="form" method="post" name="userForm" id="userForm" enctype="multipart/form-data" action="{{route('cafeteria.search-cafeteria')}}">
                @csrf
                <span class='arrow'>
                <label class='error'></label>
                </span>
                <div class="card-body">
                  <div class="form-group row">
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Sport') }}</label>
                    <input type="text" name="sport" value="@if(isset($sport)) {{$sport}} @endif" class="form-control"  autocomplete="false"/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Coach') }}</label>
                    <input type="text" name="coach" id="Batch" value="@if(isset($coach)) {{$coach}} @endif" class="form-control"  autocomplete="false"/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Member Name') }}</label>
                    <input type="text" name="member" id="Volume" value="@if(isset($member)) {{$member}} @endif" class="form-control"  autocomplete="false"/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Start Date') }}</label>
                    <input type="date" name="start_date" value="@if(isset($start_date)) {{$start_date}} @endif" class="form-control" autocomplete="false"/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('End Date') }}</label>
                    <input type="date" name="end_date" id="Current_Stock" value="@if(isset($end_date)) {{$end_date}} @endif" class="form-control" autocomplete="false"/>
                  </div>
                  </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" id="user-submit-btn" class="btn btn-primary">Search</button>
                </div>
              </form>
            
                <div class="card-body p-4">
                
                <table class="table table-striped CommonDataTables" id="">
                  <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th width="25%">Members</th>
                      <th width="25%">Items</th>
                      <th width="25%">Expiry Date</th>
                      <th width="25%">Batch</th>
                      <th width="25%">Sale Price</th>
                      <th width="25%">Discount</th>
                      <th width="25%">Purchase Price</th>
                      <th width="25%">Remaining Stock</th>
                      <th style="text-align: center;" width="30%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $total_sale_price=0;
                      $total_discount=0;
                      $total_purchase =0;
                      $total_remaining_stock=0;
                    ?>
                  @foreach($sales as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td><a href="{{route('register.details-member', $key->member_id)}}">{{$key->name}}</a></td>
                      <td>{{$key->items}}</td>
                      <td>{{$key->expiry_date}}</td>
                      <td>{{$key->batch}}</td>
                      <td>{{$key->sale_price}}</td>
                      <td>{{$key->discount}}</td>
                      <td>{{$key->purchase_price}}</td>
                      <td>{{$key->qty}}</td>
                      <td style="text-align: center;">
                      &nbsp;&nbsp;&nbsp;<a href="{{route('cafeteria.sales-edit',$key->sale_id)}}"><i class="fa fa-edit text-info"></i></a>
                      &nbsp;&nbsp;&nbsp;<a onclick="return confirm('Arr you sure?')" href="{{route('cafeteria.sales-delete',$key->sale_id)}}"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                    <?php
                      $total_sale_price += (float)$key->sale_price;
                      $total_discount += (float)$key->discount;
                      $total_purchase += (float)$key->purchase_price;
                      $total_remaining_stock += (float)$key->volume;
                    ?>
                  @endforeach
                  </tbody>
                  <tfoot>
                    <tr style="background: slategray; color: white; font-size: 18px;">
                      <td colspan="5" align="right"><b>{{__('Total')}}</b></td>
                      <td>{{$total_sale_price}}</td>
                      <td>{{$total_discount}}</td>
                      <td>{{$total_purchase}}</td>
                      <td>{{$total_remaining_stock}}</td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div>
        </div>
    </section>
  	

</div>


@endsection
