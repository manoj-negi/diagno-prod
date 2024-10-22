<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
            <li class="nav-item page-title">{{$title}}</li>
				<li class="nav-item active" aria-current="page">&nbsp;{{':: '.$page_name}}</li>
			 </div>
        </div>
        <!-- /Search -->

        
    </div>
</nav>
<div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalToggleLabel">Modal 1</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Show a second modal and hide this one with the button below.
      </div>
      <form action="" class="delete-form" method="post" style="display: inline-block;">
        <input type="hidden" name="_token" value="95yOX3duSUFJWOuEkcn7YV1bYbKqJlSyxhKbmr0J">      <input type="hidden" name="_method" value="delete"><button class="btn btn-danger mr-4" type="submit">Delete</button>
        </form>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

<!-- / Navbar -->