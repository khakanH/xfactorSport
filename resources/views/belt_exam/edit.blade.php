@extends('layouts.app')

@section('content')





<div class="content-wrapper">



<section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>{{__('web.Belt Exam')}}</h1>

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

              <form role="form" method="post" enctype="multipart/form-data" action="{{route('register.update-belt-exam')}}">

                @csrf



                <div class="card-body">

                  <input type="hidden" name="id" id="id" value="{{$belt_exam->id}}">

                  <input type="hidden" name="member_id" id="member_id" value="@if(old('member_id')){{old('member_id')}}@else{{$belt_exam->member_id}}@endif">



                  <div class="form-group row">

                    <div class="col-md-4">

                      <label for="name">{{__('web.Belt Exam Name')}}</label>
                       <input autocomplete="off" id="belt_exam_name" name="belt_exam_name" class="form-control" required value="@if(old('belt_exam_name')){{old('belt_exam_name')}}@else{{$belt_exam->belt_exam_name}}@endif">

                    </div>

                    <div class="col-md-4">

                      <label for="name">{{__('web.Member Name')}}</label>
                         <input autocomplete="off" id="name" name="name" list="member_list" class="form-control" required value="@if(old('name')){{old('name')}}@else{{$belt_exam->member_info->name}}@endif">

                      <datalist id="member_list" >

                      @foreach($member as $key)
                      <option data-value="{{$key['id']}}-{{$key['name']}}" value="{{$key['name']}}"></option>
                      @endforeach

                      </datalist>
                     
                     
                    </div>

                    <div class="col-md-4">

                      <label for="sport">{{__('web.Sports')}}</label>

                      <select required="" name="sport" id="sport" class="form-control" onchange="GetCoachList(this.value)">

                        <option selected="" value="">{{__('web.Select')}}</option>

                        @foreach($sport as $key)

                        <option <?php if (old('sport') == $key['id']): ?>

                            selected

                            <?php elseif($belt_exam->sport_id == $key['id']): ?>



                            selected



                        <?php endif ?>  value="{{$key['id']}}">{{$key['name']}}</option>

                        @endforeach

                      </select>

                    </div>

                      

                  



                  </div>







                  <div class="form-group row">




                    <div class="col-md-4">

                      <label for="sport">{{__('web.Coach')}}</label>

                      <select class="form-control" name="coach" id="coach" onfocus="return style.boxShadow='0 0 0px';">
                    <option value="">{{__('web.Select')}}</option>
                  </select>

                    </div>




   <div class="col-md-4">

                      <label for="current_belt">{{__('web.Current Belt')}}</label>

                      <select class="form-control" name="current_belt" id="current_belt" required=""  onchange="GetNextBelt(this.value)">

                        <option value="">{{__('web.Select')}}</option>

                        @foreach($belt as $key)

                        <option <?php if(old('current_belt') == $key['id']): ?>

                          selected

                          <?php elseif ($belt_exam->current_belt_id == $key['id']): ?>

                            selected

                        <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>

                        @endforeach

                      </select>

                    </div>

                    <div class="col-md-4">

                      <label for="next_belt">{{__('web.Next Belt')}}</label>

                      <select class="form-control" name="next_belt" id="next_belt" required="">

                        <option value="">{{__('web.Select')}}</option>

                        @foreach($belt as $key)

                        <option <?php if(old('next_belt') == $key['id']): ?>

                          selected

                          <?php elseif ($belt_exam->next_belt_id == $key['id']): ?>

                            selected

                        <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>

                        @endforeach

                      </select>

                    </div>

              
                   

                  </div>





                  <div class="form-group row">

                    <div class="col-md-4">

                      <label for="total_amount">{{__('web.Total Amount')}}</label>

                      <input type="number" class="form-control" id="total_amount" name="total_amount" required="" value="@if(old('total_amount')){{old('total_amount')}}@else{{$belt_exam->total_amount}}@endif">

                    </div>

                    <div class="col-md-4">

                      <label for="paid_amount">{{__('web.Paid Amount')}}</label>

                      <input type="number" id="paid_amount" name="paid_amount" class="form-control" required value="@if(old('paid_amount')){{old('paid_amount')}}@else{{$belt_exam->paid_amount}}@endif" onkeyup="GetRemainingAmount(this.value)">

                    </div>

                    <div class="col-md-4">

                      <label for="remaining_amount">{{__('web.Remaining Amount')}}</label>

                      <input type="number" class="form-control" id="remaining_amount" name="remaining_amount" required="" value="@if(old('remaining_amount')){{old('remaining_amount')}}@else{{$belt_exam->remaining_amount}}@endif" readonly="">

                    </div>

                  </div>



                  <div class="form-group row">

                    <div class="col-md-4">

                      <label for="exam_date">{{__('web.Exam Date')}}</label>

                      <input type="date" class="form-control datepicker_dropdown" id="exam_date" name="exam_date" required="" value="@if(old('exam_date')){{old('exam_date')}}@else{{$belt_exam->exam_date}}@endif">

                    </div>

                    <div class="col-md-4">

                      <label for="result_date">{{__('web.Result Date')}}</label>

                      <input type="date" class="form-control datepicker_dropdown" id="result_date" name="result_date" required="" value="@if(old('result_date')){{old('result_date')}}@else{{$belt_exam->result_date}}@endif">

                    </div>

                     
                     <div class="col-md-4">

                      <label for="">{{__('web.Documents')}}</label>

                      <div class="custom-file">

                      <input type="file" class="custom-file-input" id="document" name="document[]" multiple="" accept=

"application/msword, application/pdf, image/*">

                      <label class="custom-file-label" for="document">{{__('web.Choose file')}}</label>

                    </div>

                    </div>


                  </div>



                   <div class="form-group row">

                    <div class="col-md-12">

                      <label for="result">{{__('web.Result')}}</label>

                      <textarea name="result" id="result" class="form-control" rows="3">@if(old('result')){{old('result')}}@else{{$belt_exam->result}}@endif</textarea>

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



 $(document).ready(function() {

    $('#name').change(function()
    {
        var value = $('#name').val();
        var info = $('#member_list [value="' + value + '"]').data('value').split("-");

        document.getElementById("member_id").value = info[0];

        document.getElementById("name").value = info[1];
    });
});
 
  function GetRemainingAmount(val)

  {

    var total_amount = document.getElementById("total_amount").value;



    document.getElementById("remaining_amount").value = +total_amount - +val;

  }


  function GetNextBelt(val)
  { 
    document.getElementById("next_belt").value = ++val;
  }




  $( document ).ready(function() {

        var sport_id = '<?php echo empty($belt_exam->sport_id)?999:$belt_exam->sport_id; ?>';
        var coach_id = '<?php echo empty($belt_exam->coach_id)?"":$belt_exam->coach_id; ?>';

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

