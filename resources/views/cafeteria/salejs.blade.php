<script type="text/javascript">
	/******total qty*price ******/
	$(document).ready(function(){
	/******total qty*price ******/
		$("#Qty,#Price").keyup(function () {
		  $('#total').val($('#Qty').val() * $('#Price').val());

		});
		alert('coming herere');
  /*************rate list according to product*************/
	  $("#items").change(function(){
		  alert('fdfdf');
	  var id = $(this).val();
	  alert(id);
      var token = $("input[name='_token']").val();
     $.post("{{ url('/store/sales-ajax') }}", {_token:"{{csrf_token()}}", id}, function(result){
     	console.log(result)
       $('#Price').val(result[0].sale_price);
       $('#Current_Stock').val(result[0].total_price);
       $('#Volume').val(result[0].size);
       $('#Batch').val(result[0].batch);
       $('#Purchase_Price').val(result[0].purchse_price);
      });
  });



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

		$("#btnSave").click(function(e) {
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



	});

	// function checkMember()
	// {
	// 	var Member = $("#member").val();
	// 	if(Member == "")
	// 	{
	// 		alert('Please Select Member');
	// 	}else{
	// 		alert(' in else');
	// 		$("#hiddenSendMailBtn")[0].click();
	// 	}
	// }
</script>
