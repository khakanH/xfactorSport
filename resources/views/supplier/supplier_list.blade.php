@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Supplier List</h1>
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
            <form role="form" method="post" name="userForm" id="userForm" enctype="multipart/form-data" action="{{route('supplier.search-supplier')}}">
                @csrf
                <span class='arrow'>
                                <label class='error'></label>
                                </span>
                <div class="card-body">
                  <div class="form-group row">
                 
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Name') }}</label>
                    <input type="text" name="name" id="Batch" value="@if(isset($name)) {{$name}} @endif" class="form-control"  autocomplete="false"/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Supplier Address') }}</label>
                    <input type="text" name="address" id="Volume" value="@if(isset($address)) {{$address}} @endif" class="form-control"  autocomplete="false"/>
                  </div>
                  </div>
                  <div class="col-md-2">
                  <div class="form-group">
                    <label>{{ __('Supplier Contact') }}</label>
                    <input type="number" name="contact" value="@if(isset($contact)) {{$contact}} @endif" class="form-control" autocomplete="false"/>
                  </div>
                  </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" id="user-submit-btn" class="btn btn-primary">Search</button>
                </div>
              </form>
                <div class="card-body p-4">
                <table class="table table-striped CommonDataTables" >
                  <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th width="25%">Name</th>
                      <th width="25%">Address</th>
                      <th width="25%">Contact Number</th>
                      <th style="text-align: center;" width="30%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($all_supplier as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$key['supplier_name']}}</td>
                      <td>{{$key['supplier_address']}}</td>
                      <td>{{$key['supplier_contact']}}</td>
                      <td style="text-align: center;">
                      &nbsp;&nbsp;&nbsp;<a href="{{route('supplier.edit-supplier',$key['supplier_id'])}}"><i class="fa fa-edit text-info"></i></a>
                      &nbsp;&nbsp;&nbsp;<a onclick="return confirm('Arr you sure?')" href="{{route('supplier.delete-supplier',$key['supplier_id'])}}"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>

            </div>
        </div>
    </section>
  	

</div>


@endsection
