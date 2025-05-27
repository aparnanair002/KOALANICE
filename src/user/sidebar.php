  <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="../assets/images/logos/Koalanice_mlogo.svg" width="220" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
          
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.php" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Home<span>
              </a>
            </li>
            <li class="sidebar-item row">
  <!-- Friends Messages -->
  <div class="col-md-6">
    <a class="sidebar-link" href="./friends.php" aria-expanded="false">
      <span>
        <i class="ti ti-user"></i>
      </span>
      <span class="hide-menu">Friends</span>
    </a>
  </div>

  <!-- Group Messages -->
  <div class="col-md-6">
    <a class="sidebar-link" href="./groups.php" aria-expanded="false">
      <span>
        <i class="ti ti-users"></i>
      </span>
      <span class="hide-menu">Groups</span>
    </a>
  </div>
</li>

            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Chats</span>
            </li>

                <div id="chatList"></div>
                
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Groups</span>
            </li>

                <div id="grouplist"></div>
            
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">EXTRA</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./icon-tabler.html" aria-expanded="false">
                <span>
                  <i class="ti ti-mood-happy"></i>
                </span>
                <span class="hide-menu">Icons</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./sample-page.html" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu">Sample Page</span>
              </a>
            </li>
          </ul>
          
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!-- jquery script tag -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      
<script>
    $(document).ready(function () {
      
        // Fetch chat messages
        fetchChats();
        // Fetch group messages
        fetchgroupchats();
    });

    function fetchChats() {
        $.ajax({
            url: 'fetch_chat.php',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#chatList').html(response.html);
                } else {
                    $('#chatList').html('<p>Failed to load messages.</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('fetchChatMessages error:', status, error);
                $('#chatList').html('<p>Error loading messages.</p>');
            }
        });
      }

       function fetchgroupchats() {
        $.ajax({
            url: 'fetch_lastgrpchat.php',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#grouplist').html(response.html);
                } else {
                    $('#grouplist').html('<p>Failed to load messages.</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('fetchgroupchats error:', status, error);
                $('#grouplist').html('<p>Error loading messages.</p>');
            }
        });
      }

      //function to leave group when exit icon is clicked

      

</script>