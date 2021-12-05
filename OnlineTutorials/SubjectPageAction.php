<?php
    session_start();
    $subjectID = $_SESSION['subjectID'];
    $conn = mysqli_connect('localhost', 'root', '', 'school_management');

    if(!$conn){
        die("Error in connection:". mysqli_error($conn));
    }
    //Retrieves posts of a subject
    if($_POST['action'] == 'pageContent'){
        $username = $_SESSION['username'];
        $query = "SELECT OnlineTutorialsPerm FROM Users WHERE ID='$username'";
        $results = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($results);
        $perm = $row['OnlineTutorialsPerm'];

        $content = '';
        $query = "SELECT PostID, PostTitle, PostDesc, PostEmbed, UNIX_TIMESTAMP(current_Timestamp()) - UNIX_TIMESTAMP(PostUploadTime) AS Time FROM $subjectID ORDER BY Time ASC";
        if(!$results = mysqli_query($conn, $query)){
            $content = 'Could not retrieve data';
        }
        else{
            if (mysqli_num_rows($results) > 0) {
                while ($row = mysqli_fetch_array($results)) {
                    $content .= '<div class="card">
                    <div class="card-header"><br><h6>';
                    if($perm == "RE" || $perm == "T"){
                        $content .= '<input class="form-check-input" type="radio" name="post" value="'. $row['PostID'].'"> ';
                    }
                
                    $content .= $row['PostTitle'] .'</h6><h7 class="d-flex flex-row-reverse">';
                    if($row['Time']/2592000 > 1){
                        $content .= round($row['Time']/2592000) .' months ago';
                    }
                    else if($row['Time']/86400 > 1){
                        $content .= round($row['Time']/86400) .' days ago';
                    }
                    else if($row['Time']/3600> 1){
                        $content .= round($row['Time']/3600) .' hours ago';
                    }
                    else{
                        $content .= round($row['Time']/60) .' minutes ago';
                    }
                    $content .= '</h7></div><div class="card-body">'. $row['PostDesc'];
                    if($row['PostEmbed'] != '' || $row['PostEmbed'] != NULL){
                        $content .= '<br><br><div class="embed-responsive embed-responsive-16by9 text-center">
                        <iframe width="520" height="415" class="embed-responsive-item" src="'. $row['PostEmbed'] .'"></iframe>
                        </div>';
                    }
                    $content .= "</div></div><br>";
                }
            } 
            else {
                $content = '<p> No posts found.</p>';
            }
            echo json_encode(array('content'=>$content, 'title'=>$subjectID)); 
        }
    }
    //Delete a post
    else if($_POST['action'] == 'delete'){
        $PostID = $_POST['id'];
        $query = "DELETE FROM $subjectID WHERE PostID = $PostID";
        if(mysqli_query($conn, $query)){
            echo "success";
        }
        else{
            echo mysqli_error($conn);
        }
    }
    //Add a post
    else if($_POST['action'] == 'add'){
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $embed = $_POST['embed'];
        $query1 = "SELECT * FROM $subjectID";
        if(!$results = mysqli_query($conn, $query1)){
            echo mysqli_error($conn);
            die();
        }
        if(mysqli_num_rows($results) == 0){
            $NewId = 1;
        }
        else{
            $query1 = "SELECT PostID+1 AS NewId FROM $subjectID ORDER BY PostID DESC LIMIT 1";
            if(!$results = mysqli_query($conn, $query1)){
                echo mysqli_error($conn);
                die();
            }
            $row = mysqli_fetch_array($results);
            $NewId = $row['NewId'];
        }
        $query2 = "INSERT INTO $subjectID(`PostID`, `PostTitle`, `PostDesc`, `PostEmbed`) VALUES ('$NewId', '$title', '$desc', '$embed')";
        if(mysqli_query($conn, $query2)){
            echo "success";
        }
        else{
            echo mysqli_error($conn);
        }
    }
    //Edit a post
    else if($_POST['action'] == 'editForm'){
        $PostID = $_POST['id'];
        $query = "SELECT * FROM $subjectID WHERE PostID = '$PostID'";
        $results = mysqli_query($conn, $query);
        if(!$results = mysqli_query($conn, $query)){
            echo mysqli_error($conn);
            die();
        }
        else{
            $row = mysqli_fetch_array($results);
            echo json_encode(array('PostTitle'=>$row['PostTitle'], 'PostDesc'=>$row['PostDesc'], 'PostEmbed'=>$row['PostEmbed'])); 
        }
    }
    else if($_POST['action'] == 'edit'){
        $PostID = $_POST['id'];
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $embed = $_POST['embed'];
        $query = "UPDATE $subjectID SET PostTitle = '$title', PostDesc = '$desc', PostEmbed = '$embed' WHERE PostID = '$PostID'";
        if(!$results = mysqli_query($conn, $query)){
            echo mysqli_error($conn);
            die();
        }
        else{
            echo "success"; 
        }
    }
    mysqli_close($conn);
    
?>