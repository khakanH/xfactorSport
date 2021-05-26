 <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
   <a href="{{route('dashboard')}}" class="brand-link"><center>
      <img src="{{config('app.img_url')}}{{$gs_info['gs_system_logo']}}" style="width: 70%; height: 60px;" alt="X-factor Logo" ></center>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{config('app.img_url')}}{{Auth::user()->profile_image}}" class="img-circle elevation-2" alt="img" style="width: 40px; height: 40px;">
        </div>
        <div class="info">
          <a href="{{route('account.user-account')}}" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2" style="font-size: 14px;">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link <?php if (Request::is('dashboard')): ?>
              active
            <?php endif ?>">
               <img class="menubar-icon-img" src="<?php echo config('app.img_url')?>icon/dashboard.png">
              <p>
                {{__('web.Dashboard')}}
              </p>
            </a>
          </li>


            <?php
              $menus = (new App\Helpers\MenuHelper)->getMenu();
            ?>


          @if(Auth::user()->user_type == 0)
          <li class="nav-item has-treeview <?php if (Request::is('user/*')): ?>
            menu-open
          <?php endif ?>">
            <a href="javascript:void(0)" class="nav-link<?php if (Request::is('user/*')): ?>
            active
          <?php endif ?>">
              <img class="menubar-icon-img" src="<?php echo config('app.img_url').'icon/user.png' ?>">
              <p>
                {{__('web.Users')}}
                 <i class=" tx-14 right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('user.add-type')}}" class="nav-link <?php if (Request::is('user/add-type')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('web.Add User Type')}}</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{route('user.type-list')}}" class="nav-link <?php if (Request::is('user/type-list')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('web.User Type List')}}</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{route('user.user-roles')}}" class="nav-link <?php if (Request::is('user/user-roles')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('web.User Roles')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('user.add-user')}}" class="nav-link <?php if (Request::is('user/add-user')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('web.Add New User')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('user.user-list')}}" class="nav-link <?php if (Request::is('user/user-list')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('web.Users List')}}</p>
                </a>
              </li>
            </ul>
          </li>
          @endif





































 {{--


  <div class="dropdown-divider"></div>


  @if(Auth::user()->user_type == 0)
          <li class="nav-item has-treeview <?php if (Request::is('cafeteria/*')): ?>
            menu-open
          <?php endif ?>">
            <a href="javascript:void(0)" class="nav-link<?php if (Request::is('cafeteria/*')): ?>
            active
          <?php endif ?>">
              <i class=" tx-14 nav-icon fas fa-circle"></i>
              <p>
                {{__('Cafeteria')}}
                 <i class=" tx-14 right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item">
                <a href="{{route('cafeteria.add-cafeteria')}}" class="nav-link <?php if (Request::is('cafeteria/add-cafeteria')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('POS')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('cafeteria.sales-view')}}" class="nav-link <?php if (Request::is('cafeteria/user-list')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('All Sales')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link <?php if (Request::is('cafeteria/user-list')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('All Purchase')}}</p>
                </a>
              </li>
            </ul>
          </li>
          @endif


          @if(Auth::user()->user_type == 0)
          <li class="nav-item has-treeview <?php if (Request::is('supplier/*')): ?>
            menu-open
          <?php endif ?>">
            <a href="javascript:void(0)" class="nav-link<?php if (Request::is('supplier/*')): ?>
            active
          <?php endif ?>">
              <i class=" tx-14 nav-icon fas fa-circle"></i>
              <p>
                {{__('Supplier')}}
                 <i class=" tx-14 right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item">
                <a href="{{route('supplier.add-supplier')}}" class="nav-link <?php if (Request::is('supplier/add-supplier')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('Add New')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('supplier.supplier-list')}}" class="nav-link <?php if (Request::is('supplier/supplier-list')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-plus nav-icon"></i>
                  <p>{{__('All Supplier')}}</p>
                </a>
              </li>
            </ul>
          </li>
          @endif

       


          @if(Auth::user()->user_type == 0)
          <li class="nav-item has-treeview <?php if (Request::is('store/*')): ?>
            menu-open
          <?php endif ?>">
            <a href="javascript:void(0)" class="nav-link<?php if (Request::is('store/*')): ?>
            active
          <?php endif ?>">
              <i class=" tx-14 nav-icon fas fa-circle"></i>
              <p>
                {{__('Store')}}
                 <i class=" tx-14 right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item">
                <a href="{{route('store.sales-add')}}" class="nav-link <?php if (Request::is('store/sales-add')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('POS')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('store.sales-view')}}" class="nav-link <?php if (Request::is('store/sales-view')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('POS View')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('store.add-items')}}" class="nav-link <?php if (Request::is('store/add-items')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-add nav-icon"></i>
                  <p>{{__('Add Items')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('store.view-items')}}" class="nav-link <?php if (Request::is('store/view-items')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('View All Purchase')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('store.view-all-stock')}}" class="nav-link <?php if (Request::is('store/view-stock')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('View All Stock')}}</p>
                </a>
              </li>
            </ul>
          </li>
          @endif



          @if(Auth::user()->user_type == 0)
          <li class="nav-item has-treeview <?php if (Request::is('purchase/*')): ?>
            menu-open
          <?php endif ?>">
            <a href="javascript:void(0)" class="nav-link<?php if (Request::is('purchase/*')): ?>
            active
          <?php endif ?>">
              <i class=" tx-14 nav-icon fas fa-circle"></i>
              <p>
                {{__('Purchasing')}}
                 <i class=" tx-14 right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
              <li class="nav-item">
                <a href="{{route('purchase.add-items')}}" class="nav-link <?php if (Request::is('purchase/add-items')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('Add New Item')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('purchase.view-purchase-items')}}" class="nav-link <?php if (Request::is('purchase/view-purchase-items')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('View All Purchase Items')}}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('purchase.add-new-stock')}}" class="nav-link <?php if (Request::is('purchase/add-stock')): ?>
                  active
                <?php endif ?>">
                  <i class=" tx-14 far fa-circle nav-icon"></i>
                  <p>{{__('Add New Stock')}}</p>
                </a>
              </li>
            </ul>
          </li>
          @endif

--}}














         


          <div class="dropdown-divider"></div>
          <li class="nav-item">
            <a href="{{route('logout')}}" class="nav-link">
               <img class="menubar-icon-img" src="<?php echo config('app.img_url')?>icon/logout.png">
              <p>
                {{__('web.Logout')}}
              </p>
            </a>
          </li>
          <br><br>
      
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>