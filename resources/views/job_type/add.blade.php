@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Add Job')}}</h1>
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
              <form role="form" method="post" action="{{route('job_type.save-job')}}">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">{{__('web.Name')}}</label>
                    <input type="text" class="form-control" id="name" name="name" required="">
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


@endsection
