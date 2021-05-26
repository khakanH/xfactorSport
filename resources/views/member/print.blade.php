<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Factor | New Member | Print </title>
</head>
<style>
    #invoice{
    padding: 30px;
}

.invoice {
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px
}

.invoice header {
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #3989c6
}

.invoice .company-details {
    text-align: right
}

.invoice .company-details .name {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .contacts {
    margin-bottom: 20px
}

.invoice .invoice-to {
    text-align: left
}

.invoice .invoice-to .to {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .invoice-details {
    text-align: right
}

.invoice .invoice-details .invoice-id {
    margin-top: 0;
    color: #3989c6
}

.invoice main {
    padding-bottom: 50px
}

.thanks {
    font-size: 2em;
    margin-bottom: 50px
}

.notices {
    padding-left: 6px;
    width: 30%;
        float: left;
}

.notices .notice {
    font-size: 1.2em
}

.invoice table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px
}

.invoice table td,.invoice table th {
    padding: 15px;
    border-bottom: 1px solid #fff
}


.invoice table h3 {
    margin: 0;
    font-weight: 400;
    color: #3989c6;
    font-size: 1.2em
}

.invoice table .qty,.invoice table .total,.invoice table .unit {
    text-align: right;
    font-size: 1.2em
}

.invoice table .no {
    color: #fff;
    font-size: 1.6em;
    background: #3989c6
}

.invoice table .unit {
    background: #ddd
}

.invoice table .total {
    background: #3989c6;
    color: #fff
}

.invoice table tbody tr:last-child td {
    border: none
}

.invoice table tfoot {
    background: 0 0;
    border-bottom: none;
    white-space: nowrap;
    text-align: right;
    padding: 10px 20px;
    font-size: 1.2em;
}

.invoice table tfoot tr:first-child td {
    border-top: none
}

.invoice table tfoot tr:last-child td {
    color: #3989c6;
    font-size: 1.4em;
    border-top: 1px solid #3989c6
}

.invoice table tfoot tr td:first-child {
    border: none
}

footer {

    position:fixed;
    bottom:0;
    width: 100%;
    text-align: center;
    color: #777;
    padding-bottom:50px;
}
.date{
    margin-top:5px;
    font-weight: 550;
}
@media print {
    html,body,.container,#invoice{
        margin:0 !important;
        padding:0 !important;
    }

    .invoice {
        font-size: 11px!important;
        overflow: hidden!important
    }

    

    .invoice>div:last-child {
        
    }
}

/*custom style*/
table{
    max-width:100% !important;
}
td,th{
    padding:5px !important;
}

</style>
<body>
<div class="container">
<div id="invoice">
    <div class="invoice overflow-auto">
        <div>
            <header>
                <div class="row">
                    <div class="col-6">
                        <a  href="{{route('dashboard')}}">
                            <img src="{{config('app.img_url')}}{{$gs_info['gs_system_logo']}}" width="200" data-holder-rendered="true" />
                            </a>
                        <div class="company-details" style="float: right;">
                            <div>Muftah Plaza First Floor, <br> Al Rayan Road, Bin Mahmoud, <br> Doha, Qatar.</div>
                            <div>+9743128 2831</div>
                        </div>
                    </div>
                </div>
            </header>
            <main>
                <div  class="row contacts">
                    <div class="col invoice-to" style="float: left;">
                        <div class="text-gray-light">INVOICE TO:</div>
                        <h3 class="to">Name: {{$member->name}}</h3>
                       
                    </div> 
                    <div class="col invoice-details">
                        <h3 class="invoice-id">INVOICE: {{$member->id}}</h3>
                       
                    </div>
                </div>
                
                <table style="transform: translateY(30px);"  border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr style="background: slategray; font-size: 20px; font-weight: 600;">
                            <th>#</th>
                            <th>Sport</th>
                            <th>Coach </th>
                            <th>Duration</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Sports Fee</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
                        @foreach($member_sport as $key)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$key->sport_name->name}}</td>
                            <td>{{$key->coach_name->name}}</td>
                            <td>{{$key['duration']}}</td>
                            <td>{{$key['start_date']}}</td>
                            <td>{{$key['expiry_date']}}</td>
                            <td>{{$key['total_fee']}}</td>
                        </tr>
                        <tr style="background: #F5F5F5; font-size: 18px; font-weight: 600;">
                            <th colspan="7" align="center">Classes</th>
                        </tr>
                            <tr style="background: #F5F5F5;">
                                <th></th>
                                <th>#</th>
                                <th>Day</th>
                                <th>Class From Time</th>
                                <th>Class To Time</th>
                                <th>Location</th>
                                <th>Fee</th>

                            </tr>
                            @foreach($key->classes_list as $classes)
                            <tr style="background: #F5F5F5;">
                                <td></td>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$classes['day']}}</td>
                                <td>{{date('h:i a',strtotime($classes['class_from_time']))}}</td>
                                <td>{{date('h:i a',strtotime($classes['class_to_time']))}}</td>
                                <td>{{$classes['location']}}</td>
                                <td>{{$classes['fee']}}</td>
                            </tr>
                            @endforeach
                            <tr><td colspan="7" style="border-bottom: solid black 1px;"></td></tr>
                            <tr><td colspan="7"></td></tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2">Total Amount:</td>
                            <td>{{$member->total_amount}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2">Discount:</td>
                            <td>{{$member->discount}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2">Grand Total:</td>
                            <td>{{$member->grand_total}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2">Total Paid</td>
                            <td>{{$member->paid_amount}}</td>
                        </tr>
                         <tr>
                            <td colspan="4"></td>
                            <td colspan="2">Balance</td>
                            <td>{{$member->remaining_amount}}</td>
                        </tr>
                    </tfoot>
                </table>
            </main>
            
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
       
        
    </div>
</div>
</div>


<script type="text/javascript">
    window.print();
</script>
 
 <footer>
                <div class="notices">
                    <hr>
                    <div class="notice">Received by:</div>
                </div>
                <div class="notices" style="float:right;">
                    <hr>
                    <div class="notice">Signature</div>
                </div>
        </footer>
        <div style="page-break-after: always;"></div>
</body>
</html>