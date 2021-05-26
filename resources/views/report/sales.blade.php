@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.Sales')}}</h1>
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



              <form method="post" action="{{route('reports.filter-sales')}}">
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
                
                


                <div class="col-sm-3">
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
                      <th width="2%">{{__('web.Type')}}</th>
                      <th width="5%">{{__('web.Name')}}</th>
                      <th width="5%">{{__('web.Members')}}</th>
                      <th width="2%">{{__('web.Batch')}}</th>
                      <th width="2%">{{__('web.Volume')}}</th>
                      <th width="10%">{{__('web.Expiry Date')}}</th>
                      <th width="2%">{{__('web.Quantity')}}</th>
                      <th width="5%">{{__('web.Sale Price')}}</th>
                      <th width="5%">{{__('web.Purchase Price')}}</th>
                      <th width="5%">{{__('web.Total Amount')}}</th>
                      <th width="5%">{{__('web.Discount')}}</th>
                      <th width="10%">{{__('web.Added on')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                     <?php
                      $sale_price=0;
                      $purchase_price=0;
                      $total_amount =0 ;
                      $discount =0 ;
                    ?>
                  @foreach($sale as $key)  
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$key['type']}}</td>
                      <td>{{$key['item_id']}}</td>
                      <td>{{$key['member_id']}}</td>
                      <td>{{$key['batch']}}</td>
                      <td>{{$key['volume']}}</td>
                      <td>{{$key['expiry_date']}}</td>
                      <td>{{$key['qty']}}</td>
                      <td>{{$key['sale_price']}}</td>
                      <td>{{$key['purchase_price']}}</td>
                      <td>{{$key['total_amount']}}</td>
                      <td>{{$key['discount']}}</td>
                      <td>{{date('Y-m-d',strtotime($key['created_at']))}}</td>
                    </tr>
                    <?php
                      $sale_price += $key['sale_price'];
                      $purchase_price += $key['purchase_price'];
                      $total_amount += $key['total_amount'];
                      $discount += $key['discount'];
                    ?>
                  @endforeach
                  </tbody>
                   <tfoot>
                    <tr style="background: slategray; color: white; font-size: 18px;">
                      <td colspan="8" align="right"><b>{{__('web.Total')}}</b></td>
                      <td>{{$sale_price}}</td>
                      <td>{{$purchase_price}}</td>
                      <td colspan="2"><b>{{$total_amount}}-{{$discount}} = {{$total_amount-$discount}}</b></td>
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

 

</script>

@endsection
