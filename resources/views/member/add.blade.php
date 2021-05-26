@extends('layouts.app')

@section('content')

  

  <style type="text/css">

    .custom-file-labels {

  position: absolute;

  top: 0;

  right: 0;

  left: 0;

  z-index: 1;

  height: calc(2.25rem + 2px);

  padding: 0.375rem 0.75rem;

  font-weight: 0 !important;

  line-height: 1.5;

  color: #495057;

  background-color: #ffffff;

  border: 1px solid #ced4da;

  border-radius: 0.25rem;

  box-shadow: none;

}

.custom-file-labels::after {

  position: absolute;

  top: 0;

  right: 0;

  bottom: 0;

  z-index: 3;

  display: block;

  height: 2.25rem;

  padding: 0.375rem 0.75rem;

  line-height: 1.5;

  color: #495057;

  content: "Browse";

  background-color: #e9ecef;

  border-left: inherit;

  border-radius: 0 0.25rem 0.25rem 0;

}

</style>



<div class="content-wrapper">



<section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>{{__('web.Add Member')}}</h1>

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

              <form role="form" method="post" enctype="multipart/form-data" action="{{route('register.save-register')}}">

                @csrf

                <div class="card-body">



                  <div class="form-group row">

                    <div class="col-md-3">

                      <label for="name">{{__('web.Name')}}</label>

                      <input type="text" class="form-control" id="name" name="name" required="" value="{{old('name')}}">

                    </div>

                    <div class="col-md-3">

                      <label for="email">{{__('web.Email')}}</label>

                      <input type="email" class="form-control" id="email" name="email" required="" value="{{old('email')}}">

                    </div>

                    <div class="col-md-3">

                      <label for="phone">{{__('web.Phone')}}</label>

                      <input type="text" class="form-control" id="phone" name="phone" required="" value="{{old('phone')}}">

                    </div>

                     <div class="col-md-3">

                      <label for="gender">{{__('web.Gender')}}</label>

                      <select class="form-control" name="gender" id="gender" required="">

                        <option value="male">Male</option>

                        <option value="female">Female</option>

                        <option value="other">other</option>

                      </select>

                    </div>

                   

                  </div>



                  <div class="form-group row">

                   

                    <div class="col-md-3">

                      <label for="nationality">{{__('web.Nationality')}}</label>

                      <input id="nationality" name="nationality" list="ntnly" class="form-control" required value="{{ old('nationality') }}">

                      <datalist id="ntnly">

                            <?php

                              $list = (new App\Helpers\MenuHelper)->getNationality();

                            ?>

                      </datalist>

                    </div>

                    <div class="col-md-3">

                      <label for="national_id">{{__('web.National ID')}}</label>

                      <input type="number" class="form-control" id="national_id" name="national_id" required="" value="{{old('national_id')}}">

                    </div>



                     <div class="col-md-3">

                      <label for="dob">{{__('web.Date of Birth')}}</label>

                      <input type="date" class="form-control" id="dob" name="dob" required="" value="{{old('dob')}}">

                    </div>



                    <div class="col-md-3">

                      <label for="age">{{__('web.Age')}} ({{__('web.Auto')}})</label>

                      <input type="number" class="form-control" id="age" name="age" required="" readonly="">

                    </div>





                  </div>







                  <div class="form-group row">

                    <div class="col-md-3">

                     <label for="">{{__('web.Upload Profile Image')}}</label>

                      <div class="custom-file">

                      <input type="file" class="custom-file-input" id="profile_image" name="profile_image" accept="image/*" onclick="clearImage('profile_output')" onchange="profile_loadFile(event)">

                      <label class="custom-file-labels profile_image" for="profile_image">{{__('web.Choose file')}}</label>



                      <img id="profile_output" src="http://localhost/newxfactor/public/images/general_setting/LOGO_64733.png"  height="100" style="display: none; min-height: 100px; object-position: top;  margin: 10px; width: 90%;">










                      </div>

                    </div>

                    <div class="col-md-3">

                      <label for="">{{__('web.Upload ID Card Image')}}</label>

                      <div class="custom-file">

                      <input type="file" class="custom-file-input" id="id_card_image" name="id_card_image[]" multiple="" accept="image/*" onclick="clearImage('id_card_output')" onchange="id_card_loadFile(event)">

                      <label class="custom-file-labels id_card_image" for="id_card_image">{{__('web.Choose file')}}</label>

                       <img id="id_card_output" src="http://localhost/newxfactor/public/images/general_setting/LOGO_64733.png"  height="100" style="display: none; min-height: 100px; object-position: top;  margin: 10px; width: 90%;">

                      </div>

                    </div>

                    <div class="col-md-3">

                      <label for="">{{__('web.Upload Certificate Image')}}</label>

                      <div class="custom-file">

                      <input type="file" class="custom-file-input" id="certificate_image" name="certificate_image[]" multiple="" accept="image/*" onclick="clearImage('certificate_output')" onchange="certificate_loadFile(event)">

                      <label class="custom-file-labels certificate_image" for="certificate_image">{{__('web.Choose file')}}</label>

                      <img id="certificate_output" src="http://localhost/newxfactor/public/images/general_setting/LOGO_64733.png"  height="100" style="display: none; min-height: 100px; object-position: top;  margin: 10px; width: 90%;">

                      </div>

                    </div>

                    <div class="col-md-3">

                       <label for="">{{__('web.Upload Passport Image')}}</label>

                      <div class="custom-file">

                      <input type="file" class="custom-file-input" id="passport_image" name="passport_image[]" multiple="" accept="image/*" onclick="clearImage('passport_output')" onchange="passport_loadFile(event)">

                      <label class="custom-file-labels passport_image" for="passport_image">{{__('web.Choose file')}}</label>


                       <img id="passport_output" src="http://localhost/newxfactor/public/images/general_setting/LOGO_64733.png"  height="100" style="display: none; min-height: 100px; object-position: top;  margin: 10px; width: 90%;">

                      </div>

                    </div>

                  </div>





                  <div class="form-group row" id="guardian_div" style="margin-top: 50px;">
  

                  </div>






































































                  <br><br>
                  <div class="form-group row">

                   

                    <div class="col-md-4">

                       <label for="sport0">{{__('web.Sports')}}</label>

                      <select class="form-control" name="sport[]" id="sport0" required="" onchange="GetBeltsAndCoaches('0',this.value)">

                        <option value="">{{__('web.Select')}}</option>

                        @foreach($sport as $key)

                        <option value="{{$key['id']}}">{{$key['name']}}</option>

                        @endforeach

                      </select>

                    </div>



                    <div class="col-md-4" id="belt-div0" style="display: none;">

                       <label for="current_belt0">{{__('web.Current Belt')}}</label>

                      <select class="form-control" name="current_belt[]" id="current_belt0">

                        <option value="">{{__('web.Select')}}</option>

                         @foreach($belt as $belts)

                         <option value="{{$belts['id']}}">{{$belts['name']}}</option>

                         @endforeach



                      </select>

                    </div>

                      

                    <div class="col-md-4">

                       <label for="coach0">{{__('web.Coaches')}}</label>

                      <select class="form-control" name="coach[]" id="coach0" required="" onchange="GetClassesDuration('0',this.value)" onfocus="return style.boxShadow='0 0 0px';">

                        <option selected="" value="">{{__('web.Select')}}</option>

                      </select>

                    </div>



                    <div class="col-md-4">

                      <label for="start_date0">{{__('web.Start Date')}}</label>

                      <input type="date" name="start_date[]" id="start_date0" required="" class="form-control">

                    </div>

                    

                    <div class="col-md-4">

                       <label for="duration0">{{__('web.Duration')}}</label>

                      <select class="form-control" name="duration[]" id="duration0" required="" onchange="GetClassesDays('0',this.value)" onfocus="return style.boxShadow='0 0 0px';">

                        <option value="">{{__('web.Select')}}</option>

                      </select>

                    </div>                      



                    <div class="col-md-4">

                      <label for="days0">{{__('web.Days')}}</label>

                      <select required="" class="select2 select2-hidden-accessible" multiple="" style="width: 100%;" data-select2-id="a0" name="days[0][]" id="days0" tabindex="-1" aria-hidden="true" onfocus="return style.boxShadow='0 0 0px';" onchange="GetClassesTimeLocation('0')">

                      </select>

                    </div>





                    <div class="col-md-4">

                      <label for="classes0">{{__('web.Classes')}}&nbsp;&nbsp;<i class="fas fa-info-circle" data-toggle="tooltip" data-tooltip="tooltip" title="{{__('web.Format: Day - Class Start Time - Class Location - Availability')}}"></i></label>

                      <select required="" class="select2 select2-hidden-accessible" multiple="" style="width: 100%;" data-select2-id="b0" name="classes[0][]" id="classes0" tabindex="-1" aria-hidden="true" onchange="GetClassesFee('0')">

                      </select>

                    </div>





                    <div class="col-md-4">

                      <label for="fee0">{{__('web.Fees')}}</label>

                      <input type="number" name="fee[]" id="fee0" class="form-control" required="" readonly="" value="0">

                    </div>

                    



                  </div>





                  <div id="more-sports-div">

                    

                  </div>





                  <div class="row">

                    <div class="col-md-3"></div>

                    <div class="col-md-3"></div>

                    <div class="col-md-3"></div>

                    <div class="col-md-3">

                      <button type="button" id="sport-add" class="btn btn-success" style="width: 100%;">{{__('web.Add Sports')}}</button>

                    </div>

                  </div>



                  <br>

                  <hr>

                  <br>

                

                <div class="form-group row">

                  <div class="col-md-7"></div>

                  <div class="col-md-5">

                    <div class="row">

                      <div class="col-md-12">

                        <label for="payment_date">{{__('web.Payment Date')}}</label>

                      <input type="date" name="payment_date" id="payment_date" class="form-control" required="" value="{{date('Y-m-d')}}">

                      </div>

                      <div class="col-md-12">

                        <label for="total_amount">{{__('web.Total Amount')}}</label>

                      <input type="number" name="total_amount" id="total_amount" class="form-control" required="" readonly="">

                      </div>

                      <div class="col-md-12">

                        <label for="discount">{{__('web.Discount')}}</label>

                      <input type="number" name="discount" id="discount" class="form-control" onkeyup="GetGrandTotal(this.value)" value="0">

                      </div>

                      <div class="col-md-12">

                        <label for="grand_total">{{__('web.Grand Total')}}</label>

                      <input type="number" name="grand_total" id="grand_total" class="form-control" required="" readonly="">

                      </div>

                      <div class="col-md-12">

                        <label for="paid_amount">{{__('web.Paid Amount')}}</label>

                      <input type="number" name="paid_amount" id="paid_amount" class="form-control" required="" onkeyup="GetRemainingAmount(this.value)">

                      </div>

                      <div class="col-md-12">

                        <label for="remaining_amount">{{__('web.Remaining Amount')}}</label>

                      <input type="number" name="remaining_amount" id="remaining_amount" class="form-control" required="" readonly="">

                      </div>

                    </div>

                  </div>

                </div>



                

                  



                </div>



                <div class="card-footer">

                  <button type="submit" class="btn btn-primary">{{__('web.Save')}}</button>

                </div>

              </form>

            </div>




         <div class="card card-primary card-outline">
            <div class="card-header">
                  <h3 class="card-title">
                    {{__('web.Time Table')}}
                  </h3>
            </div>
            <div class="card-body">

              <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg bg-primary">
                                <th width="14.2%">{{ __('web.Monday') }}</th>
                                <th width="14.2%">{{ __('web.Tuesday') }}</th>
                                <th width="14.2%">{{ __('web.Wednesday') }}</th>
                                <th width="14.2%">{{ __('web.Thursday') }}</th>
                                <th width="14.2%">{{ __('web.Friday') }}</th>
                                <th width="14.2%">{{ __('web.Saturday') }}</th>
                                <th width="14.2%">{{ __('web.Sunday') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($time_table as $key)
                          <tr>
                            @for($i=1; $i <=7; $i++) 
                              @switch($i) 
                                @case(1)
                                  @if($key['day']=='monday')
                                    @if($key['capacity'] > $key['members_count'])
                                    <td class="bg-green">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @endif
                                    @else
                                    <td class=""></td>
                                    @endif
                                    @break
                                  @case(2)
                                  @if($key['day']=='tuesday')
                                    @if($key['capacity'] > $key['members_count'])
                                    <td class="bg-green">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @endif
                                    @else
                                    <td class=""></td>
                                    @endif
                                    @break 
                                  @case(3)
                                  @if($key['day']=='wednesday')
                                    @if($key['capacity'] > $key['members_count'])
                                    <td class="bg-green">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @endif
                                    @else
                                    <td class=""></td>
                                    @endif
                                    @break
                                  @case(4)
                                  @if($key['day']=='thursday')
                                    @if($key['capacity'] > $key['members_count'])
                                    <td class="bg-green">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @endif
                                    @else
                                    <td class=""></td>
                                    @endif
                                    @break
                                  @case(5)
                                  @if($key['day']=='friday')
                                    @if($key['capacity'] > $key['members_count'])
                                    <td class="bg-green">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @endif
                                    @else
                                    <td class=""></td>
                                    @endif
                                    @break 
                                  @case(6)
                                  @if($key['day']=='saturday')
                                    @if($key['capacity'] > $key['members_count'])
                                    <td class="bg-green">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @endif
                                    @else
                                    <td class=""></td>
                                    @endif
                                    @break 
                                  @case(7)
                                  @if($key['day']=='sunday')
                                    @if($key['capacity'] > $key['members_count'])
                                    <td class="bg-green">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ $key->class_data->coach_name->name }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @endif
                                    @else
                                    <td class=""></td>
                                    @endif
                                    @break        
                              @endswitch
                            @endfor
                          </tr>




                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        
        </div>



        </div>

    </section>

  	



</div>





<script type="text/javascript">

  $(function () {

    $('.select2').select2();

     $('.select2').select2({

      theme: 'bootstrap4'

    });

});





 $("#dob").change(function(){

           var value = $("#dob").val();

            var dob = new Date(value);

            var today = new Date();

            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));

            if(isNaN(age)) {

             age=0;



            }

            else{

              age=age;

            }

            $('#age').val(age);

        });





          $('#dob').change(function() {

         var agecondition=$('#age').val();

           if(agecondition < 18)

            {

                $('#guardian_div').empty();

                if( $('#guardian_div').is(':empty') ) {

                    var html='';

                    html +='<div class="col-md-3">';

                    html +='<label for="guardian_name">{{__("web.Guardian Name")}}</label>';

                    html +='<input type="text" name="guardian_name" id="guardian_name" class="form-control" required/>';

                    html +='</div>';

                    html +='<div class="col-md-3">';

                    html +='<label for="guardian_phone">{{__("web.Guardian Phone")}}</label>';

                    html +='<input type="text" name="guardian_phone" id="guardian_phone" class="form-control" required/>';

                    html +='</div>';

                    html +='<div class="col-md-3">';

                    html +='<label for="">{{__("web.Upload Guardian ID Card")}}</label>';

                    html +='<div class="custom-file">';

                    html +='<input type="file" class="custom-file-input" id="guardian_id_card_image" name="guardian_id_card_image[]" multiple="" onclick="clearImage(\'guardian_output\')" onchange="guardian_loadFile(event)" accept="image/*">';

                    html +='<label class="custom-file-labels guardian_id_card_image" for="guardian_id_card_image">{{__("web.Choose file")}}</label>';


                     html +='<img id="guardian_output" src="http://localhost/newxfactor/public/images/general_setting/LOGO_64733.png"  height="100" style="display: none; min-height: 100px; object-position: top;  margin: 10px; width: 90%;">';

                    html +='</div>';

                    html +='</div>';

                    $('#guardian_div').append(html);

                    $('#guardian_id_card_image').change(function(e){

                        var files = e.target.files;

                        var fileName = files.length+" {{__('web.File(s) Selected')}}";

                        $('.guardian_id_card_image').html(fileName);



                });



                }

            }else{

                $('#guardian_div').empty();

            }

         });

















