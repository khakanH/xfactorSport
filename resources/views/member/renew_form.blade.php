@extends('layouts.app')

@section('content')

  

<div class="content-wrapper">



<section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>{{__('web.Renew Member')}}</h1>

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

              <form role="form" method="post" enctype="multipart/form-data" action="{{route('register.renew-register')}}">

                @csrf

                <div class="card-body">

                  <input type="hidden" name="id" id="id" value="{{$member->id}}">

                  <div class="form-group row">

                    <div class="col-md-3">

                      <label for="name">{{__('web.Name')}}</label>

                      <input type="text" class="form-control" id="name" name="name" required="" value="@if(old('name')){{old('name')}}@else{{$member->name}}@endif">

                    </div>

                    <div class="col-md-3">

                      <label for="email">{{__('web.Email')}}</label>

                      <input type="email" class="form-control" id="email" name="email" required="" value="@if(old('email')){{old('email')}}@else{{$member->email}}@endif">

                    </div>

                    <div class="col-md-3">

                      <label for="phone">{{__('web.Phone')}}</label>

                      <input type="text" class="form-control" id="phone" name="phone" required="" value="@if(old('phone')){{old('phone')}}@else{{$member->phone}}@endif">

                    </div>

                     <div class="col-md-3">

                      <label for="gender">{{__('web.Gender')}}</label>

                      <select class="form-control" name="gender" id="gender" required="">

                        <option value="male" <?php if (old('gender') == "male"): ?>

                            selected

                            <?php elseif ($member->gender == "male"):?>

                            selected

                        <?php endif ?>>Male</option>

                        <option <?php if (old('gender') == "female"): ?>

                            selected

                            <?php elseif ($member->gender == "female"):?>

                            selected

                        <?php endif ?> value="female">Female</option>

                        <option  <?php if (old('gender') == "other"): ?>

                            selected

                            <?php elseif ($member->gender == "other"):?>

                            selected

                        <?php endif ?>value="other">other</option>

                      </select>

                    </div>

                   

                  </div>



                  <div class="form-group row">

                   

                    <div class="col-md-3">

                      <label for="nationality">{{__('web.Nationality')}}</label>

                      <input id="nationality" name="nationality" list="ntnly" class="form-control" required value="@if(old('nationality')){{ old('nationality') }}@else{{$member->nationality}}@endif">

                      <datalist id="ntnly">

                            <?php

                              $list = (new App\Helpers\MenuHelper)->getNationality();

                            ?>

                      </datalist>

                    </div>

                    <div class="col-md-3">

                      <label for="national_id">{{__('web.National ID')}}</label>

                      <input type="number" class="form-control" id="national_id" name="national_id" required="" value="@if(old('national_id')){{old('national_id')}}@else{{$member->national_id}}@endif">

                    </div>



                     <div class="col-md-3">

                      <label for="dob">{{__('web.Date of Birth')}}</label>

                      <input type="date" class="form-control" id="dob" name="dob" required="" value="@if(old('dob')){{old('dob')}}@else{{$member->dob}}@endif">

                    </div>



                    <div class="col-md-3">

                      <label for="age">{{__('web.Age')}} ({{__('web.Auto')}})</label>

                      <input type="number" class="form-control" id="age" name="age" required="" readonly="" value="@if(old('age')){{old('age')}}@else{{$member->age}}@endif">

                    </div>





                  </div>







                 




















































                  <hr>

                  <br>











                  @php($count_sport = 0)

                  @foreach($member_sport as $key)

                  <div class="form-group row">

                   

                    <div class="col-md-4">

                       <label for="sport{{$count_sport}}">{{__('web.Sports')}}</label>

                      <select class="form-control" name="sport[]" id="sport{{$count_sport}}" required="" onchange="GetBeltsAndCoaches('{{$count_sport}}',this.value)">

                        <option value="">{{__('web.Select')}}</option>

                        @foreach($sport as $sports)

                        <option <?php if ($key['sport_id'] == $sports['id']): ?>

                          selected

                        <?php endif ?> value="{{$sports['id']}}">{{$sports['name']}}</option>

                        @endforeach

                      </select>

                    </div>



                    <div class="col-md-4" id="belt-div{{$count_sport}}" style="<?php if ($key['sport_id'] != 1): ?>

                      display: none;

                    <?php endif ?>">

                       <label for="current_belt{{$count_sport}}">{{__('web.Current Belt')}}</label>

                      <select class="form-control" name="current_belt[]" id="current_belt{{$count_sport}}">

                        <option value="">{{__('web.Select')}}</option>

                        @foreach($belt as $belts)

                         <option value="{{$belts['id']}}" <?php if ($key['current_belt_id'] == $belts['id']): ?>

                           selected

                         <?php endif ?>>{{$belts['name']}}</option>

                         @endforeach

                      </select>

                    </div>

                      

                    <div class="col-md-4">

                       <label for="coach{{$count_sport}}">{{__('web.Coaches')}}</label>

                      <select class="form-control" name="coach[]" id="coach{{$count_sport}}" required="" onchange="GetClassesDuration('{{$count_sport}}',this.value)" onfocus="return style.boxShadow='0 0 0px';">

                        <option value="{{$key['coach_id']}}">{{$key->coach_name->name}}</option>



                      </select>

                    </div>



                    <div class="col-md-4">

                      <label for="start_date{{$count_sport}}">{{__('web.Start Date')}}</label>

                      <input type="date" name="start_date[]" id="start_date{{$count_sport}}" required="" class="form-control" value="{{$key['start_date']}}">

                    </div>

                    

                    <div class="col-md-4">

                       <label for="duration{{$count_sport}}">{{__('web.Duration')}}</label>

                      <select class="form-control" name="duration[]" id="duration{{$count_sport}}" required="" onchange="GetClassesDays('{{$count_sport}}',this.value)" onfocus="return style.boxShadow='0 0 0px';">

                        <option value="{{$key['class_id']}}">{{$key['duration']}}</option>

                      </select>

                    </div>                      



                    <div class="col-md-4">

                      <label for="days{{$count_sport}}">{{__('web.Days')}}</label>

                      <select required="" class="select2 select2-hidden-accessible" multiple="" style="width: 100%;" data-select2-id="a{{$count_sport}}" name="days[{{$count_sport}}][]" id="days{{$count_sport}}" tabindex="-1" aria-hidden="true" onfocus="return style.boxShadow='0 0 0px';" onchange="GetClassesTimeLocation('{{$count_sport}}')">

                        @foreach($key->classes_list->unique('day') as $days)

                        <option selected="" value="{{$days['day']}}">{{ucfirst($days['day'])}}</option>

                        @endforeach

                      </select>

                    </div>





                    <div class="col-md-4">

                      <label for="classes{{$count_sport}}">{{__('web.Classes')}}&nbsp;&nbsp;<i class="fas fa-info-circle" data-toggle="tooltip" data-tooltip="tooltip" title="{{__('web.Format: Day - Class Start Time - Class Location - Availability')}}"></i></label>

                      <select required="" class="select2 select2-hidden-accessible" multiple="" style="width: 100%;" data-select2-id="b{{$count_sport}}" name="classes[{{$count_sport}}][]" id="classes{{$count_sport}}" tabindex="-1" aria-hidden="true" onchange="GetClassesFee('{{$count_sport}}')">

                         @foreach($key->classes_list as $classes)

                        <option selected="" value="{{$classes['classes_days_id']}}">{{ucfirst($classes['day'])}} - {{date('h:i a',strtotime($classes['class_from_time']))}} - {{ucfirst($classes['location'])}}</option>

                        @endforeach

                      </select>

                    </div>





                    <div class="col-md-4">

                      <label for="fee{{$count_sport}}">{{__('web.Fees')}}</label>

                      <input type="number" name="fee[]" id="fee{{$count_sport}}" class="form-control" required="" readonly="" value="{{$key['total_fee']}}">

                    </div>

                  </div>

                  @php($count_sport++)

                  @if(!$loop->last)

                  <hr>

                  <br>

                  @endif

                  @endforeach









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

                      <input type="number" name="total_amount" id="total_amount" class="form-control" required="" readonly="" value="{{$member->total_amount}}">

                      </div>

                      <div class="col-md-12">

                        <label for="discount">{{__('web.Discount')}}</label>

                      <input type="number" name="discount" id="discount" class="form-control" onkeyup="GetGrandTotal(this.value)" value="">

                      </div>

                      <div class="col-md-12">

                        <label for="grand_total">{{__('web.Grand Total')}}</label>

                      <input type="number" name="grand_total" id="grand_total" class="form-control" required="" readonly="" value="{{$member->grand_total}}">

                      </div>

                      <div class="col-md-12">

                        <label for="paid_amount">{{__('web.Paid Amount')}}</label>

                      <input type="number" name="paid_amount" id="paid_amount" class="form-control" required="" onkeyup="GetRemainingAmount(this.value)" value="">

                      </div>

                      <div class="col-md-12">

                        <label for="remaining_amount">{{__('web.Remaining Amount')}}</label>

                      <input type="number" name="remaining_amount" id="remaining_amount" class="form-control" required="" readonly="" value="{{$member->remaining_amount}}">

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





















 































 var count_sport_id = "{{count($member_sport)}}"; 



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

    $('.select2').select2({

      theme: 'bootstrap4'

    });

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



  function Alert(day,time,location)

  {

    alert(day+"-"+time+"-"+location);

  }






</script>





@endsection

