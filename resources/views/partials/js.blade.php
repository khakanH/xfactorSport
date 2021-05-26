<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
<script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{asset('js/adminlte.js')}}"></script>
<script src="{{asset('js/pages/dashboard.js')}}"></script>
<script src="{{asset('js/demo.js')}}"></script>

<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>


<script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>





  <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
     $('input[type="date"]').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        maxDate: new Date('2060/12/31'),
        yearRange: '1950:2060'
    });

   $('input[type="date"]').attr({  
    "max" : '2060-12-31',  
    });  
});


    
  $.widget.bridge('uibutton', $.ui.button);











    $(document).ready(function() {
    // Setup - add a text input to each footer cell
    // $('.card').appendTo( '.card' );
    // $('.card thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="date" class="m-10" placeholder="Date" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.search() !== this.value ) {
                table
                    
                    // .search( this.value )
                    .draw();
            }
        } );
    // } );
    $.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#min').val() );
        var max = parseInt( $('#max').val() );
        var age = parseFloat( data[3] ) || 0; // use data for the age column
 
        if (  isNaN( min )  ||
              min <= age )
        {
            return true;
        }
        return false;
    }
);
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var created_date = parseInt( $('#created_date').val() );
        var newdata = parseFloat( data[11] ) || 0; // use data for the age column
 
        if (  isNaN( created_date )  ||
        created_date <= newdata )
        {
            return true;
        }
        return false;
    }
);
 
    var table = $('.CommonDataTables').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        "pageLength": 10,
        dom: 'Bfrtip',
                    buttons: [['copy', 'csv', 'excel'],
                                        {
                                          extend: 'pdfHtml5',
                                          orientation: 'landscape',
                                          pageSize: 'LEGAL',
                                        },
                                        {
                                            extend: 'print',
                                            title: '<br>',

                                            customize: function (win) {
                                                $(win.document.body)
                                                    .css('font-size', '10pt')
                                                    .prepend(
                                                        '<img src="{{config('app.img_url')}}{{$gs_info['gs_system_logo']}}" style="position:absolute; top:0; right:0;height:70px;bottom:20;" /> <h1>{{$gs_info["gs_printout_head_letter"]}}</h1>'
                                                    );

                                                $(win.document.body).find('table')
                                                    .addClass('compact')
                                                    .css('font-size', 'inherit');
                                            }
                                        },
                                        
                                    ],
        
    } );
} );















          


            $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {


            if (jqxhr.status == 401) 
            {
                alert("{{__('web.Session expired. You will be take to the login page')}}");
                location.href = "{{ config('app.url')}}"; 
            }
            else if(jqxhr.status == 403)
            {
                alert("{{__('web.Sorry, You are not allowed to visit requested page.')}}");
                return false;
            }
            else
            {
                alert("{{__('web.Something went wrong. Try again later')}}");
                return false;
            }
    

    });

















  /******total qty*price ******/
  $(document).ready(function(){
  /******total qty*price ******/
    $("#Qty,#Price").keyup(function () {
      $('#total').val($('#Qty').val() * $('#Price').val());

    });
  /*************rate list according to product*************/
//    $("#items_new").click(function(){
//    var id = $(this).val();
//       var token = $("input[name='_token']").val();
//      $.post("{{ url('/store/sales-ajax') }}", {_token:"{{csrf_token()}}", id}, function(result){
//        console.log(result)
//        $('#Price').val(result[0].sale_price);
//        $('#Current_Stock').val(result[0].qty);
//        $('#Volume').val(result[0].size);
//        $('#Batch').val(result[0].batch);
//        $('#Purchase_Price').val(result[0].purchase_price);
//       });
//   });



    $("#discount").change(function(){
       $('#total').val((Number($('#total').val())-Number($('#discount').val())));
      });
 

    $("#btnSendEmail").click(function(e) {
      e.preventDefault();
// alert('asd');
      var memberValue = $("#member").val();
      if(memberValue == "")
      {
      alert('Please Select Member');
      }else{
      var form = $("#formCafe");

      form.prop("action", $(this).data("url"));
      form.submit();
      }
    });

//    $("#btnSave").click(function(e) {
//      e.preventDefault();
// // alert('asd');
//      var memberValue = $("#member").val();
//      if(memberValue == "")
//      {
//      alert('Please Select Member');
//      }else{
//      var form = $("#formCafe");

//      form.prop("action", $(this).data("url"));
//      form.submit();
//      }
//    });



  });

  // function checkMember()
  // {
  //  var Member = $("#member").val();
  //  if(Member == "")
  //  {
  //    alert('Please Select Member');
  //  }else{
  //    alert(' in else');
  //    $("#hiddenSendMailBtn")[0].click();
  //  }
  // }
</script>