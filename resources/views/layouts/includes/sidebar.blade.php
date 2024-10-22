<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo justify-content-center">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <div class="brand-logo">
        <img
          src="{{url('Images').'/'.$siteSetting['logo']}}"
          width="200px"
          height="200px"
          class="logostyle"
          style="width: 177px; height: 50px; object-fit: cover"
        />
      </div>
    </a>

    <a
      href="javascript:void(0);"
      class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none"
    >
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- logout -->
    <div class="row">
      <div class="col-md-12">
        @can('dashboard_labs_test_list')
        <a
          href="{{url('lab/'.Auth::user()->id.'/edit')}}"
          class="btn btn-icon btn-secondary button1"
          id="buttoncss"
        >
          <i class="bx bxs-user"></i>
        </a>
        @endcan @can('dashboard_support_list')
        <a
          href="{{ route('site-settings.create') }}"
          class="btn btn-icon btn-secondary button1"
          id="buttoncss"
        >
          <i class="bx bxs-cog"></i>
        </a>
        @endcan
        <a
          href="{{ route('logout') }}"
          onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
          class="btn btn-icon btn-secondary button2"
          id="buttoncsss"
        >
          <span class="bx bx-power-off"></span>
        </a>
        <form
          id="logout-form"
          action="{{ route('logout') }}"
          method="POST"
          class="d-none"
        >
          @csrf
        </form>
      </div>
    </div>
    <li class="menu-item {{ request()->segment(1)=='dashboard'?'active': '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>
    @can('dashboard_support_list')
    <li
      class="menu-item {{request()->segment(1)=='lab' ||request()->segment('1')=='lab-category'?'open active':'' }}"
    >
      <a href="javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon bx bx-user"></i>
        <div data-i18n="User interface">Lab Details</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{request()->segment('1')=='lab'?'active':''}}">
          <a class="menu-link" href="{{ route('lab.index') }}">
            <!-- <i class='menu-icon tf-icons bx bx-clinic'></i> -->
            <div data-i18n="Category">Lab List</div>
          </a>
        </li>

        {{--
        <li
          class="menu-item {{request()->segment('1')=='lab-category'?'active':''}}"
        >
          <a class="menu-link" href="{{ route('lab-category.index') }}">
            <!-- <i class='menu-icon tf-icons bx bx-clinic'></i> -->
            <div data-i18n="Category">Lab Category</div>
          </a>
        </li>
        --}}
      </ul>
      @endcan
    </li>

    <li
      class="menu-item {{request()->segment(1)=='lab-test' ||request()->segment('1')=='diagno-tests'?'open active':'' }}"
      style=""
    >
      <a href=" javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon bx bx-test-tube"></i>
        <div data-i18n="User interface">Tests</div>
      </a>
      <ul class="menu-sub">
        <li
          class="menu-item {{request()->segment(1)=='lab-test' && app('request')->input('url') != 'diagno-tests' ?'active':''}}"
        >
          <a class="menu-link" href="{{ route('lab-test.index') }}">
            <div data-i18n="LabTest">Labs Tests</div>
          </a>
        </li>
        <li
          class="menu-item {{request()->segment(1)=='diagno-tests' || app('request')->input('url') == 'diagno-tests' ?'active':''}}"
        >
          <a class="menu-link" href="{{ url('diagno-tests') }}">
            <div data-i18n="LabTest">Diagno Tests</div>
          </a>
        </li>
      </ul>
    </li>

    <li
      class="menu-item {{request()->segment(1)=='radiology' ||request()->segment('1')=='diagno-radiology'?'open active':'' }}"
      style=""
    >
      <a href=" javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon bx bx-test-tube"></i>
        <div data-i18n="User interface">Radiology</div>
      </a>
      <ul class="menu-sub">
        <li
          class="menu-item {{request()->segment(1)=='radiology' && app('request')->input('url') != 'diagno-radiology' ?'active':''}}"
        >
          <a class="menu-link" href="{{ route('radiology.index') }}">
            <div data-i18n="LabTest">Radiology</div>
          </a>
        </li>
        <li
          class="menu-item {{request()->segment(1)=='diagno-radiology' || app('request')->input('url') == 'diagno-radiology' ?'active':''}}"
        >
          <a class="menu-link" href="{{ url('diagno-radiology') }}">
            <div data-i18n="LabTest">Diagno Radiology</div>
          </a>
        </li>
      </ul>
    </li>
    @can('dashboard_support_list')
    <li
      class="menu-item {{request()->segment(1)=='PatientManagement' ||request()->segment('1')=='patient'||request()->segment('1')=='patient-report'?'open active':'' }}"
    >
      <a href="javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-plus-medical"></i>
        <div data-i18n="User interface">Patient Management</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{request()->segment(1)=='patient'?'active':''}}">
          <a href="{{ route('patient.index') }}" class="menu-link">
            <div data-i18n="Accordion">Patients</div>
          </a>
        </li>
        <li
          class="menu-item {{request()->segment(1)=='patient-report'?'active':''}}"
        >
          <a href="{{ route('patient-report.index') }}" class="menu-link">
            <div data-i18n="Accordion">Patient Report</div>
          </a>
        </li>
      </ul>
      @endcan
    </li>

    <li
      class="menu-item {{request()->segment(1)=='profile' ||request()->segment('1')=='diagno-profiles'?'open active':'' }}"
      style=""
    >
      <a href=" javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon tf-iconsbx bx bx-vial"></i>
        <div data-i18n="User interface">Profile</div>
      </a>
      <ul class="menu-sub">
        <li
          class="menu-item {{request()->segment(1)=='profile' && app('request')->input('url') != 'diagno-profiles' ?'active':''}}"
        >
          <a class="menu-link" href="{{ route('profile.index') }}">
            <div data-i18n="LabTest">Lab Profiles</div>
          </a>
        </li>
        <li
          class="menu-item {{request()->segment(1)=='diagno-profiles' || app('request')->input('url') == 'diagno-profiles' ? 'active':''}}"
        >
          <a class="menu-link" href="{{ url('diagno-profiles') }}">
            <div data-i18n="LabTest">Diagno Profiles</div>
          </a>
        </li>
      </ul>
    </li>
    <!-- ///////////// -->
    <li
      class="menu-item {{request()->segment(1)=='package' ||request()->segment('1')=='diagno-package'?'open active':'' }}"
      style=""
    >
      <a href=" javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon tf-iconsbx bx bx-vial"></i>
        <div data-i18n="User interface">Package</div>
      </a>
      <ul class="menu-sub">
        <li
          class="menu-item {{request()->segment(1)=='package' && app('request')->input('url') != 'diagno-package' ?'active':''}}"
        >
          <a class="menu-link" href="{{ route('package.index') }}">
            <div data-i18n="LabTest">Lab Packages</div>
          </a>
        </li>
        <li
          class="menu-item {{request()->segment(1)=='diagno-package' || app('request')->input('url') == 'diagno-package' ? 'active':''}}"
        >
          <a class="menu-link" href="{{ url('diagno-package') }}">
            <div data-i18n="LabTest">Diagno Packages</div>
          </a>
        </li>
      </ul>
    </li>
    @if(Auth::user()->roles->contains('1') || Auth::user()->roles->contains('4'))
