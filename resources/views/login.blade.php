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
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                <center><img src="{{config('app.img_url')}}{{$gs_info['gs_system_logo']}}" style="width: 65%; height:150px;"></center>
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

                    <form class="form-horizontal" role="form" method="POST" action="{{route('login')}}">
                      @csrf
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email Address:</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus onfocus="return document.getElementById('email').style.border='solid #66afe9 1px';"  onfocusout="return document.getElementById('email').style.border='solid lightgray 1px';">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Password:</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required value="{{ old('password') }}">

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="javascript:void(0)" onclick="ForgotPassword()">
                                    Forgot Your Password?
                                </a>
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
      <script type="text/javascript">
          function ForgotPassword()
          {
            var email = document.getElementById('email');
            if (!email.value.trim()) 
            {
                email.style.border = "solid red 1px";
                return;
            }

                location.href = "{{ config('app.url')}}forgot-password/"+email.value.trim();



          }
      </script>


</html>

