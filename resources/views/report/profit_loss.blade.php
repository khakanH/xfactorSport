@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Profit & Loss')}}</h1>
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



              <form method="post" action="{{route('reports.filter-profit-loss')}}">
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

                <div class="col-sm-2">
                  <label for="">&nbsp;</label><br>
                  <button type="submit" class="btn btn-success">{{__('web.Filter')}}</button>
                </div>

              </div>
              </form>
              <br>




           



            <div class="card">


             <div class="card-body">
                <table class="table table-bordered table-hover" id="profit_table">

                    <thead>
                        <tr>
                        <td colspan="3">
                            <p align="center">
                                <img src="{{config('app.img_url')}}{{$gs_info['gs_system_logo']}}" height="100" width="200" alt="Logo"/></p>
                            <h2 align="center">Profit & Loss Statement</h2>
                            <p align="center">X-Factor Sports Academy</p>
                        </td>
                       
                    </tr>
                    <tr>
                        <th width="50%">Income</th>
                        <th width="20%">Amount</th>
                        <th width="30%">Information</th>
                    </tr>
                    </thead>
                    <tbody>
                    

                    <tr>
                        <td>Registrations</td>
                        <td>{{$registrations}}</td>
                        <td><a onclick="GetDetails('1','0')" href="javascript:void(0)">Details</a></td>
                    </tr>

                    <tr>
                        <td>Sales</td>
                        <td>{{$sales}}</td>
                        <td><a onclick="GetDetails('2','0')" href="javascript:void(0)">Details</a></td>
                    </tr>
                    <tr>
                        <td>Unions Subcsription</td>
                        <td>{{$unions}}</td>
                         <td><a onclick="GetDetails('3','0')" href="javascript:void(0)">Details</a></td>
                    </tr>
                    <tr>
                        <td>Championship</td>
                        <td>{{$championships}}</td>
                         <td><a onclick="GetDetails('4','0')" href="javascript:void(0)">Details</a></td>
                    </tr>
                    <tr>
                        <td>Belt Exam</td>
                        <td>{{$belt_exam}}</td>
                         <td><a onclick="GetDetails('9','0')" href="javascript:void(0)">Details</a></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; font-size: 18px;"><center> Total Income </center></td>
                        <td colspan="">
                            <center><h1 style="color:green !important;">{{$total_income}}</h1></center>
                        </td>
                        <td></td>
                    </tr>


                    <tr>
                        <td><b>Expenditure</b></td>
                        <td><b>Amount</b></td>
                        <td><b>Information</b></td>
                    </tr>
                    <tr>
                        <td>Purchases</td>
                        <td>{{$purchases}}</td>
                        <td><a onclick="GetDetails('5','0')" href="javascript:void(0)">Details</a></td>
                    </tr>
                    <tr>
                        <td>Salary</td>
                        <td>{{$salary}}</td>
                        <td><a onclick="GetDetails('6','0')" href="javascript:void(0)">Details</a></td>
                    </tr>
                    <tr>
                        <td>Leave</td>
                        <td>{{$leave}}</td>
                        <td><a onclick="GetDetails('7','0')" href="javascript:void(0)">Details</a></td>
                    </tr>

                    @foreach($expense_type as $et)
                    <tr>
                        <td>{{$et['name']}}</td>
                        <td>{{$et['total_expense']}}</td>
                        <td><a onclick='GetDetails("8","{{$et['id']}}")' href="javascript:void(0)">Details</a></td>
                    </tr>
                    @endforeach


                    <tr>
                    <td style="vertical-align: middle; font-size: 18px;text-align: center;">Total Expenses</td>
                    <td colspan=""><center><h1 style="color:red !important;">{{$total_expense}}</h1></center></td>
                    <td></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; font-size: 18px;text-align: center;">Profit/Loss</td>
                        <td colspan=""><center><h1>{{$profit_loss}}</h1></center></td>
                        <td></td>
                    </tr>


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


