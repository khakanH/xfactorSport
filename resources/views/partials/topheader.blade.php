 <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="height: 87px; z-index: 999 !important;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- SEARCH FORM -->
   <!--  <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

       <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-globe tx-18"></i>
          <span class="badge badge-success navbar-badge tx-12" style="right: 0px; top: 3px;">{{empty(session('locale'))?"en":session('locale')}}</span>

        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-width: auto !important; min-width: auto !important;">
          
          <span class="dropdown-item dropdown-header">{{__('web.Language')}}</span>
         
         
          <div class="dropdown-divider"></div>
          <a href="{{route('lang',['ar'])}}" class="dropdown-item" style="">
           Arabic
          </a>
          <div class="dropdown-divider"></div>

          <div class="dropdown-divider"></div>
          <a href="{{route('lang',['en'])}}" class="dropdown-item" style="">
            English
          </a>
          <div class="dropdown-divider"></div>
         
         <div class="dropdown-divider"></div>
          <a href="{{route('lang',['tr'])}}" class="dropdown-item" style="">
            Turkish
          </a>
          <div class="dropdown-divider"></div>

          <div class="dropdown-divider"></div>
          <a href="{{route('lang',['ur'])}}" class="dropdown-item" style="">
            Urdu
          </a>
          <div class="dropdown-divider"></div>

          <div class="dropdown-divider"></div>
          <a href="{{route('lang',['hi'])}}" class="dropdown-item" style="">
            Hindi
          </a>
          <div class="dropdown-divider"></div>

          <div class="dropdown-divider"></div>
          <a href="{{route('lang',['ge'])}}" class="dropdown-item" style="">
            German
          </a>
          <div class="dropdown-divider"></div>



        </div>
      </li>
      <!-- Messages Dropdown Menu -->
      <?php
            use App\Models\Notifications;
            
            $notification = Notifications::where('is_deleted',0)->orderBy('created_at','desc')->get();

            $unread_notifi_count = Notifications::where('is_new',1)->count();
          
      ?>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>

          @if($unread_notifi_count != 0)        
          <span class="badge badge-warning navbar-badge tx-12">{{$unread_notifi_count}}</span>
          @endif
        
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-height: 85vh; overflow-y: auto; padding: 5px;">
          
          @if($unread_notifi_count == 0)
          <span class="dropdown-item dropdown-header">{{count($notification)}} Notifications</span>
          @else        
          <span class="dropdown-item dropdown-header">{{$unread_notifi_count}} Unread Notifications</span>
          @endif
          @foreach($notification as $key)
          <div class="dropdown-divider"></div>
          <a href="{{route($key['redirection'])}}" onclick='MarkNotificationRead("{{$key['id']}}")' class="dropdown-item" style="<?php if ($key['is_new'] == 1): ?>
             background: #033972; color: #fff; border-radius: 10px; margin: 8px 0px 8px 0px;
          <?php endif ?>">
            <p><i class="{{$key['icon']}} mr-2"></i><b>{{$key['title']}}</b></p>
            
            <p style="padding: 2px 0px 0px 25px;">{!! $key['description'] !!}</p>
            <span class="<?php if ($key['is_new'] != 1): ?>
                text-muted
            <?php endif ?> text-sm"><i class="far fa-clock mr-2"></i>{{$key->getTimeLapse($key['created_at'])}}</span>
          </a>
          <div class="dropdown-divider"></div>
          @endforeach
         

          <!-- <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
        </div>
      </li>
      


     

    </ul>
  </nav>


  <script type="text/javascript">
     function MarkNotificationRead(id)
     {
      $.ajax({
            type: "GET",
            cache: false,
            async: false,
            url: "{{ config('app.url')}}mark_notification_read/"+id,
            beforeSend: function(){
                           
                        },
            success: function(data) {
                       return true;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });
     }
  </script>