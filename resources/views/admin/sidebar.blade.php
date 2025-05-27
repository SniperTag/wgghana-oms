<nav id="sidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
      <!-- Side Header -->
      <div class="content-header justify-content-lg-center">
        <!-- Logo -->
        <div>
          <span class="smini-visible fw-bold tracking-wide fs-lg">
            c<span class="text-primary">b</span>
          </span>
          <a class="link-fx fw-bold tracking-wide mx-auto" href="{{ route('dashboard') }}">
            <span class="smini-hidden">
              <i class="fas fa-envlope text-primary"></i>
              <span class="fs-4 text-dual">WG</span><span class="fs-4 text-primary">GHAHA</span>
            </span>
          </a>
        </div>
        <!-- END Logo -->

        <!-- Options -->
        <div>
          <!-- Close Sidebar, Visible only on mobile screens -->
          <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
          <button type="button" class="btn btn-sm btn-alt-danger d-lg-none" data-toggle="layout" data-action="sidebar_close">
            <i class="fa fa-fw fa-times"></i>
          </button>
          <!-- END Close Sidebar -->
        </div>
        <!-- END Options -->
      </div>
      <!-- END Side Header -->

      <!-- Sidebar Scrolling -->
      <div class="js-sidebar-scroll">
        <!-- Side User -->
        <div class="content-side content-side-user px-0 py-0">
          <!-- Visible only in mini mode -->
          <div class="smini-visible-block animated fadeIn px-3">
            <img class="img-avatar img-avatar32" src="{{ asset('build/assets/media/avatars/avatar15.jpg') }}" alt="">
          </div>
          <!-- END Visible only in mini mode -->

          <!-- Visible only in normal mode -->
          <div class="smini-hidden text-center mx-auto">
            <a class="img-link" href="{{ route('profile.update') }}">
              <img class="img-avatar" src="{{ asset('build/assets/media/avatars/avatar15.jpg') }}" alt="">
            </a>
            <ul class="list-inline mt-3 mb-0">
              <li class="list-inline-item">
                <a class="link-fx text-dual fs-sm fw-semibold text-uppercase" href="{{ route('profile.update') }}">{{ auth()->user()->name }}</a>
              </li>
              <li class="list-inline-item">
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="link-fx text-dual" data-toggle="layout" data-action="dark_mode_toggle" href="javascript:void(0)">
                  <i class="fa fa-moon"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a  class="link-fx text-dual"href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fa fa-sign-out-alt"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              </li>
            </ul>
          </div>
          <!-- END Visible only in normal mode -->
        </div>
        <!-- END Side User -->

        <!-- Side Navigation -->
        <div class="content-side content-side-full">
          <ul class="nav-main">
            <li class="nav-main-item">
              <a class="nav-main-link" href="{{ url('admin/dashboard') }}">
                <i class="nav-main-link-icon fa fa-house-user"></i>
                <span class="nav-main-link-name">Dashboard</span>
              </a>
            </li>
            <li class="nav-main-item {{ request()->routeIs('roles.*') ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->routeIs('roles.*') ? 'true' : 'false' }}" href="#">
                  <i class="nav-main-link-icon fa fa-award"></i>
                  <span class="nav-main-link-name">User Management</span>
                </a>
                <ul class="nav-main-submenu">
                  <li class="nav-main-item {{ request()->routeIs('roles.*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->routeIs('roles.*') ? 'true' : 'false' }}" href="#">
                      <span class="nav-main-link-name">All Staffs</span>
                    </a>
                    <ul class="nav-main-submenu">
                      <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('roles.create') ? 'active' : '' }}" href="{{ route('admin.create') }}">
                          <span class="nav-main-link-name">Create Staff</span>
                        </a>
                      </li>
                      <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('invite.user') ? 'active' : '' }}" href="{{ route('invite.user') }}">
                          <span class="nav-main-link-name">Invite Staff</span>
                        </a>
                      </li>
                      <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('roles.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                          <span class="nav-main-link-name">View All Staffs</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
               <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon fa fa-eye"></i>
                    <span class="nav-main-link-name">Access Controller</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{ route('access.roles') }}">
                        <span class="nav-main-link-name">Access Roles</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{  route('access.management') }}">
                        <span class="nav-main-link-name">Access Management</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="#"></a>
                        <span class="nav-main-link-name">User Access</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="db_corporate.html">
                        <span class="nav-main-link-name">Corporate</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="db_minimal.html">
                        <span class="nav-main-link-name">Minimal</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="db_pop.html">
                        <span class="nav-main-link-name">Pop</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="db_medical.html">
                        <span class="nav-main-link-name">Medical</span>
                      </a>
                    </li>
                  </ul>
                </li>

            <li class="nav-main-heading">Attendance & Time </li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-grip-vertical"></i>
                <span class="nav-main-link-name">Attendance Records</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link" href="{{ route('allstaff.attendance') }}">
                    <span class="nav-main-link-name">View All Attendance</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="{{ route('self.attendance') }}">
                    <span class="nav-main-link-name">My Attendance</span>
                  </a>
                </li>

              </ul>
            </li>

            <li class="nav-main-heading">STAFFS MANAGEMENT</li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-vector-square"></i>
                <span class="nav-main-link-name">Assessment</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <span class="nav-main-link-name"> PROJECTS</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_default.html">
                        <span class="nav-main-link-name">Create Projct</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_flipped.html">
                        <span class="nav-main-link-name">Assign Staffs</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_native_scrolling.html">
                        <span class="nav-main-link-name">View Projects</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <span class="nav-main-link-name">TASKS SECTION</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <span class="nav-main-link-name">Static</span>
                      </a>
                      <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_modern.html">
                            <span class="nav-main-link-name">Create Task</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_classic.html">
                            <span class="nav-main-link-name">Assign Task</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_classic_dark.html">
                            <span class="nav-main-link-name">View Tasks</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_glass.html">
                            <span class="nav-main-link-name">Glass</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_glass_dark.html">
                            <span class="nav-main-link-name">Glass Dark</span>
                          </a>
                        </li>
                      </ul>
                    </li>
                    {{--  <li class="nav-main-item">
                      <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <span class="nav-main-link-name">Fixed</span>
                      </a>
                      <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_fixed_modern.html">
                            <span class="nav-main-link-name">Modern</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_fixed_classic.html">
                            <span class="nav-main-link-name">Classic</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_fixed_classic_dark.html">
                            <span class="nav-main-link-name">Classic Dark</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_fixed_glass.html">
                            <span class="nav-main-link-name">Glass</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="be_layout_header_fixed_glass_dark.html">
                            <span class="nav-main-link-name">Glass Dark</span>
                          </a>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <span class="nav-main-link-name">Sidebar</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_sidebar_dark.html">
                        <span class="nav-main-link-name">Dark</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_sidebar_hidden.html">
                        <span class="nav-main-link-name">Hidden</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_sidebar_mini.html">
                        <span class="nav-main-link-name">Mini</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <span class="nav-main-link-name">Side Overlay</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_side_overlay_visible.html">
                        <span class="nav-main-link-name">Visible</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_side_overlay_hoverable.html">
                        <span class="nav-main-link-name">Hoverable</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_side_overlay_no_page_overlay.html">
                        <span class="nav-main-link-name">No Page Overlay</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <span class="nav-main-link-name">Main Content</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_content_boxed.html">
                        <span class="nav-main-link-name">Boxed</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_content_narrow.html">
                        <span class="nav-main-link-name">Narrow</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_content_full_width.html">
                        <span class="nav-main-link-name">Full Width</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <span class="nav-main-link-name">Hero</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_hero_color.html">
                        <span class="nav-main-link-name">Color</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_hero_bubbles.html">
                        <span class="nav-main-link-name">Bubbles</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_hero_image.html">
                        <span class="nav-main-link-name">Image</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="be_layout_hero_video.html">
                        <span class="nav-main-link-name">Video</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_layout_api.html">
                    <span class="nav-main-link-name">API</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-cogs"></i>
                <span class="nav-main-link-name">Payolls</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_charts.html">
                    <span class="nav-main-link-name">Create payrolls</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_onboarding.html">
                    <span class="nav-main-link-name">View Staff payrolls</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_loaders.html">
                    <span class="nav-main-link-name">Allowance</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_dialogs.html">
                    <span class="nav-main-link-name">Deductions</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_notifications.html">
                    <span class="nav-main-link-name">SSNIT & TAX RATES</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_nestable.html">
                    <span class="nav-main-link-name">Nestable</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_gallery.html">
                    <span class="nav-main-link-name">Gallery</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_sliders.html">
                    <span class="nav-main-link-name">Sliders</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_carousel.html">
                    <span class="nav-main-link-name">Carousel</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_offcanvas.html">
                    <span class="nav-main-link-name">Offcanvas</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_rating.html">
                    <span class="nav-main-link-name">Rating</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_appear.html">
                    <span class="nav-main-link-name">Appear</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_calendar.html">
                    <span class="nav-main-link-name">Calendar</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_image_cropper.html">
                    <span class="nav-main-link-name">Image Cropper</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_maps_vector.html">
                    <span class="nav-main-link-name">Vector Maps</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_comp_syntax_highlighting.html">
                    <span class="nav-main-link-name">Syntax Highlighting</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-puzzle-piece"></i>
                <span class="nav-main-link-name">Main Menu</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link" href="#">
                    <span class="nav-main-link-name">Link 1-1</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="#">
                    <span class="nav-main-link-name">Link 1-2</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <span class="nav-main-link-name">Sub Level 2</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="#">
                        <span class="nav-main-link-name">Link 2-1</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="#">
                        <span class="nav-main-link-name">Link 2-2</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <span class="nav-main-link-name">Sub Level 3</span>
                      </a>
                      <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="#">
                            <span class="nav-main-link-name">Link 3-1</span>
                          </a>
                        </li>
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="#">
                            <span class="nav-main-link-name">Link 3-2</span>
                          </a>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="nav-main-heading">LEAVE MANAGEMENT</li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-globe-americas"></i>
                <span class="nav-main-link-name">Leaves</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_blank.html">
                    <span class="nav-main-link-name">Request Leave</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_blank_block.html">
                    <span class="nav-main-link-name">View Leaves Request</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_blank_breadcrumb.html">
                    <span class="nav-main-link-name"></span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_search.html">
                    <span class="nav-main-link-name">Search</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_profile.html">
                    <span class="nav-main-link-name">Profile</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_profile_edit.html">
                    <span class="nav-main-link-name">Profile Edit</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_inbox.html">
                    <span class="nav-main-link-name">Inbox</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_invoice.html">
                    <span class="nav-main-link-name">Invoice</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_faq.html">
                    <span class="nav-main-link-name">FAQ</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_blog.html">
                    <span class="nav-main-link-name">Blog</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_story.html">
                    <span class="nav-main-link-name">Story</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_project_list.html">
                    <span class="nav-main-link-name">Project List</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_project.html">
                    <span class="nav-main-link-name">Project</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_pricing_plans.html">
                    <span class="nav-main-link-name">Pricing Plans</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_upgrade_plan.html">
                    <span class="nav-main-link-name">Upgrade Plan</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_team.html">
                    <span class="nav-main-link-name">Team</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_contact.html">
                    <span class="nav-main-link-name">Contact</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_generic_todo.html">
                    <span class="nav-main-link-name">Todo</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_sidebar_mini_nav.html">
                    <span class="nav-main-link-name">Sidebar with Mini Nav</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_coming_soon.html">
                    <span class="nav-main-link-name">Coming Soon</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_maintenance.html">
                    <span class="nav-main-link-name">Maintenance</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_status.html">
                    <span class="nav-main-link-name">Status</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_installation.html">
                    <span class="nav-main-link-name">Installation</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_checkout.html">
                    <span class="nav-main-link-name">Checkout</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-user-lock"></i>
                <span class="nav-main-link-name">Authentication</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_auth_all.html">
                    <span class="nav-main-link-name">All</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_signin.html">
                    <span class="nav-main-link-name">Sign In</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_signin2.html">
                    <span class="nav-main-link-name">Sign In 2</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_signin3.html">
                    <span class="nav-main-link-name">Sign In 3</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_signup.html">
                    <span class="nav-main-link-name">Sign Up</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_signup2.html">
                    <span class="nav-main-link-name">Sign Up 2</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_signup3.html">
                    <span class="nav-main-link-name">Sign Up 3</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_lock.html">
                    <span class="nav-main-link-name">Lock Screen</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_lock2.html">
                    <span class="nav-main-link-name">Lock Screen 2</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_lock3.html">
                    <span class="nav-main-link-name">Lock Screen 3</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_reminder.html">
                    <span class="nav-main-link-name">Pass Reminder</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_reminder2.html">
                    <span class="nav-main-link-name">Pass Reminder 2</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_reminder3.html">
                    <span class="nav-main-link-name">Pass Reminder 3</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_two_factor.html">
                    <span class="nav-main-link-name">Two Factor</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_two_factor2.html">
                    <span class="nav-main-link-name">Two Factor 2</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_auth_two_factor3.html">
                    <span class="nav-main-link-name">Two Factor 3</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-flag"></i>
                <span class="nav-main-link-name">Error</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link" href="be_pages_error_all.html">
                    <span class="nav-main-link-name">All</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_error_400.html">
                    <span class="nav-main-link-name">400</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_error_401.html">
                    <span class="nav-main-link-name">401</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_error_403.html">
                    <span class="nav-main-link-name">403</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_error_404.html">
                    <span class="nav-main-link-name">404</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_error_500.html">
                    <span class="nav-main-link-name">500</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="op_error_503.html">
                    <span class="nav-main-link-name">503</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-coffee"></i>
                <span class="nav-main-link-name">Get Started</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item">
                  <a class="nav-main-link" href="gs_backend.html">
                    <span class="nav-main-link-name">Backend</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="gs_backend_boxed.html">
                    <span class="nav-main-link-name">Backend Boxed</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="gs_landing.html">
                    <span class="nav-main-link-name">Landing</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="gs_rtl_backend.html">
                    <span class="nav-main-link-name">RTL Backend</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="gs_rtl_backend_boxed.html">
                    <span class="nav-main-link-name">RTL Backend Boxed</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="gs_rtl_landing.html">
                    <span class="nav-main-link-name">RTL Landing</span>
                  </a>
                </li>
              </ul>
            </li>  --}}
          </ul>
        </div>
        <!-- END Side Navigation -->
      </div>
      <!-- END Sidebar Scrolling -->
    </div>
    <!-- Sidebar Content -->
  </nav>
  <!-- END Sideba
