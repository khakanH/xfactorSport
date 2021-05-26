@extends('layouts.app')
@section('content')
<style type="text/css">
  .detail-row-div{
    padding: 25px;
  }
  .detail-col-div{
    padding:  12px;
  }

  .uploaded_img_file {
    border: 0 none; 
    display: inline-block; 
    height: auto; 
    max-width: 100%;
    vertical-align: middle;
    margin: 7px;
  }
</style>

<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-md-6">
            <h1>{{__('web.Members Detials')}} ({{$member->name}})</h1>
          </div>
          <div class="col-md-5"></div>
          <div class="col-md-1" ><button onclick='PrintMemberDetails("{{$member->id}}")' style="float: right;" type="button" class="btn btn-success">Print</button></div>
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

              <div class="row detail-row-div">
                <div class="col-md-3 detail-col-div"><b>{{__('web.Name')}}:</b> {{$member->name}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Email')}}:</b> {{$member->email}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Phone')}}:</b> {{$member->phone}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Gender')}}:</b> {{$member->gender}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Nationality')}}:</b> {{$member->nationality}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.National ID')}}:</b> {{$member->national_id}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Date of Birth')}}:</b> {{$member->dob}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Age')}}:</b> {{$member->age}}</div>

                <div class="col-md-3 detail-col-div"><b>{{__('web.Profile Image')}}:</b><br> 
                @if(empty($member->profile_image))
                {{__('web.Not Found')}}
                @else 
                <ul>
                <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $member->profile_image ?>"><img class="uploaded_img_file" src="<?php echo config('app.img_url') ?><?php echo $member->profile_image ?>"></a></li>
                </ul>
                @endif
                </div>
                
                <div class="col-md-3 detail-col-div"><b>{{__('web.ID Card')}}:</b>
                @if(empty($member->id_card_image))
                <br>
                {{__('web.Not Found')}}
                @else 
                  <ul>
                    <?php 
                    $cert = explode(",", $member->id_card_image); 
                    foreach ($cert as $key):
                    ?>
                    <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $key ?>"><img class="uploaded_img_file" src="<?php echo config('app.img_url') ?><?php echo $key ?>"></a></li>
                    <?php endforeach; ?>
                  </ul>
                @endif  
                </div>
                

                <div class="col-md-3 detail-col-div"><b>{{__('web.Certificates')}}:</b>
                @if(empty($member->certificate_image))
                <br>
                {{__('web.Not Found')}}
                @else 
                  <ul>
                    <?php 
                    $cert = explode(",", $member->certificate_image); 
                    foreach ($cert as $key):
                    ?>
                    <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $key ?>"><img class="uploaded_img_file" src="<?php echo config('app.img_url') ?><?php echo $key ?>"></a></li>
                    <?php endforeach; ?>
                  </ul>
                @endif
                </div>


                <div class="col-md-3 detail-col-div"><b>{{__('web.Passport')}}:</b> 
                @if(empty($member->passport_image))
                <br>
                {{__('web.Not Found')}}
                @else 
                  <ul>
                    <?php 
                    $cert = explode(",", $member->passport_image); 
                    foreach ($cert as $key):
                    ?>
                    <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $key ?>"><img class="uploaded_img_file" src="<?php echo config('app.img_url') ?><?php echo $key ?>"></a></li>
                    <?php endforeach; ?>
                  </ul>
                @endif
                </div>
                
                @if($member->age < 18)
                <div class="col-md-3 detail-col-div"><b>{{__('web.Guardian Name')}}:</b> {{$member->guardian_name}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Guardian Phone')}}:</b> {{$member->guardian_phone}}</div>
                <div class="col-md-3 detail-col-div"><b>{{__('web.Guardian ID Card')}}:</b>
                @if(empty($member->guardian_id_card_image))
                <br>
                {{__('web.Not Found')}}
                @else 
                  <ul>
                    <?php 
                    $cert = explode(",", $member->guardian_id_card_image); 
                    foreach ($cert as $key):
                    ?>
                    <li><a target="_blank" href="<?php echo config('app.img_url') ?><?php echo $key ?>"><img class="uploaded_img_file" src="<?php echo config('app.img_url') ?><?php echo $key ?>"></a></li>
                    <?php endforeach; ?>
                  </ul>
                @endif
                 </div>
                @endif
              </div>
              
              <hr>
              <div class="row detail-row-div">
              
                <h4>{{__('web.Sport List')}}</h4>
                

                <div class="card-body p-4 table-responsive">
                  <table class="table" id="">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('web.Sports')}}</th>
                            <th>{{__('web.Coach')}}</th>
                            <th>{{__('web.Duration')}}</th>
                            <th>{{__('web.Start Date')}}</th>
                            <th>{{__('web.Expiry Date')}}</th>
                            <th>{{__('web.Sport Fee')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($member_sport as $key)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$key->sport_name->name}}</td>
                            <td>{{$key->coach_name->name}}</td>
                            <td>{{$key['duration']}}</td>
                            <td>{{$key['start_date']}}</td>

                            @php($current_date = time())
                            @php($plus_one_month = strtotime('+ 1 Month',time()))


                            <td <?php if ((time() >= strtotime($key['expiry_date'])) || ((strtotime($key['expiry_date']) >= $current_date && strtotime($key['expiry_date']) <= $plus_one_month)) ): ?>
                                class="bg-danger"
                            <?php endif ?>  >{{$key['expiry_date']}}</td>
                            


                            <td>{{$key['total_fee']}}</td>
                        </tr>
                         <tr style="background: #F5F5F5; font-size: 18px; font-weight: 600; text-align: center;">
                            <th colspan="7">Classes</th>
                        </tr>
                            <tr style="background: #F5F5F5;">
                                <th></th>
                                <th>#</th>
                                <th>{{__('web.Day')}}</th>
                                <th>{{__('web.Class From Time')}}</th>
                                <th>{{__('web.Class To Time')}}</th>
                                <th>{{__('web.Location')}}</th>
                                <th>{{__('web.Fee')}}</th>

                            </tr>
                            @foreach($key->classes_list as $classes)
                            <tr style="background: #F5F5F5;">
                                <td></td>
                                <td>{{$loop->iteration}}</td>
                                <td>{{__('web.'.ucfirst($classes['day']))}}</td>
                                <td>{{date('h:i a',strtotime($classes['class_from_time']))}}</td>
                                <td>{{date('h:i a',strtotime($classes['class_to_time']))}}</td>
                                <td>{{ucfirst($classes['location'])}}</td>
                                <td>{{$classes['fee']}}</td>
                            </tr>
                            @endforeach
                           
                            <tr style="background: slategray;"><td colspan="7"></td></tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2" align="right"><b>{{__('web.Total Amount')}}:</b></td>
                            <td>{{$member->total_amount}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2" align="right"><b>{{__('web.Discount')}}:</b></td>
                            <td>{{$member->discount}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2" align="right"><b>{{__('web.Grand Total')}}:</b></td>
                            <td>{{$member->grand_total}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2" align="right"><b>{{__('web.Total Paid')}}:</b></td>
                            <td>{{$member->paid_amount}}</td>
                        </tr>
                         <tr>
                            <td colspan="4"></td>
                            <td colspan="2" align="right"><b>{{__('web.Balance')}}:</b></td>
                            <td>{{$member->remaining_amount}}</td>
                        </tr>
                    </tfoot>
                  </table>
                </div>


              </div>


            </div>




            




        </div>
    </section>
  	

</div>

<script type="text/javascript">
   function PrintMemberDetails(id)
   {    
        $.ajax({
            type: "GET",
            cache: false,
            url: "{{ config('app.url')}}register/print-member-details/"+id,
            beforeSend: function(){
            },
            success: function(data){
                    newWin= window.open("Print","_blank");
                    newWin.document.write(data);
                    newWin.print();
                    newWin.close();
                    },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });
   }
</script>

@endsection
 