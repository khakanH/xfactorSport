@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Add Classes')}}</h1>
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


            @if ($errors->any())
              <div class="callout callout-danger">
                  <h5>Form Submission Errors</h5>
                  @foreach ($errors->all() as $error)
                   <li class="text-danger">{{$error}}</li>
                  @endforeach
              </div>
           @endif

        <div class="card">
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('classes.save-classes')}}">
                @csrf
                <div class="card-body">

                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="sport">{{__('web.Sports')}}</label>
                      <select class="form-control" name="sport" id="sport" required="" onchange="GetCoachList(this.value)">
                          <option value="">{{__('web.Select')}}</option>
                      @foreach($sport as $key)
                        <option <?php if (old('sport') == $key['id']): ?>
                            selected
                        <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
                      @endforeach
                      </select>
                    </div>  
                    <div class="col-md-4">
                      <label for="coach">{{__('web.Coach')}}</label>
                      <select class="form-control" name="coach" id="coach" required="">
                        <option value="">{{__('web.Select')}}</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label for="duration">{{__('web.Duration')}}</label>
                      <select class="form-control" name="duration" id="duration" required="">
                        <option value="">{{__('web.Select')}}</option>
                        @for($i = 1; $i <= 12; $i++)
                        <option <?php if (old('duration') == $i." Month"): ?>
                            selected
                        <?php endif ?> value="{{$i}} Month">{{$i}} Month</option>
                        @endfor
                      </select>
                    </div>
                  </div>

                  <div class="form-group row" id="class">
                    <div class="col-md-12 mb-2">
                      <label for="class" class="h5 mb-4">{{__('web.Time Table')}}:</label>
                      <div class="row">
                        <div class="col-md-2">
                          <label for="class-day">{{__('web.Day')}}</label>
                          <select name="class-day[]" id="class-day"  class="form-control" required="">
                            <option value="monday">{{__('web.Monday')}}</option>
                            <option value="tuesday">{{__('web.Tuesday')}}</option>
                            <option value="wednesday">{{__('web.Wednesday')}}</option>
                            <option value="thursday">{{__('web.Thursday')}}</option>
                            <option value="friday">{{__('web.Friday')}}</option>
                            <option value="saturday">{{__('web.Saturday')}}</option>
                            <option value="sunday">{{__('web.Sunday')}}</option>
                          </select>
                        </div>
                        <div class="col-md-2">
                          <label for="class-from-time">{{__('web.From Time')}}</label>
                          <input type="time" name="class-from-time[]" id="class-from-time"  class="form-control" required="">
                        </div>
                        <div class="col-md-2">
                          <label for="class-to-time">{{__('web.To Time')}}</label>
                          <input type="time" name="class-to-time[]" id="class-to-time"  class="form-control" required="">
                        </div>

                        <div class="col-md-2">
                          <label for="class-location">{{__('web.Location')}}</label>
                          <select class="form-control" name="class-location[]" id="class-location" required="">
                            <option value="">{{__('web.Select')}}</option>
                            <option value="academy">Academy</option>
                            <option value="home">Home</option>
                            <option value="school">School</option>
                          </select>
                        </div>

                        <div class="col-md-2">
                          <label for="class-capacity">{{__('web.Capacity')}}</label>
                          <input type="number" name="class-capacity[]" id="class-capacity" required="" class="form-control">
                        </div>

                         <div class="col-md-2">
                          <label for="class-fee">{{__('web.Fee')}}</label>
                          <input type="number" name="class-fee[]" id="class-fee" required="" class="form-control">
                        </div>
                         


                      </div>

                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-2">
                          <label for="">&nbsp;</label><br>
                          <button type="button" id="class-add" class="btn btn-primary" style="width: 100%;">{{__('web.Add')}}</button>
                        </div>
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


<script type="text/javascript">
  var class_id = 1; 

$("#class-add").click(function(){
  var html =  '<div class="col-md-12 mb-2" id="class-div-'+class_id+'">'+
              '<div class="row">'+
                '<div class="col-md-2">'+
                          '<label for="class-day">{{__("web.Day")}}</label>'+
                          '<select name="class-day[]" id="class-day"  class="form-control" required>'+
                            '<option value="monday">{{__("web.Monday")}}</option>'+
                            '<option value="tuesday">{{__("web.Tuesday")}}</option>'+
                            '<option value="wednesday">{{__("web.Wednesday")}}</option>'+
                            '<option value="thursday">{{__("web.Thursday")}}</option>'+
                            '<option value="friday">{{__("web.Friday")}}</option>'+
                            '<option value="saturday">{{__("web.Saturday")}}</option>'+
                            '<option value="sunday">{{__("web.Sunday")}}</option>'+
                          '</select>'+
                '</div>'+
                '<div class="col-md-2">'+
                  '<label for="class-from-time">{{__("web.From Time")}}</label>'+
                  '<input type="time" name="class-from-time[]" id="class-from-time" required="" class="form-control">'+
                '</div>'+
                '<div class="col-md-2">'+
                  '<label for="class-to-time">{{__("web.To Time")}}</label>'+
                  '<input type="time" name="class-to-time[]" id="class-to-time" required="" class="form-control">'+
                '</div>'+
                '<div class="col-md-2">'+
                  '<label for="class-location">{{__("web.Location")}}</label>'+
                  '<select class="form-control" name="class-location[]" id="class-location" required="">'+
                    '<option value="">{{__("web.Select")}}</option>'+
                    '<option value="academy">Academy</option>'+
                    '<option value="home">Home</option>'+
                    '<option value="school">School</option>'+
                  '</select>'+
                '</div>'+
                '<div class="col-md-1">'+
                  '<label for="class-capacity">{{__("web.Capacity")}}</label>'+
                  '<input type="number" name="class-capacity[]" id="class-capacity" required="" class="form-control">'+
                '</div>'+
                '<div class="col-md-1">'+
                    '<label for="class-fee">{{__("web.Fee")}}</label>'+
                    '<input type="number" name="class-fee[]" id="class-fee" required="" class="form-control">'+
                '</div>'+
                '<div class="col-md-2">'+
                  '<label for="">&nbsp;</label><br>'+
                    '<button type="button" id="class_remove-'+class_id+'" class="btn btn-danger" style="width: 100%;">{{__("web.Remove")}}</button>'+
                '</div>'+
              '</div>'+
            '</div>';
    class_id++;         
   $("#class").append(html);
});

$(document).on('click', 'button[id^="class_remove-"]',function(){
    var getId = $(this).attr('id');
    var splitAttrId = getId.split('-');
    var getId = splitAttrId[1];
    $('#class-div-'+getId).remove();

    // (this).parent('div').parent('div').parent('div').remove();
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });
}

</script>


@endsection