$('#profile_image').change(function(e){

        var files = e.target.files;

        var fileName = files.length+" {{__('web.File(s) Selected')}}";

        $('.profile_image').html(fileName);

});



$('#id_card_image').change(function(e){

        var files = e.target.files;

        var fileName = files.length+" {{__('web.File(s) Selected')}}";

        $('.id_card_image').html(fileName);

});



$('#certificate_image').change(function(e){

        var files = e.target.files;

        var fileName = files.length+" {{__('web.File(s) Selected')}}";

        $('.certificate_image').html(fileName);

});



$('#passport_image').change(function(e){

        var files = e.target.files;

        var fileName = files.length+" {{__('web.File(s) Selected')}}";

        $('.passport_image').html(fileName);

});













 































 var count_sport_id = 1; 



$("#sport-add").click(function(){

  var html = '<div class="form-group row" id="sport-row'+count_sport_id+'">'+

                '<br><div class="row"><div class="col-md-12"><button type="button" class="btn btn-danger" style="width:100%;" id="sport_remove-'+count_sport_id+'">Remove</button</div></div><br><br>'+



                

                    '<div class="col-md-4">'+

                       '<label for="sport'+count_sport_id+'">{{__("web.Sports")}}</label>'+

                      '<select class="form-control" name="sport[]" id="sport'+count_sport_id+'" required="" onchange="GetBeltsAndCoaches('+count_sport_id+',this.value)">'+

                       ' <option value="">{{__("web.Select")}}</option>';

                          @foreach($sport as $key)

                          html +='<option value="{{ $key["id"] }}">{{ $key["name"] }}</option>';

                          @endforeach

                      html +='</select>'+

                    '</div>'+



                    '<div class="col-md-4" id="belt-div'+count_sport_id+'" style="display: none;">'+

                       '<label for="current_belt'+count_sport_id+'">{{__("web.Current Belt")}}</label>'+

                      '<select class="form-control" name="current_belt[]" id="current_belt'+count_sport_id+'">'+

                        '<option value="">{{__("web.Select")}}</option>';

                            @foreach($belt as $belts)

                          html +='<option value="{{ $belts["id"] }}">{{ $belts["name"] }}</option>';

                          @endforeach

                     html +='</select>'+

                    '</div>'+

                      

                    '<div class="col-md-4">'+

                       '<label for="coach'+count_sport_id+'">{{__("web.Coaches")}}</label>'+

                      '<select class="form-control" name="coach[]" id="coach'+count_sport_id+'" required="" onchange="GetClassesDuration('+count_sport_id+',this.value)" onfocus="return style.boxShadow=\'0 0 0px\';">'+

                        '<option selected="" value="">{{__("web.Select")}}</option>'+

                      '</select>'+

                    '</div>'+



                    '<div class="col-md-4">'+

                      '<label for="start_date'+count_sport_id+'">{{__("web.Start Date")}}</label>'+

                      '<input type="date" name="start_date[]" id="start_date'+count_sport_id+'" required="" class="form-control">'+

                    '</div>'+

                    

                    '<div class="col-md-4">'+

                       '<label for="duration'+count_sport_id+'">{{__("web.Duration")}}</label>'+

                      '<select class="form-control" name="duration[]" id="duration'+count_sport_id+'" required="" onchange="GetClassesDays('+count_sport_id+',this.value)" onfocus="return style.boxShadow=\'0 0 0px\';">'+

                        '<option value="">{{__("web.Select")}}</option>'+

                      '</select>'+

                    '</div>'+



                    '<div class="col-md-4">'+

                      '<label for="days'+count_sport_id+'">{{__("web.Days")}}</label>'+

                      '<select required="" class="select2 select2-hidden-accessible" multiple="" style="width: 100%;" data-select2-id="a'+count_sport_id+'" name="days['+count_sport_id+'][]" id="days'+count_sport_id+'" tabindex="-1" aria-hidden="true" onfocus="return style.boxShadow=\'0 0 0px\';" onchange="GetClassesTimeLocation('+count_sport_id+')">'+

                      '</select>'+

                    '</div>'+





                    '<div class="col-md-4">'+

                      '<label for="classes'+count_sport_id+'">{{__("web.Classes")}}&nbsp;&nbsp;<i class="fas fa-info-circle" data-toggle="tooltip" data-tooltip="tooltip" title="{{__("web.Format: Day - Class Start Time - Class Location - Availability")}}"></i></label>'+

                      '<select required="" class="select2 select2-hidden-accessible" multiple="" style="width: 100%;" data-select2-id="b'+count_sport_id+'" name="classes['+count_sport_id+'][]" id="classes'+count_sport_id+'" tabindex="-1" aria-hidden="true" onchange="GetClassesFee('+count_sport_id+')">'+

                      '</select>'+

                    '</div>'+





                    '<div class="col-md-4">'+

                      '<label for="fee'+count_sport_id+'">{{__("web.Fees")}}</label>'+

                      '<input type="number" name="fee[]" id="fee'+count_sport_id+'" class="form-control" required="" readonly="" value="0">'+

                    '</div>'+



                  '</div>';







    $("#more-sports-div").append(html);

    $('[data-toggle="tooltip"]').tooltip();

    $('.select2').select2();

    $('input[type="date"]').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        minDate: new Date('2011/1/1'),
    });
    

    count_sport_id++;         





});







