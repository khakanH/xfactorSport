@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Dashboard')}}</h1>
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


            <form method="post" action="{{route('dashboard-filter')}}">
              <div class="row">
              @csrf

                <div class="col-sm-2"></div>

                <div class="col-sm-3">
                  <label for="from_date">{{__('web.From Date')}}:</label>
                  <input type="date" name="from_date" id="from_date" class="form-control" value="{{$from_date}}">
                </div>
                <div class="col-sm-3">
                  <label for="to_date">{{__('web.To Date')}}:</label>
                  <input type="date" name="to_date" id="to_date" class="form-control" value="{{$to_date}}">
                </div>
                

                <div class="col-sm-2">
                  <label for="">&nbsp;</label><br>
                  <button type="submit" class="btn btn-success">{{__('web.Filter')}}</button>
                </div>


                <div class="col-sm-2"></div>

              </div>
              </form>
              <br>
              <br>


        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="far fa-money-bill-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{__('web.Income')}}</span>
                <span class="info-box-number">{{$total_income}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-credit-card"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{__('web.Expense')}}</span>
                <span class="info-box-number">{{$total_expense}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-dollar-sign"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{__('web.Salary')}}</span>
                <span class="info-box-number">{{$salary}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{__('web.Members')}}</span>
                <span class="info-box-number">{{$members_count}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
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
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
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
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
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
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
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
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
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
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
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
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
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
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @elseif($key['capacity'] == $key['members_count'])
                                    <td class="bg-yellow">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
                                        <p class="text text-center text-sm">{{ __('web.Availability') }}
                                          {{$key['members_count']}} / {{$key['capacity']}}
                                        </p>
                                        <i class="fa fa-clock"></i><br>
                                        {{ __('web.From') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['from_time'])) }}<br>
                                        {{ __('web.To') }}:<br>{{ucfirst(substr($key['day'],0,3))}}-{{ date('h:i a',strtotime($key['to_time'])) }}
                                    </td>
                                    @else
                                    <td class="bg-red">
                                        <b>{{ $key->class_data->sport_name->name }} | {{ isset($key->class_data->coach_name->name)?$key->class_data->coach_name->name:"-" }}</b><br>
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


@endsection
