<?php
    session_start();
    if(!$_SESSION['username']){
        echo '<script type="text/javascript">
           window.location = "../Login/login_form.php"
        </script>';
        die();
    }
    $conn = mysqli_connect('localhost', 'root', '', 'school_management');

    if(!$conn){
        die("Error in connection:". mysqli_error($conn));
    }
    //Retrieve data of the subjects and links to the subject page
    if($_POST['action'] == 'pageContent'){
        $username = $_SESSION['username'];
        $query = "SELECT OnlineTutorialsPerm FROM Users WHERE ID='$username'";
        $results = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($results);
        $perm = $row['OnlineTutorialsPerm'];

        $content = '';
        $query = "SELECT * FROM Subject";
        if(!$results = mysqli_query($conn, $query)){
            echo mysqli_error($conn);
            die();
            $content = 'Could not retrieve data';
        }
        else{
            if (mysqli_num_rows($results) > 0) {
                while ($row = mysqli_fetch_array($results)) {
                    if($perm = "RE"){
                        $content .= '<input class="form-check-input" type="radio" name="subject" value="'. $row['SubjectID'].'">';
                    }
                    $content .= '<a href="SubjectPage.php?id='. $row['SubjectID'].'"class="btn btn-outline-primary btn-square-md" role="button" name="subject">'. $row['SubjectName'].'</a> <b>     </b>';
                }
            } 
            else {
                $content = '<p> No Subjects found.</p>';
            }
        }
        echo $content;
    }
    else{
        $id = $_POST['id'];
        //Delete subject
        if($_POST['action'] == 'delete'){
            $query1 = "DELETE FROM Subject WHERE SubjectID='$id'";
            $query2 = "DROP TABLE $id";
            if(mysqli_query($conn, $query1) && mysqli_query($conn, $query2)){
                echo 'success';
            }
            else{
                echo mysqli_error($conn);
            }
        }
        //Add subject
        else if($_POST['action'] == 'add'){
            $name = $_POST['name'];
            $instructor = $_POST['instructor'];
            $query = "SELECT * FROM Subject WHERE SubjectID='$id'";
            if(!$results = mysqli_query($conn, $query)){
                echo mysqli_error($conn);
                die();
            }
            else{
                if(mysqli_num_rows($results) != 0){
                    echo 'exists';
                    die();
                }
            }
            $query1 = "SELECT * FROM Users WHERE ID='$instructor'";
            if(!$results = mysqli_query($conn, $query1)){
                echo mysqli_error($conn);
                die();
            }
            else{
                if(mysqli_num_rows($results) != 1){
                    echo 'TNotExists';
                    die();
                }
            }
            $query2 = "INSERT INTO Subject VALUES ('$id', '$name', '$instructor')";
            $query3 = "CREATE TABLE $id LIKE demo_subject";
            if(mysqli_query($conn, $query2) && mysqli_query($conn, $query3)){
                echo 'success';
            }
            else{
                echo mysqli_error($conn);
            }
        }
        //Edit subject details
        else if($_POST['action'] == 'editForm'){
            $query = "SELECT * FROM Subject WHERE SubjectID='$id'";
            if(!$results = mysqli_query($conn, $query)){
                echo mysqli_error($conn);
                die();
            }
            else{
                $row = mysqli_fetch_array($results);
                echo json_encode(array('Name'=>$row['SubjectName'], 'Instruct'=>$row['InstructorID'])); 
            }
        }
        else if($_POST['action'] == 'edit'){
            $name = $_POST['name'];
            $instructor = $_POST['instructor'];
            $query1 = "SELECT * FROM Users WHERE ID='$instructor'";
            if(!$results = mysqli_query($conn, $query1)){
                echo mysqli_error($conn);
                die();
            }
            else{
                if(mysqli_num_rows($results) == 0){
                    echo 'TNotExists';
                    die();
                }
            }
            $query2 = "UPDATE Subject SET SubjectName = '$name', InstructorID = '$instructor' WHERE SubjectID='$id'";
            if(!mysqli_query($conn, $query2)){
                echo mysqli_error($conn);
                die();
            }
            echo 'success';
        }
    }
    
    mysqli_close($conn);
?>