$(document).on('click', 'button[id^="sport_remove-"]',function(){

    var getId = $(this).attr('id');

    var splitAttrId = getId.split('-');

    var getId = splitAttrId[1];

    $('#sport-row'+getId).remove();

    GetTotalBill();



    // (this).parent('div').parent('div').parent('div').remove();

        });



































































//____________________________________________________________________________



function GetBeltsAndCoaches(row_id,sport_id)

{



  if (!sport_id) 

  {

    var sport_id = "0";

  }



  if (sport_id == 1) 

  {

    document.getElementById("belt-div"+row_id).style.display = "block";

    document.getElementById("current_belt"+row_id).required = true;

  }

  else

  {

    document.getElementById("belt-div"+row_id).style.display = "none";

    document.getElementById("current_belt"+row_id).required = false;



  }



    $.ajax({

            type: "GET",

            cache: false,

            url: "{{ config('app.url')}}classes/names-coach/"+sport_id,

            beforeSend: function(){

            },

            success: function(data) {

                            document.getElementById('coach'+row_id).style.boxShadow = " 0 0 10px #9ecaed";

                            $('#coach'+row_id).html(data);

                            $('#duration'+row_id).html('<option value="" selected="">{{__("web.Select")}}</option>');

                            $('#days'+row_id).html('');

                            $('#classes'+row_id).html('');

                            document.getElementById("fee"+row_id).value = 0;

                            GetTotalBill();

                          





            },

            error: function(jqXHR, textStatus, errorThrown) {

                alert('Exception:' + errorThrown);

            }

        });

}



