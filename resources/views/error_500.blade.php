@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content">
      <div class="error-page">
        <h2 class="headline text-danger">500</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>

          <p>
            We will work on fixing that right away.
            Meanwhile, you may <a href="{{route('dashboard')}}">return to dashboard</a>
          </p>

        </div>
      </div>
      <!-- /.error-page -->

    </section>  	

</div>


@endsection
