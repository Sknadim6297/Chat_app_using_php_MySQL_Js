<?php
include 'config.php';

$outgoing_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user_form WHERE NOT user_id = {$outgoing_id} ORDER BY user_id DESC";
$query = mysqli_query($conn, $sql);

if (!$query) {
    die("Error fetching users: " . mysqli_error($conn));
}

$output = "";

while ($row = mysqli_fetch_assoc($query)) {
    $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['user_id']}
             OR outgoing_msg_id = {$row['user_id']}) AND (outgoing_msg_id = {$outgoing_id}
             OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";

    $query2 = mysqli_query($conn, $sql2);
    if (!$query2) {
        die("Error fetching messages: " . mysqli_error($conn));
    }

    $row2 = mysqli_fetch_assoc($query2);

    // Check if there are any messages
    if (mysqli_num_rows($query2) > 0) {
        $result = $row2['msg'];
    } else {
        $result = "No message available";
    }

    // Shorten message if necessary
    $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '....' : $result;

    // Check if the message is an image
    if (mysqli_num_rows($query2) != 0 && $row2['msg'] == '' && $row2['msg_img'] != '') {
        $msg = 'image';
    }

    // Set the "You:" label for outgoing messages
    $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : '';

    // Set user status (offline/online)
    $offline = ($row['status'] == "Offline Now") ? "offline" : "";

    // Hide the logged-in user's own profile
    $hid_me = ($outgoing_id == $row['user_id']) ? "hide" : "";

    // Build the output HTML for each user
    $output .= '<a href="chat.php?user_id=' . $row['user_id'] . '">
        <div class="content">
            <img src="uploaded_img/' . $row['img'] . '" alt="">
            <div class="details">
                <span>' . $row['username'] . '</span>
                <p>' . $you . $msg . '</p>
            </div>
        </div>
        <div class="status-dot ' . $offline . '"></div>
    </a>';
}

echo $output;
?>
