<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>New X-Factor | Admin</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    

    
  

    <meta name="description" content="https://www.facebook.com/Osama.younas97/">
    <meta name="author" content="Osama Younas">
    @include('partials/js')  
    @include('partials/css')  
       <!-- All css files link -->
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
              @include('partials/topheader') 
              @include('partials/leftmenu') 
                    @yield('content')
                
        </div>

            @include('partials/footer') 
            @include('partials/modals') 

    </body>


  


    <script type="text/javascript">
          $('input[type="file"]').change(function(e){
        var files = e.target.files;
        
        var fileName = files.length+" {{__('web.File(s) Selected')}}";


        $('.custom-file-label').html(fileName);
    });


                 


    </script>
</html>