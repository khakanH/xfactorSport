@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Renew Members')}}</h1>
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
                  <div class="card-body">
                    <div class="form-group row">
                      <div class="col-md-4">

                      <label for="name">{{__('web.Select Member')}}</label>

                      <input id="member" name="member" list="member_list" class="form-control" required value="{{old('member')}}">

                      <datalist id="member_list">

                      @foreach($member as $key)
                      <option data-value="{{$key['id']}}" value="{{$key['name']}}"></option>
                      @endforeach

                      </datalist>

                      </div>
                    </div>
                  </div>
                </div>
           

                    




        </div>
    </section>
  	

</div>

<script type="text/javascript">

$(document).ready(function() {

    $('#member').change(function()
    {
        var value = $('#member').val();
        var member_id = $('#member_list [value="' + value + '"]').data('value');

         $("body").fadeOut();
        window.location.href = "{{config('app.url')}}register/renew-register-form/"+member_id;
    });
});
 
</script>

@endsection
