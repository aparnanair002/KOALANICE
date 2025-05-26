<?php
session_start();
include './dboperation.php';
$con = new dboperation();

// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}



?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koalanice</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.svg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
        <link rel="stylesheet" href="../assets/css/stylus.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<style>
    body {
        background-color: #DBBADD;
        font-family: 'Arial', sans-serif;
    }
    .chatbox {
        width: 100%;
        height: 680px;
        background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)),
            url('../assets/images/logos/Koalanice.svg');
        border-radius: 12px;
        background-size: cover;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .my-message {
    text-align: right;
    background: #dcf8c6;
    margin: 5px;
    padding: 8px;
    border-radius: 10px;
}

.their-message {
    text-align: left;
    background: #f1f0f0;
    margin: 5px;
    padding: 8px;
    border-radius: 10px;
}


    .chat-messages {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
    }

    .message {
        margin-bottom: 10px;
        max-width: 75%;
        padding: 10px 14px;
        border-radius: 16px;
        position: relative;
        word-wrap: break-word;
    }

    .message.user {
        background-color: #007bff;
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 0;
    }

    .message.bot {
        background-color: #e4e6eb;
        color: #000;
        margin-right: auto;
        border-bottom-left-radius: 0;
    }

    .timestamp {
        font-size: 0.75em;
        color: gray;
        margin-top: 2px;
        text-align: right;
    }

    .chat-input {
        display: flex;
        border-top: 1px solid #ddd;
        padding: 12px;
    }

    .chat-input input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 20px;
        outline: none;
    }

    .chat-input button {
        margin-left: 8px;
        padding: 10px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .chat-input button:hover {
        background-color: #0056b3;
    }

    .chat-header{
        display: flex;
        justify-content: center;
        align-items: center;
        background-color:#BE92A2;

        padding: 12px;
        color: black;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 1.5rem;
    }
</style>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed">
        <?php
        include('./sidebar.php');
        ?>
        <div class="body-wrap" style="max-height: 100vh;"
">
            <!--  Header Start -->
            <!--  Header End -->
            <div class="container-fluid rounded">
                <!--  Row 1 -->
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="chatbox rounded mt-3 ">
                           
                            <div class="chat-header rounded" id="chatHeader">
                            <!-- select image, name, and status from user table where id = $_GET['id'] -->
                           
                            </div>
                            <div class="chat-messages"  style="overflow-y: auto;" id="chatMessages">
                            </div>
                            <div class="chat-input">
                                <input type="text" id="chatInput" placeholder="Type a message...">
<i class="bi bi-emoji-smile" id="emojiButton" style="font-size: 1.5rem; padding: 10px; cursor: pointer;"></i>
                                <i class="bi bi-paperclip" style="font-size: 1.5rem; padding: 10px;"></i>
                                <button onclick="sendMessage()">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        //change message status to read
        readchat();
        // Fetch chat messages
        fetchChatMessages();

        // Fetch user details
        fetchUserDetails();
    });
    function readchat() {
    $.ajax({
        type: "POST",
        url: "readchat.php",
        data: { id: <?php echo json_encode($_GET['id']); ?> }, // safely inject id
        dataType: 'json',
        success: function(response) {
            console.log('fetch success:', response);
            if (response.success) {
                $('#chTis').html(response.html);
                $('#chTis').scrollTop($('#chTis')[0].scrollHeight);
            } else {
                $('#chTis').html('<p>Failed to load messages: ' + response.message + '</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error('fetchChatMessages error:', status, error);
            console.log('Raw response:', xhr.responseText);
            $('#chTis').html('<p>Error loading messages.</p>');
        }
    });
}


    function fetchChatMessages() {
        $.ajax({
            url: 'fetch_private_messages.php',
            type: 'POST',
            data: { receiver_id: <?php echo $_GET['id']; ?> },
            dataType: 'json',
            success: function (response) {
                console.log('fetchChatMessages success:', response);
                if (response.success) {
                    $('#chatMessages').html(response.html);
                    $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                } else {
                    $('#chatMessages').html('<p>Failed to load messages.</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('fetchChatMessages error:', status, error);
                $('#chatMessages').html('<p>Error loading messages.</p>');
            }
        });
    }

    function fetchUserDetails() { $.ajax({
                        url: 'fetch_user_details.php',
                        method: 'GET',
                        dataType: 'json',
                        data: { user_id:<?php echo $_GET['id']; ?> },
                        success: function(response) {
                            if (response.success) {
                            // Insert the returned HTML inside a container div (e.g., #usersList)
                            $('#chatHeader').html(response.html);

                            } else {
                            $('#chatHeader').html('<p>No users found.</p>');

                            }
                        },
                        error: function() {
                            $('#chatHeader').html('<p>Failed to load users.</p>');
                        }
                        });
                    }
    $('#chatInput').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // prevents line break
            sendMessage();      // call your existing function
        }
    });

    function sendMessage() {
        var message = $('#chatInput').val();
        if (message.trim() === '') {
            return;
        }

        $.ajax({
            url: 'send_private_message.php',
            type: 'POST',
            data: {
                message: message,
                receiver_id: <?php echo $_GET['id']; ?>
            },
            success: function (response) {
                try {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        $('#chatInput').val(''); // Clear the input field
                        fetchChatMessages(); // Refresh chat messages
                    } else if (res.status === 'error') {
                        alert('Error: ' + res.message);                       


                    } else {
                        alert('Failed to send message: ' + res.message);
                    }
                } catch (e) {
                    alert('Unexpected response from server.');
                }
            }
        });
    }
   $(document).on('click', '#deleteConvo', function() {
        var userId = <?php echo $_GET['id']; ?>;
        $.ajax({
            url: 'delete_conversation.php',
            type: 'POST',
            data: { receiver_id: userId },
            success: function(response) {
               
                    alert('Conversation deleted successfully.');
                    window.location.reload();
                
            },
            error: function(xhr, status, error) {
                console.error('Error deleting conversation:', error);
            }
        });
    });
const button = document.getElementById('emojiButton');
const picker = new EmojiButton();

picker.on('emoji', emoji => {
    const input = document.querySelector('#chatInput');
    input.value += emoji;
    input.focus();
});

button.addEventListener('click', () => {
    picker.togglePicker(button);
});


</script>

    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.2/dist/index.min.js"></script>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>

</body>

</html>