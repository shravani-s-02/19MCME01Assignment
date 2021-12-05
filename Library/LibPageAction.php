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

    /*To add the rows of book details*/
    if ($_POST['action'] == 'pageContent'){
        $username = $_SESSION['username'];
        $query = "SELECT LibPerm FROM school_management.Users WHERE ID='$username'";
        $results = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($results);
        $perm = $row['LibPerm'];

        $query = "SELECT * FROM school_management.Library";
        $results = mysqli_query($conn, $query);

        $table = '';
        if (mysqli_num_rows($results) > 0) {
            while ($row = mysqli_fetch_array($results)) {
                $table .= '<tr>';
                if($perm == "RE"){
                    $table .= '<td><input class="form-check-input" type="radio" name="book" value="'. $row['BookID'] .'" id="flexCheckDefault"></td>';
                }
                else{
                    $table .= '<td> </td>';
                }
                $table .= '<td>' . $row['BookID'] . '</td>
                    <td>' . $row['BookName'] . '</td>
                    <td>' . $row['Author'] . '</td>
                    <td>' . $row['Genre'] . '</td>
                </tr>';
            }
        } 
        else {
            $table = '<tr>
                <td colspan="5"> No books found. </td>   
            </tr>';
        }
        echo $table;
    }
    /*Adding a new book*/
    else if ($_POST['action'] == 'add'){
        $ID = $_POST['ID'];
        $Name = $_POST['Name'];
        $Author = $_POST['Author'];
        $Genre = $_POST['Genre'];
        $query = "SELECT * FROM Library WHERE BookID='$ID'";
        $results = mysqli_query($conn, $query);
        if(mysqli_num_rows($results) != 1){
            $query = "INSERT INTO library VALUES ('$ID', '$Name', '$Author', '$Genre')";
        }
        else{
            echo "exists";
            die();
        }
    }
    /*Deleting a book*/
    else if ($_POST['action'] == 'delete'){
        $ID = $_POST['bookID'];
        $query = "DELETE FROM Library WHERE BookID='$ID'";
    }
    /*Returning book details to display in issue/return form*/
    else if ($_POST['action'] == 'issue_returnForm'){
        $ID = $_POST['bookID'];
        $query1 = "SELECT * FROM Checkout_Logs WHERE BookID='$ID' AND ReturnedDate IS NULL";
        $results1 = mysqli_query($conn, $query1);
        $row1 = mysqli_fetch_array($results1);

        $query2 = "SELECT BookName FROM Library WHERE BookID='$ID'";
        $results2 = mysqli_query($conn, $query2);
        $row2 = mysqli_fetch_array($results2);
        if(mysqli_num_rows($results1) == 1){
            echo json_encode(array('Name'=>$row2['BookName'], 'IssuedDate'=>$row1['IssuedDate'], 'LenderID'=>$row1['LenderID'], 'Status'=>'NotAvailable'));
            die();
        }
        else{
            echo json_encode(array('Name'=>$row2['BookName'], 'Status'=>'Available'));
            die();
        }
    }
    /*Issuing a book*/
    else if ($_POST['action'] == 'Issue'){
        $ID = $_POST['bookID'];
        $IssuedDate = $_POST['IssuedDate'];
        $ReturnedDate = $_POST['ReturnedDate'];
        $LenderID = $_POST['LenderID'];
        $query = "SELECT * FROM Students WHERE StudentID='$LenderID'";
        $results = mysqli_query($conn, $query);
        if(mysqli_num_rows($results) == 1){
            $query ="INSERT INTO Checkout_Logs VALUES ('$ID', '$LenderID', '$IssuedDate', NULL)";
        }
        else{
            echo "notExists";
            die();
        }
    }
    /*Returning a book*/
    else if ($_POST['action'] == 'Return'){
        $ID = $_POST['bookID'];
        $IssuedDate = $_POST['IssuedDate'];
        $ReturnedDate = $_POST['ReturnedDate'];
        $LenderID = $_POST['LenderID'];
        $query ="UPDATE Checkout_Logs SET ReturnedDate = '$ReturnedDate' WHERE BookID = '$ID' AND LenderID = '$LenderID' AND IssuedDate = '$IssuedDate'";
    }

    if(mysqli_query($conn, $query)){
        echo 'success';
    }
    else{
        echo mysqli_error($conn);
    }
    mysqli_close($conn);
?>