function GetClassesDuration(row_id,coach_id)

{

    if (!coach_id) 

    {

      coach_id = "0";

    }



    var sport_id = document.getElementById('sport'+row_id).value;



    if (!sport_id) 

    {

      sport_id = "0";

    }



    $.ajax({

            type: "GET",

            cache: false,

            url: "{{ config('app.url')}}register/get-classes-duration-list/"+sport_id+"/"+coach_id,

            beforeSend: function(){

            },

            success: function(data) {

                            document.getElementById('duration'+row_id).style.boxShadow = " 0 0 10px #9ecaed";

                            $('#duration'+row_id).html('<option value="" selected="">{{__("web.Select")}}</option>'+data);

                            $('#days'+row_id).html('');

                            $('#classes'+row_id).html('');

                            document.getElementById("fee"+row_id).value = 0;

                            GetTotalBill();

                        



            },

            error: function(jqXHR, textStatus, errorThrown) {

                alert('Exception:' + errorThrown);

            }

        });

}



function GetClassesDays(row_id,classes_id)

{

    if (!classes_id) 

    {

      var classes_id = "0";

    }



    $.ajax({

            type: "GET",

            cache: false,

            url: "{{ config('app.url')}}register/get-classes-days-list/"+classes_id,

            beforeSend: function(){

            },

            success: function(data) {

                            document.getElementById('days'+row_id).focus();

                            $('#days'+row_id).html(data);

                            $('#classes'+row_id).html('');

                            document.getElementById("fee"+row_id).value = 0;

                            GetTotalBill();

                           



            },

            error: function(jqXHR, textStatus, errorThrown) {

                alert('Exception:' + errorThrown);

            }

        });

}



