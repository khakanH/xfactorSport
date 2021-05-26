<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New X-Factor</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>


   <div class="wrapper">
<div class="container">
    <div class="row">
        <p><br></p><p><br></p>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                <center></center>
                <br>
                <center>
                @if(session('success'))
                <h4 class="text-success pulse animated">{{ session('success') }}</h4>
                {{ session()->forget('success') }}
                @elseif(session('failed'))
                <h4 class="text-danger pulse animated">{{ session('failed') }}</h4>
                {{ session()->forget('failed') }}
                @endif
                </center>

                <br>

                    <form name="passwordForm" class="form-horizontal" role="form" method="POST" action="{{route('change-password')}}">
                      @csrf
                       <span class='arrow'>
                                <label class='error'></label>
                                </span>
                      <input type="hidden" name="user_id" id="user_id" value="{{$check_token->user_id}}">
                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Password:</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required value="{{ old('password') }}">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="col-md-4 control-label">Confirm Password:</label>

                            <div class="col-md-6">
                                <input id="confirm_password" type="password" class="form-control" name="confirm_password" required value="{{ old('confirm_password') }}">

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" id="save-btn" class="btn btn-primary">
                                    Save
                                </button>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

</body>
      <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>  
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

     
     <script type="text/javascript">
              $(function() {
        $("form[name='passwordForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "10px" ,"width":"100%"});
        label.insertBefore(element);
    },
    wrapper: 'span',

    rules: {
    

      
      confirm_password: {
        equalTo: "#password"


      },
     
    },
    messages: {
     
       confirm_password: {
        equalTo:"Password Mismatch",

      },
    },
    submitHandler: function(form) {

        document.getElementById("save-btn").disabled = true;
        form.submit();

    }
  });
  });
     </script>


</html>