<li class="menu-item {{request()->segment('1')=='test-request.create' ||request()->segment('1')=='test-request'?'open active':'' }}">
  <a href="javascript:void(0)" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-hive"></i>
    <div data-i18n="User interface">Test Request</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{request()->segment('1')=='test-request'?'active':''}}">
      <a class="menu-link" href="{{ route('test-request.index') }}">
        <div data-i18n="Test Request">View Test Requests</div>
      </a>
    </li>
    <li class="menu-item {{request()->segment('1')=='test-request.create'?'active':''}}">
      <a class="menu-link" href="{{ route('test-request.create') }}">
        <div data-i18n="Test Request">Create Test Request</div>
      </a>
    </li>
  </ul>
</li>
@endif
    <!-- ////////////// -->
    <!-- <li class="menu-item {{request()->segment('1')=='package'?'active':''}}">
      <a class="menu-link" href="{{ route('package.index') }}">
        <i class="menu-icon tf-iconsbx bx bx-package"></i>
        <div data-i18n="Test">Package</div>
      </a>
    </li> -->
    <li
      class="menu-item {{request()->segment('1')=='appointments'?'active':''}}"
    >
      <a class="menu-link" href="{{ route('appointments.index') }}">
        <i class="menu-icon tf-icons bx bx-calendar"></i>
        <div data-i18n="Test">Bookings</div>
      </a>
    </li>
    @if(Auth::user()->roles->contains(1)) <!-- Admin Role -->

    <li class="menu-item {{ request()->is('admin/blogposts*') ? 'active' : '' }}">
    <a class="menu-link" href="{{ route('admin.blogposts.index') }}">
        <i class="menu-icon tf-icons bx bx-calendar"></i>
        <div data-i18n="Test">Blogs</div>
    </a>