function GetClassesTimeLocation(row_id)

{ 

  var selected = [];

  for (var option of document.getElementById('days'+row_id).options) {

    if (option.selected) {

      selected.push(option.value);

    }

  }

    var class_id  = document.getElementById('duration'+row_id).value;



    if (!class_id) 

    {

      class_id = "0";

    }

   $.ajax({

            type: "POST",

            cache: false,

            url: "{{ config('app.url')}}register/get-classes-time-list",

            data: {

            _token: "{{csrf_token()}}",

            "days_name": selected,

            "class_id" : class_id,

            }, 

            beforeSend: function(){

            },

            success: function(data) {

                            document.getElementById('classes'+row_id).focus();

                            $('#classes'+row_id).html(data);

                            document.getElementById("fee"+row_id).value = 0;

                            GetTotalBill();

                           



            },

            error: function(jqXHR, textStatus, errorThrown) {

                alert('Exception:' + errorThrown);

            }

        });



}





function GetClassesFee(row_id)

{

  var selected = [];

  for (var option of document.getElementById('classes'+row_id).options) {

    if (option.selected) {

      selected.push(option.value);

    }

  }

  var start_date = document.getElementById("start_date"+row_id).value;
  var duration = document.getElementById("duration"+row_id).value;

   $.ajax({

            type: "POST",

            cache: false,

            url: "{{ config('app.url')}}register/get-classes-fee",

            data: {

            _token: "{{csrf_token()}}",

            "classes_ids": selected,
            "start_date" : start_date,
            "duration"   : duration,

            }, 

            beforeSend: function(){

            },

            success: function(data) {

                            document.getElementById("fee"+row_id).value = data;

                            GetTotalBill();

                           

            },

            error: function(jqXHR, textStatus, errorThrown) {

                alert('Exception:' + errorThrown);

            }

        });







        

}

  



  function GetTotalBill()

  {

      var fee = document.getElementsByName("fee[]");

      var total_amount = 0;

      for(var i = 0; i < fee.length; i ++)

      {

        total_amount = +fee[i].value + +total_amount;

      }

      document.getElementById("total_amount").value = total_amount;

      document.getElementById("grand_total").value = total_amount;



      document.getElementById("discount").value = "";

      document.getElementById("paid_amount").value = "";

      document.getElementById("remaining_amount").value = 0;

  }

  



  function GetGrandTotal(val)

  {

    var total = document.getElementById("total_amount").value;



    document.getElementById("grand_total").value = +total - +val;



  }



  function GetRemainingAmount(val)

  {

    var grand_total = document.getElementById("grand_total").value;



    document.getElementById("remaining_amount").value = +grand_total - +val;

  }


