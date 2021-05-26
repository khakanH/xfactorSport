@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content">
      <div class="error-page">
        <h2 class="headline text-danger">404</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> Page Not Found.</h3>

          <p>
            Requested page is not found on our server.
            Meanwhile, you may <a href="{{route('dashboard')}}">return to dashboard</a>
          </p>

        </div>
      </div>
      <!-- /.error-page -->

    </section>  	

</div>


@endsection
