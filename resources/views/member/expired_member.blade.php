@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Expired Members')}}</h1>
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

           



               <form method="post" action="{{route('register.filter-expired')}}">
              <div class="row">
              @csrf
                <div class="col-sm-2" id="from_date_div">
                  <label for="from_date">{{__('web.From Date')}}:</label>
                  <input type="date" name="from_date" id="from_date" class="form-control" value="{{$from_date}}">
                </div>
                <div class="col-sm-2" id="to_date_div">
                  <label for="to_date">{{__('web.To Date')}}:</label>
                  <input type="date" name="to_date" id="to_date" class="form-control" value="{{$to_date}}">
                </div>
                <div class="col-sm-2">
                 <label for="sport">{{__('web.Sports')}}</label>
                  <select class="form-control" name="sport" id="sport" onchange="GetCoachList(this.value)">
                    <option value="">{{__('web.Select')}}</option>
                    @foreach($sport as $key)
                      <option <?php if ($selected_sport == $key['id']): ?>
                        selected
                      <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
                    @endforeach
                  </select>
                </div>
  
                <div class="col-sm-2">
                  <label for="coach">{{__('web.Coach')}}</label>
                  <select class="form-control" name="coach" id="coach" onfocus="return style.boxShadow='0 0 0px';">
                    <option value="">{{__('web.Select')}}</option>
                  </select>
                </div>

                <div class="col-sm-1">
                  <label for="">&nbsp;</label><br>
                  <button type="submit" class="btn btn-success">{{__('web.Filter')}}</button>
                </div>
              

              </div>
              </form>
              <br>













            <div class="card">

                <div class="card-body p-4 table-responsive">
                <table class="table table-striped CommonDataTables" id="">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>{{__('web.Name')}}</th>
                      <th>{{__('web.Email')}}</th>
                      <th>{{__('web.Phone')}}</th>
                      <th>{{__('web.Sports')}}</th>
                      <th>{{__('web.Coach')}}</th>
                      <th>{{__('web.Duration')}}</th>
                      <th>{{__('web.Start Date')}}</th>
                      <th>{{__('web.Expiry Date')}}</th>
                      <th>{{__('web.Sport Fee')}}</th>
                      <th style="text-align: center;">{{__('web.Action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($member as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td><b><a href="{{route('register.details-member',[$key['id']])}}">{{$key['name']}}</a></b></td>
                      <td>{{$key['email']}}</td>
                      <td>{{$key['phone']}}</td>
                      <td>{{$key['sport_name']}}</td>
                      <td>{{$key['coach_name']}}</td>
                      <td>{{$key['duration']}}</td>
                      <td>{{$key['start_date']}}</td>
                      <td>{{$key['expiry_date']}}</td>
                      <td>{{$key['total_fee']}}</td>
                       <td style="text-align: center;"><a title="{{__('web.Print')}}" data-toggle="tooltip" href="{{route('register.print-register',[$key['id']])}}"><i class="fa fa-print text-success"></i></a>&nbsp;&nbsp;<a title="{{__('web.Edit')}}" data-toggle="tooltip" href="{{route('register.edit-register',[$key['id']])}}"><i class="fas fa-edit text-primary"></i></a>&nbsp;&nbsp;<a title="{{__('web.Delete')}}" data-toggle="tooltip" onclick="return confirm('{{__("web.Are you sure?")}}')" href="{{route('register.delete-register',[$key['id']])}}"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>

            </div>




            




        </div>
    </section>
  	

</div>


<script type="text/javascript">
$( document ).ready(function() {

        var sport_id = '<?php echo empty($selected_sport)?999:$selected_sport; ?>';
        var coach_id = '<?php echo empty($selected_coach)?"":$selected_coach; ?>';

            $.ajax({
            type: "GET",
            cache: false,
            async: false,
            url: "{{ config('app.url')}}classes/names-coach/"+sport_id,
            beforeSend: function(){
                           
                        },
            success: function(data) {
                            $('#coach').html(data);
                            document.getElementById("coach").value = coach_id;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });

});
function GetCoachList(sport_id)
{
  if (!sport_id) 
  {
    $('#coach').html('<option value="">{{__("web.Select")}}</option');
    return false;
  }

   $.ajax({
            type: "GET",
            cache: false,
            async: false,
            url: "{{ config('app.url')}}classes/names-coach/"+sport_id,
            beforeSend: function(){
                           
                        },
            success: function(data) {
                            $('#coach').html(data);
                    document.getElementById("coach").style.boxShadow = " 0 0 10px #9ecaed";

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });
}

 
</script>


@endsection
