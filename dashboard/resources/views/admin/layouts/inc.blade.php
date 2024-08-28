@php
	$lang = session()->get('admin_lang');
	app()->setLocale($lang);
@endphp

<!--begin::Menu Container-->
<div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">

     <!--begin::Menu Nav-->
     <ul class="menu-nav">

          <li class="menu-item   @if(count(Request::segments()) == 1)  menu-item-active @endif" aria-haspopup="true">
               <a href="{{ url('admin_panel') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fas fa-tachometer-alt"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.dashboard') }}</span>
               </a>
          </li>


          <li class="menu-item {{ request()->is('admin_panel/admin*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/admin') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fas fa-users"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.managers') }}</span>
               </a>
          </li>

          <li class="menu-item {{ request()->is('admin_panel/setting*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/setting') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fas fa-cogs"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.setting') }}</span>
               </a>
          </li>

          {{-- <li class="menu-item {{ request()->is('admin_panel/policy*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
            <a href="{{ url('admin_panel/policy') }}" class="menu-link">
                <span class="svg-icon menu-icon">
                    <i class="m-menu__link-icon fas fa-cogs"></i>
                </span>
                <span class="menu-text">{{ trans('home.policy') }}</span>
            </a>
        </li> --}}

          <li class="menu-item {{ request()->is('admin_panel/cities*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/cities') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fas fa-location-arrow"></i>
                    </span>
                    <span class="menu-text"> {{ trans('home.cities') }}</span>
               </a>
          </li>

          <li class="menu-item {{ request()->is('admin_panel/coupon*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
            <a href="{{ url('admin_panel/coupon') }}" class="menu-link">
                <span class="svg-icon menu-icon">
                    <i class="m-menu__link-icon fas fa-tags"></i>
                </span>
                <span class="menu-text">{{ trans('home.coupon') }}</span>
            </a>
          </li>

          <li class="menu-item {{ request()->is('admin_panel/invoices*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/invoices') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fas fa-file-invoice-dollar"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.invoices') }}</span>
               </a>
          </li>

          <li class="menu-item {{ request()->is('admin_panel/users*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/users') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fas fa-user"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.users') }}</span>
               </a>
          </li>

          <li class="menu-item {{ request()->is('admin_panel/site_pages*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/site_pages') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fa fa-file-text"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.site_pages') }}</span>
               </a>
          </li>

          <li class="menu-item {{ request()->is('admin_panel/product_categories*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/product_categories') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon flaticon-line-graph"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.product_categories') }}</span>
               </a>
          </li>
          <li class="menu-item {{ request()->is('admin_panel/menu_categories*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/menu_categories') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fa fa-bars"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.menu_categories') }}</span>
               </a>
          </li>

          <li class="menu-item {{ request()->is('admin_panel/products*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/products') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fab fa-product-hunt"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.products') }}</span>
               </a>
          </li>

          <li class="menu-item {{ request()->is('admin_panel/menu') ? 'menu-item-active' : '' }} " aria-haspopup="true">
               <a href="{{ url('admin_panel/menu') }}" class="menu-link">
                    <span class="svg-icon menu-icon">
                         <i class="m-menu__link-icon fab fa fa-coffee"></i>
                    </span>
                    <span class="menu-text">{{ trans('home.menu') }}</span>
               </a>
          </li>

          {{-- <li class="menu-item {{ request()->is('admin_panel/slider*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
            <a href="{{ url('admin_panel/slider') }}" class="menu-link">
                 <span class="svg-icon menu-icon">
                      <i class="m-menu__link-icon fas fa-sliders-h"></i>
                 </span>
                 <span class="menu-text"> {{ trans('home.slider') }}</span>
            </a>
          </li> --}}

          {{-- <li class="menu-item {{ request()->is('admin_panel/faq*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
            <a href="{{ url('admin_panel/faq') }}" class="menu-link">
                 <span class="svg-icon menu-icon">
                      <i class="m-menu__link-icon fas fa-question"></i>
                 </span>
                 <span class="menu-text">{{ trans('home.faq') }}</span>
            </a>
          </li> --}}


          <li class="menu-item {{ request()->is('admin_panel/reviews*') ? 'menu-item-active' : '' }} " aria-haspopup="true">
            <a href="{{ url('admin_panel/reviews') }}" class="menu-link">
                <span class="svg-icon menu-icon">
                    <i class="m-menu__link-icon fas fa-sms"></i>
                </span>
                <span class="menu-text">{{ trans('home.reviews') }}</span>
            </a>
          </li>


     </ul>
     <!--end::Menu Nav-->
</div>
<!--end::Menu Container-->