$('#profit_table').DataTable({
                                    paging: false,
                                    "ordering": false,
                                    "info":     false,
                                    "pageLength": 20,
                                    dom: 'Bfrtip',
                                    "ordering": false,
                                    buttons: [['copy', 'csv', 'excel', 'pdf'],
                                        {
                                            extend: 'print',
                                            title: '<br>',
                                            customize: function (win) {
                                                $(win.document.body)
                                                    .css('font-size', '10pt')
                                                    .prepend(
                                                        '<img src="{{config('app.img_url')}}{{$gs_info['gs_system_logo']}}" style="position:absolute; top:0; right:0;height:70px;" /> <h1>{{$gs_info["gs_printout_head_letter"]}}</h1>'
                                                    );

                                                $(win.document.body).find('table')
                                                    .addClass('compact')
                                                    .css('font-size', 'inherit');
                                            }
                                        }
                                    ],
                                    "lengthMenu": [
                                        [10, 25, 50, -1],
                                        [10, 25, 50, "All"]
                                    ],

                                });




function GetDetails(type,exp_id)
    {   
        var from_date   = "<?php echo $from_date ?>";
        var to_date     = "<?php echo $to_date ?>";
        var coach       = "<?php echo $selected_coach ?>";
        var sport       = "<?php echo $selected_sport ?>";

        var form = $('<form></form>');

        form.attr("method", "post");
        if (type == 1) 
        {
            form.attr("action", "{{ config('app.url')}}reports/filter-members-registration");
        }
        else if(type == 2)
        {
            form.attr("action", "{{ config('app.url')}}reports/filter-sales");
        }
        else if (type == 3)
        {
            form.attr("action", "{{ config('app.url')}}reports/filter-union-registration");   
        }
        else if (type == 4)
        {
            form.attr("action", "{{ config('app.url')}}reports/filter-championship-registration");
        }
        else if (type == 5) 
        {
            form.attr("method", "get");
            form.attr("action", "{{ config('app.url')}}purchase/view-purchase-items");
            
        }
        else if (type == 6) 
        {
            form.attr("action", "{{ config('app.url')}}employee/filter-salary");
        }
        else if (type == 7) 
        {
            form.attr("action", "{{ config('app.url')}}leave/filter-leave");
        }
        else if (type == 8) 
        {   
            form.attr("action", "{{ config('app.url')}}expenses/filter-expenses");
            var expense_type = $('<input></input>');

            expense_type.attr("type", "hidden");
            expense_type.attr("name", "type");
            expense_type.attr("value", exp_id);

             var beneficiary = $('<input></input>');

            beneficiary.attr("type", "hidden");
            beneficiary.attr("name", "beneficiary");
            beneficiary.attr("value", "");
            
            form.append(expense_type);
            form.append(beneficiary);

        }
        else if (type == 9) 
        {
            form.attr("action", "{{ config('app.url')}}register/filter-belt-exam");
        }
        else
        {
            return;
        }
        var token = $('<input></input>');


        token.attr("type", "hidden");
        token.attr("name", "_token");
        token.attr("value", "{{csrf_token()}}");

        var from_date_input = $('<input></input>');

        from_date_input.attr("type", "hidden");
        from_date_input.attr("name", "from_date");
        from_date_input.attr("value", from_date);

        var to_date_input = $('<input></input>');

        to_date_input.attr("type", "hidden");
        to_date_input.attr("name", "to_date");
        to_date_input.attr("value", to_date);
       

        var coach_input = $('<input></input>');

        coach_input.attr("type", "hidden");
        coach_input.attr("name", "coach");
        coach_input.attr("value", coach);

        var sport_input = $('<input></input>');

        sport_input.attr("type", "hidden");
        sport_input.attr("name", "sport");
        sport_input.attr("value", sport);

        form.append(token);
        form.append(from_date_input);
        form.append(to_date_input);
        form.append(coach_input);
        form.append(sport_input);

    
        $(document.body).append(form);
        form.submit();

    }
</script>

@endsection
