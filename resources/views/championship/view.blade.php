@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Championship Members')}}</h1>
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


              <br>


               <form method="post" action="{{route('register.filter-championship')}}">
              <div class="row">
              @csrf

                <div class="col-sm-3">
                  <label for="from_date">{{__('web.From Date')}}:</label>
                  <input type="date" name="from_date" id="from_date" class="form-control" value="{{$from_date}}">
                </div>
                <div class="col-sm-3">
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
                      <th width="2%">#</th>
                      <th width="8%">{{__('web.Championship Name')}}</th>
                      <th width="8%">{{__('web.Member Name')}}</th>
                      <th width="15%">{{__('web.Sports')}} | {{__('web.Coach')}}</th>
                      <th width="4%">{{__('web.Age')}}</th>
                      <th width="15%">{{__('web.Championship Date')}}</th>
                      <th width="15%">{{__('web.Total Amount')}}</th>
                      <th width="15%">{{__('web.Paid Amount')}}</th>
                      <th width="15%">{{__('web.Remaining Amount')}}</th>
                      <th width="10%">{{__('web.Added on')}}</th>
                      <th style="text-align: center;" width="20%">{{__('web.Action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                     <?php
                      $total_amount=0;
                      $total_paid=0;
                      $total_remaining =0 ;
                    ?>
                  @foreach($championship as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$key->championship_name}}</td>
                      <td><b><a href="{{route('register.details-member',[$key['member_id']])}}">{{isset($key->member_info->name)?$key->member_info->name:"-"}}</a></b></td>
                      <td>{{$key->sport_name->name}} | {{$key->coach_name->name}}</td>
                      <td>{{$key->member_info->age}}</td>
                      <td>{{$key['championship_date']}}</td>
                      <td>{{$key['total_amount']}}</td>
                      <td>{{$key['paid_amount']}}</td>
                      <td>{{$key['remaining_amount']}}</td>
                      <td>{{date('Y-m-d',strtotime($key['created_at']))}}</td>
                      <td style="text-align: center;"><a title="{{__('web.Details')}}" data-toggle="tooltip" onclick='ShowChampionshipDetails("{{$key['id']}}")' href="javascript:void(0)"><i class="fa fa-eye text-secondary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Edit')}}" data-toggle="tooltip" href="{{route('register.edit-championship',[$key['id']])}}"><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="{{__('web.Delete')}}" data-toggle="tooltip" onclick="return confirm('{{__("web.Are you sure?")}}')" href="{{route('register.delete-championship',[$key['id']])}}"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                    <?php
                      $total_amount += $key['total_amount'];
                      $total_paid += $key['paid_amount'];
                      $total_remaining += $key['remaining_amount'];
                    ?>
                  @endforeach
                  </tbody>
                  <tfoot>
                    <tr style="background: slategray; color: white; font-size: 18px;">
                      <td colspan="6" align="right"><b>{{__('web.Total')}}</b></td>
                      <td>{{$total_amount}}</td>
                      <td>{{$total_paid}}</td>
                      <td>{{$total_remaining}}</td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div>




            




        </div>
    </section>
  	

</div>

<script type="text/javascript">

  function ShowChampionshipDetails(id)
  {
        $('#MyModal').modal('show');
        $('#MyModalLabel').html('{{__("web.Championship Details")}}');

        $.ajax({
            type: "GET",
            cache: false,
            url: "{{ config('app.url')}}register/details-championship/"+id,
            beforeSend: function(){
                            $('#MyModalData').html('<center><i class="fas fa-2x fa-sync-alt fa-spin"></i></center>');
                        },
            success: function(data) {
                            $('#MyModalData').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });


        document.getElementById('MyModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('MyModalDialog').style.paddingTop="0px";
        document.getElementById('MyModalData').style.padding="20px 20px 0px 20px";
  }

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
