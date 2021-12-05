<?php
    session_start();
    if(!$_SESSION['username']){
        echo '<script type="text/javascript">
           window.location = "../Login/login_form.php"
        </script>';
        die();
    }
    $username = $_SESSION['username'];
    $conn = mysqli_connect('localhost', 'root', '', 'school_management');

    if(!$conn){
        die("Error in connection:". mysqli_error($conn));
    }

    $query = "SELECT * FROM school_management.Users WHERE ID='$username'";
    $results = mysqli_query($conn, $query);

    if(mysqli_num_rows($results) == 1){
        $row = mysqli_fetch_array($results);
        echo json_encode(array('ID' => $username, 'Name' => $row['Name'], 'LibPerm' => $row['LibPerm'], 'HostelPerm' =>  $row['HostelPerm'], 'OnlineTutorialsPerm' => $row['OnlineTutorialsPerm']));
    }
    else{
        echo mysqli_error($conn);
    }
    mysqli_close($conn);
?>