var profile_loadFile = function(event) {
    var profile_output = document.getElementById('profile_output');
    profile_output.style.display = "inline-block";
    profile_output.src = URL.createObjectURL(event.target.files[0]);
    profile_output.onload = function() {
      URL.revokeObjectURL(profile_output.src) // free memory
    }
  };

var id_card_loadFile = function(event) {
    var id_card_output = document.getElementById('id_card_output');
    id_card_output.style.display = "inline-block";
    id_card_output.src = URL.createObjectURL(event.target.files[0]);
    profile_output.onload = function() {
      URL.revokeObjectURL(id_card_output.src) // free memory
    }
  };


var certificate_loadFile = function(event) {
    var certificate_output = document.getElementById('certificate_output');
    certificate_output.style.display = "inline-block";
    certificate_output.src = URL.createObjectURL(event.target.files[0]);
    certificate_output.onload = function() {
      URL.revokeObjectURL(certificate_output.src) // free memory
    }
  };

var passport_loadFile = function(event) {
    var passport_output = document.getElementById('passport_output');
    passport_output.style.display = "inline-block";
    passport_output.src = URL.createObjectURL(event.target.files[0]);
    passport_output.onload = function() {
      URL.revokeObjectURL(passport_output.src) // free memory
    }
  };

  var guardian_loadFile = function(event) {
    var guardian_output = document.getElementById('guardian_output');
    guardian_output.style.display = "inline-block";
    guardian_output.src = URL.createObjectURL(event.target.files[0]);
    guardian_output.onload = function() {
      URL.revokeObjectURL(guardian_output.src) // free memory
    }
  };

  function clearImage(id)
  {
    document.getElementById(id).src = "";
    document.getElementById(id).style.display = "none";

  }


</script>





@endsection