</li>
@endif
@if(Auth::user()->roles->contains(1)) 

    <li
      class="menu-item {{request()->segment(1)=='ratingreviews'?'active':''}}"
    >
      <a class="menu-link" href="{{ route('ratingreviews.index') }}">
        <i class="menu-icon tf-icons bx bxs-user-detail"></i>
        <div data-i18n="Test">Reviews</div>
      </a>
    </li>
    @endif
    @can('dashboard_support_list')
    <li class="menu-item {{ request()->segment('1')=='city'?'active': '' }}">
      <a href="{{ route('pincodes.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-buildings"></i>
        <div data-i18n="Dashboard">Pincode</div>
      </a>
    </li>
 

    <li
      class="menu-item {{request()->segment('1')=='static-pages'?'active':''}}"
    >
      <a class="menu-link" href="{{ route('static-pages.index') }}">
        <i class="menu-icon tf-icons bx bx-layout"></i>
        <div data-i18n="Test">Pages</div>
      </a>
    </li>
    <li class="menu-item {{request()->segment('1')=='faqs'?'active':''}}">
      <a class="menu-link" href="{{ route('faqs.index') }}">
        <i class="menu-icon tf-icons bx bx-error"></i>
        <div data-i18n="Test">Faqs</div>
      </a>
    </li>
    <li class="menu-item {{request()->segment('1')=='supports'?'active':''}}">
      <a class="menu-link" href="{{ route('supports.index') }}">
        <i class="menu-icon tf-icons bx bx-support"></i>
        <div data-i18n="Test">Support</div>
      </a>
    </li>

   
    <li class="menu-item {{request()->segment('1')=='offers'?'active':''}}">
      <a class="menu-link" href="{{ route('offer.index') }}">
        <i class="menu-icon tf-icons bx bxs-discount"></i>
        <div data-i18n="Test">Offer</div>
      </a>
    </li>

    <li class="menu-item {{request()->segment('1')=='wallet'?'active':''}}">
      <a class="menu-link" href="{{ route('wallet.index') }}">
        <i class="menu-icon tf-icons bx bxs-wallet-alt"></i>
        <div data-i18n="Test">Wallet</div>
      </a>
    </li>

    <!-- <li class="menu-item {{request()->segment('1')=='promo'?'active':''}}">
      <a class="menu-link" href="{{ route('promo.index') }}">
        <i class="menu-icon tf-icons bx bxs-discount"></i>
        <div data-i18n="Test">Promo Code</div>
      </a>
    </li> -->

    @endcan
 
    
    @can('dashboard_support_list')
    <!-- <li
      class="menu-item {{request()->segment('1')=='test-request'?'active':''}}"
    >
      <a class="menu-link" href="{{ route('test-request.index') }}">
        <i class="menu-icon tf-icons bx bx-hive"></i>
        <div data-i18n="Test-request">Test Request</div>
      </a>
    </li> -->
    @endcan @can('dashboard_support_list')
    <li
      class="menu-item {{request()->segment('1')=='testimonials'?'active':''}}"
    >
      <a class="menu-link" href="{{ route('testimonials.index') }}">
        <i class="menu-icon bx bx-comment-dots"></i>
        <div data-i18n="Test">Testimonials</div>
      </a>
    </li>
    <li
      class="menu-item {{request()->segment('1')=='doctor-recommended'?'active':''}}"
    >
      <a class="menu-link" href="{{ route('doctor-recommended.index') }}">
        <i class="menu-icon bx bx-plus-medical"></i>
        <div data-i18n="Test">Doctor Recommended</div>
      </a>
    </li>
    <li
      class="menu-item {{request()->segment(1)=='mail'||request()->segment('1')=='site-settings'||request()->segment(1)=='sliders'?'open active':''}}"
    >
      <a href=" javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div data-i18n="User interface">Settings</div>
      </a>
      <ul class="menu-sub">
        <li
          class="menu-item {{request()->segment('1')=='mails-template'?'active':''}}"
        >
          <a class="menu-link" href="{{ route('mails-template.index') }}">
            <i class="menu-icon tf-icons bx bx-box"></i>
            <div data-i18n="Test">Mails Template</div>
          </a>
        </li>
        <li class="menu-item {{request()->segment(1)=='sliders'?'active':''}}">
          <a class="menu-link" href="{{ route('sliders.index') }}">
            <i class="menu-icon tf-icons bx bx-slider-alt"></i>
            <div data-i18n="Test">Sliders</div>
          </a>
        </li>
        @if(Auth::user()->roles->contains(4))
        <li
          class="menu-item {{request()->segment('1')=='hospital-profile'?'active':''}}"
        >
          <a class="menu-link" href="{{ url('hospital-profile') }}">
            <i class="menu-icon tf-icons bx bx-vial"></i>
            <div data-i18n="Test">Profile</div>
          </a>
        </li>
        @else
        <li
          class="menu-item {{request()->segment('1')=='site-settings'?'active':''}}"
        >
          <a class="menu-link" href="{{ route('site-settings.index') }}">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <div data-i18n="Test">Site Settings</div>
          </a>
        </li>
        @endif
      </ul>
    </li>
    @endcan
  </ul>
</aside>

<style>
  .button1 {
    background-color: #007ac2 !important;
    margin-left: 80px;
    margin-bottom: 10px;
    margin-top: 10px;
  }

  .button2 {
    background-color: #007ac2 !important;
    margin-left: 20px;
    margin-bottom: 10px;
    margin-top: 10px;
  }
</style>
