
@extends('layouts.app')
@section('content')



<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Receive Payment From Members')}}</h1>
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
        <form action="{{route('unions.update-receive-payment')}}" method="post" enctype="multipart/form-data">
        @csrf
          <input type="hidden" name="id" id="id" value="{{$union_member->id}}">

                <div class="card-body">
                        <div class="form-group row">
                        <div class="col-md-4">
                                <label>{{ __('web.Member Name') }}</label>
                                <input type="hidden" name="member_id" id="member_id" value="@if(old('member_id')){{old('member_id')}}@else{{$union_member->member_id}}@endif">

                                 <input autocomplete="off" id="name" name="name" list="member_list" class="form-control" required value="@if(old('member_name')){{old('member_name')}}@else{{$union_member->member_name->name}}@endif">

                      <datalist id="member_list" >

                      @foreach($member as $key)
                      <option data-value="{{$key['id']}}-{{$key['name']}}" value="{{$key['name']}}"></option>
                      @endforeach

                      </datalist>
                        </div>

                        <div class="col-md-4">
                          <label>{{ __('web.Union Name') }}</label>
                          <select class="form-control" id="union_name" name="union_name" required="">
                            <option value="">{{__('web.Select')}}</option>
                            @foreach($union as $key)
                            <option <?php if (old('union_name') == $key['union_id']): ?>
                              selected
                              <?php elseif($union_member->union_id == $key['union_id']): ?>
                                selected
                            <?php endif; ?> value="{{$key['union_id']}}">{{$key['name']}}</option>
                            @endforeach
                          </select>  
                        </div>
                        
                        <div class="col-md-4">
                          <label>{{ __('web.Year') }}</label>
                          <input id="year" name="year" list="year_list" class="form-control" required value="@if(old('year')){{ old('year') }}@else{{$union_member->year}}@endif" onkeypress="if(this.value.length==4) return false;" pattern="^(20)\d{2}$" placeholder="yyyy">
                          <datalist id="year_list">
                                @for($i = 2017; $i <= 2030; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor
                          </datalist>
                        </div>

                        <div class="col-md-4">
                          <label>{{ __('web.Amount') }}</label>
                          <input type="number" id="amount" name="amount" class="form-control" required value="@if(old('amount')){{ old('amount') }}@else{{$union_member->amount}}@endif">
                        </div>

                        <div class="col-md-4">
                          <label>{{ __('web.Payment Date') }}</label>
                          <input type="date" id="payment_date" name="payment_date" class="form-control" required value="@if(old('payment_date')){{ old('payment_date') }}@else{{$union_member->payment_date}}@endif">
                        </div>




                        </div>
                        
                        </div>

                        <div class="card-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                    </form>
            </div>
        </div>
    </section>
  	

</div>
<script type="text/javascript">


   $(document).ready(function() {

    $('#name').change(function()
    {
        var value = $('#name').val();
        var info = $('#member_list [value="' + value + '"]').data('value').split("-");

        document.getElementById("member_id").value = info[0];

        document.getElementById("name").value = info[1];
    });
});
 
  
</script>



@endsection


