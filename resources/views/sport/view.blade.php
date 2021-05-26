@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Sports')}}</h1>
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

                <div class="card-body p-4">
                <table class="table table-striped CommonDataTables" id="">
                  <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th width="65%">{{__('web.Name')}}</th>
                      <th style="text-align: center;" width="30%">{{__('web.Action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($sports as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$key['name']}}</td>
                      <td style="text-align: center;"><a href="{{route('sports.edit-sport',[$key['id']])}}"><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;<a onclick="return confirm('{{__("web.Are you sure?")}}')" href="{{route('sports.delete-sport',[$key['id']])}}"><i class="fa fa-trash text-danger"></i></a></td>
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
