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

    /*Adds the rows of room details*/
    if ($_POST['action'] == 'pageContent'){
        $username = $_SESSION['username'];
        $query = "SELECT HostelPerm FROM school_management.Users WHERE ID='$username'";
        $results = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($results);
        $perm = $row['HostelPerm'];

        $query1 = "SELECT * FROM school_management.Hostel ORDER BY BuildingNumber,RoomNumber";
        if(!$results1 = mysqli_query($conn, $query1)){
            echo  "Could not retrieve data";
            die();
        }

        $table = '';
        if (mysqli_num_rows($results1) > 0) {
            while ($row1 = mysqli_fetch_array($results1)) {
                $table .= '<tr>';
                if($perm == "RE"){
                    $table .= '<td><input class="form-check-input" type="radio" name="room" value="'. $row1['RoomNumber'] . ' ,' . $row1['BuildingNumber'] .'" id="flexCheckDefault"></td>';
                }
                else{
                    $table .= '<td> </td>';
                }
                $table .= '<td>' . $row1['RoomNumber'] . '</td>
                    <td>' . $row1['BuildingNumber'] . '</td>';
                if($row1['NumOfOccupants'] == 0){
                    $table .= '<td>Vacant<br>0/' . $row1['OccupancyLimit'] .'</td>';
                    $table .= '<td> - </td>';
                }
                else{
                    if($row1['NumOfOccupants'] == $row1['OccupancyLimit']){
                        $table .= '<td>Fully Occupied<br>'. $row1['NumOfOccupants'] . '/' . $row1['OccupancyLimit'] . '</td>';
                    }
                    else{
                        $table .= '<td>Partially Occupied<br>' . $row1['NumOfOccupants'] . '/' . $row1['OccupancyLimit'] . '</td>';
                    }
                    $RoomNumber = $row1['RoomNumber'];
                    $BuildingNumber = $row1['BuildingNumber'];
                    $query2 = "SELECT * FROM school_management.Occupancy_Logs WHERE MoveOutDate IS NULL AND RoomNumber='$RoomNumber' AND BuildingNumber='$BuildingNumber'";
                    if(!$results2 = mysqli_query($conn, $query2)){
                        echo  "Could not retrieve data";
                    }

                    $table .= '<td>';
                    while ($row2 = mysqli_fetch_array($results2)) {
                        if($perm == "RE"){
                            $table .= '<input class="form-check-input" type="radio" name="occu" value="'. $row1['RoomNumber'] . ' ,' . $row1['BuildingNumber'] . ' ,' . $row2['OccupantID'] .'" id="flexCheckDefault">';
                        }
                        $table .= $row2['OccupantID'] . '<br>';
                    }
                    $table .= '</td>';
                }
                $table .='</tr>';
            }
        } 
        else {
            $table = '<tr>
                <td colspan="5"> No rooms found. </td>   
                </tr>';
        }
        echo $table;
    }

    /*Add a room*/
    else if ($_POST['action'] == 'add'){
        $roomnNo = $_POST['room'];
        $buildingNo = $_POST['building'];
        $limit = $_POST['limit']; 
        $query = "SELECT * FROM Hostel WHERE RoomNumber=$roomnNo AND BuildingNumber=$buildingNo";
        $results = mysqli_query($conn, $query);
        if(mysqli_num_rows($results) != 0){
            echo "exists";
            die();
        }
        else{
            $query = "INSERT INTO Hostel VALUES ('$roomnNo', '$buildingNo', '$limit', 0)";
            if(mysqli_query($conn, $query)){
                echo 'success';
            }
            else{
                echo mysqli_error($conn);
            }
        }
    }

    /*Delete a room*/
    else if ($_POST['action'] == 'delete'){
        $roomnNo = $_POST['room'];
        $buildingNo = $_POST['building'];
        $query = "DELETE FROM Hostel WHERE RoomNumber= $roomnNo AND BuildingNumber = $buildingNo";
        if(mysqli_query($conn, $query)){
            echo 'success';
        }
        else{
            echo mysqli_error($conn);
        }
    }

    /*Edit room occupancy limit*/
    else if ($_POST['action'] == 'editForm'){
        $roomnNo = $_POST['room'];
        $buildingNo = $_POST['building'];

        $query = "SELECT OccupancyLimit FROM Hostel WHERE RoomNumber = $roomnNo AND BuildingNumber = $buildingNo";
        if(!$result = mysqli_query($conn, $query)){
            echo mysqli_error($conn);
            die();
        }
        $row = mysqli_fetch_array($result);
        echo $row['OccupancyLimit'];
    }
    else if ($_POST['action'] == 'edit'){
        $roomnNo = $_POST['room'];
        $buildingNo = $_POST['building'];
        $Limit = $_POST['Limit'];
        if($Limit != ''){
            $query = "UPDATE Hostel SET OccupancyLimit = $Limit WHERE RoomNumber = $roomnNo AND BuildingNumber = $buildingNo";
            if(!mysqli_query($conn, $query)){
                echo mysqli_error($conn);
                die();
            }
        }
        echo 'success';
    }

    /*Check in a occupant*/
    else if ($_POST['action'] == 'checkInForm'){
        $roomnNo = $_POST['room'];
        $buildingNo = $_POST['building'];

        $query = "SELECT OccupancyLimit, NumOfOccupants FROM Hostel WHERE RoomNumber = $roomnNo AND BuildingNumber = $buildingNo";
        if(!$results = mysqli_query($conn, $query)){
            echo mysqli_error($conn);
            die();
        }
        $row = mysqli_fetch_array($results);
        if($row['OccupancyLimit'] == $row['NumOfOccupants']){
            echo "Full";
        }
        else{
            echo "Availble";
        }
    }
    else if ($_POST['action'] == 'checkIn'){
        $roomnNo = $_POST['room'];
        $buildingNo = $_POST['building'];
        $id = $_POST['id'];
        $moveInDate = $_POST['moveInDate'];
       
        $query1 = "SELECT * FROM Students WHERE StudentID = '$id'";
        if(!$results = mysqli_query($conn, $query1)){
            echo mysqli_error($conn);
            die();
        }
        else{
            if(mysqli_num_rows($results) == 0){
                echo 'notExists';
                die();
            }
        }
        $query2 = "SELECT * FROM Occupancy_Logs WHERE OccupantID = '$id' AND MoveOutDate IS NULL";
        if(!$results = mysqli_query($conn, $query2)){
            echo mysqli_error($conn);
            die();
        }
        else{
            if(mysqli_num_rows($results) > 0){
                echo 'alreadyMovedIn';
                die();
            }
        }
        $query3 = "INSERT INTO Occupancy_Logs VALUES ($roomnNo, $buildingNo, '$id', '$moveInDate', NULL)";
        $query4 = "UPDATE Hostel SET NumOfOccupants = NumOfOccupants+1 WHERE RoomNumber = $roomnNo AND BuildingNumber = $buildingNo";
        if(!mysqli_query($conn, $query3) || !mysqli_query($conn, $query4)){
            echo mysqli_error($conn);
            die();
        }
        else{
            echo 'success';
        }
    }

    /*Check out a occupant*/
    else if ($_POST['action'] == 'checkOutForm'){
        $roomnNo = $_POST['room'];
        $buildingNo = $_POST['building'];
        $id = $_POST['id'];
        $query = "SELECT MoveInDate FROM Occupancy_Logs WHERE RoomNumber = $roomnNo AND BuildingNumber = $buildingNo AND OccupantID = '$id' AND MoveOutDate IS NULL";
        if(!$result = mysqli_query($conn, $query)){
            echo mysqli_error($conn);
            die();
        }
        $row = mysqli_fetch_array($result);
        echo $row['MoveInDate'];
    }
    else if ($_POST['action'] == 'checkOut'){
        $roomnNo = $_POST['room'];
        $buildingNo = $_POST['building'];
        $id = $_POST['id'];
        $moveInDate = $_POST['moveInDate'];
        $moveOutDate = $_POST['moveOutDate'];
        $query1 = "UPDATE Occupancy_Logs SET MoveOutDate = '$moveOutDate' WHERE RoomNumber = $roomnNo AND BuildingNumber = $buildingNo AND OccupantID = '$id' AND MoveInDate = '$moveInDate'";
        $query2 = "UPDATE Hostel SET NumOfOccupants = NumOfOccupants-1 WHERE RoomNumber = $roomnNo AND BuildingNumber = $buildingNo";
        if(!mysqli_query($conn, $query1) || !mysqli_query($conn, $query2)){
            echo mysqli_error($conn);
            die();
        }
        echo 'success';
    }
    mysqli_close($conn);
?>