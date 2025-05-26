<?php
$userDatai = null;
$user_id = $_SESSION['userid'];
$query = $con->con->prepare("SELECT profile_pic FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
if ($result->num_rows === 1) {
  $userDatai = $result->fetch_assoc();
}
$query->close();

// Determine profile image to show
$profileImage = '../assets/images/profile/user-1.jpg'; // default
if (!empty($_SESSION['profile_pic'])) {
  $profileImage = $_SESSION['profile_pic'];
} elseif (!empty($userDatai['profile_pic'])) {
  $profileImage = $userDatai['profile_pic'];
}
?>
<style>
  /* Modal background */
  .modal {
    display: none;
    position: fixed;
    z-index: 999;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
  }

  .modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    position: relative;
  }

  .close {
    color: #aaa;
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .rounded-circle {
    border-radius: 50%;
    object-fit: cover;
  }

  input,
  textarea {
    width: 100%;
    margin-bottom: 10px;
    padding: 8px;
  }

  .button-radar {
    width: 100%;
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .button-radar:hover {
    background-color: #0056b3;
  }

  .text-center {
    text-align: center;
  }

  @keyframes shake {
    0% {
      transform: translateY(0);
    }

    20% {
      transform: translateY(-5px);
    }

    40% {
      transform: translateY(5px);
    }

    60% {
      transform: translateY(-5px);
    }

    80% {
      transform: translateY(5px);
    }

    100% {
      transform: translateY(0);
    }
  }

  .vibrate-alert {
    animation: shake 0.5s;
  }
</style>
<header class="app-header mb-3">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>

      <li class="nav-item">

      </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <button type="button" class="btn btn-primary m-1" onclick="showPopup()">
        <a class="nav-link nav-icon-hover" href="javascript:void(0)">
          New Group
          <i class="ti ti-plus"></i>
        </a>
      </button>


      <button type="button" class="btn btn-outline-primary m-1">

        <a class="nav-link nav-icon-hover" href="./friends.php">
          <span id="friend-notification" class="badge bg-danger rounded-circle" style="display: none; margin-right:3px;"></span>

          Friends
          <i class="ti ti-heart"></i>

        </a>
      </button>

      <button type="button" class="btn btn-outline-primary m-1">

        <a class="nav-link nav-icon-hover" href="./groups.php">
          <span id="group-notification" class="badge bg-danger rounded-circle" style="display: none; margin-right:3px;"></span>

          Groups
          <i class="ti ti-users"></i>
        </a>
      </button>
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover mt-2" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
            aria-expanded="false">
            <img src=<?php echo htmlspecialchars($profileImage); ?> alt="" width="60" height="60" class="rounded-circle">
          </a>

          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
            <div class="message-body">
              <a href="./profiledit.php" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-user fs-6"></i>
                <p class="mb-0 fs-3">My Profile</p>
              </a>
                <a href="./blockedusers.php" class="d-flex align-items-center gap-2 p-2 mt-2 dropdown-item badge bg-danger">
                <i class="ti ti-users fs-6"></i>

                <p class="mb-0 fs-3">Blocked users</p>
              </a> 
              <a href="./logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>

            
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
<div id="popupForm" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closePopup()">&times;</span>
    <h3 class="text-center">Enter Details</h3>

    <form id="profileForm">
      <div class="text-center mb-4">
        <img id="previewImage" src="../assets//images/profile/kind.jpg" alt="Profile Preview" width="150" height="150" class="rounded-circle" />
      </div>

      <input type="text" class="form-control" name="name" placeholder="Name" required>
      <textarea name="description" class="form-control" placeholder="Description" rows="3" required></textarea>
      <input type="file" class="form-control" name="profile_image" id="profileInput" accept="image/*">

      <button class="button-radar add-group" type="submit">Submit</button>
    </form>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  //pop ups
  function showPopup() {
    document.getElementById("popupForm").style.display = "block";
  }

  function closePopup() {
    document.getElementById("popupForm").style.display = "none";
  }

  document.getElementById('profileInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
      const imageURL = URL.createObjectURL(file);
      document.getElementById('previewImage').src = imageURL;
    }
  });

  //fetching notifications 
  // Function to fetch group notifications
  function fetchGroupNotifications() {
    $.ajax({
      url: 'grouprequests.php',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        if (data.count > 0) {
          $('#group-notification').text(data.count).show();
          $('#group-notification').addClass('vibrate-alert');

        } else {
          $('#group-notification').hide();
        }
      },
      error: function() {
        console.error('Failed to load group notifications');
      }
    });
  }
  //function to fetch friend notifications or requests
  function fetchFriendNotifications() {
    $.ajax({
      url: 'friendrequests.php',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        if (data.count > 0) {
          $('#friend-notification').text(data.count).show();
          $('#friend-notification').addClass('vibrate-alert');
        } else {
          $('#friend-notification').hide();
        }
      },
      error: function() {
        console.error('Failed to load friend notifications');
      }
    });
  }


  //ajax for submission 
  $(document).on('submit', '#profileForm', function(e) {
    e.preventDefault();

    const form = $('#profileForm')[0];
    const formData = new FormData(form);

    $.ajax({
      url: 'add_group.php', // NEW target
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        $('#popupForm').hide();
        form.reset();
        $('#previewImage').attr('src', '../assets/images/profile/kind.jpg');
        closePopup();
        window.location.reload();
      },
      error: function(xhr, status, error) {
        alert('An error occurred: ' + error);
      }
    });
  });
  // Call it on page load
  $(document).ready(function() {
    fetchGroupNotifications();
    fetchFriendNotifications();

    // Optionally refresh every 60 seconds
    setInterval(fetchGroupNotifications, 60000);
  });
</script>