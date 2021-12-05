<?php
    session_start();
    $username = $_SESSION['username'];
?>

<html>
    <head>
        <title>School Management</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel = "icon" href = "../Website/WebIcon.png" type = "image/x-icon">
        <link href="../bootstrap/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="../bootstrap/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>      
        <div class="container-lg">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-4 col-md-8 col-sm-g">
                    <div class="card">
                        <div class="card-title text-center border-bottom">
                            <h4>Login</h4>
                        </div>
                        <div class="card-body text-center">
                            <form class="form-outline mb-4" action = "change_passwd.php" method = "post">
                                <p>Change your password from the default</p>
                                <div class="form-group">
                                    <label class="form-label">New Password </label>
                                   <input type = "password" class="form-control" name = "new_password"/><br><br>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Renter New Password </label>
                                    <input type = "password" class="form-control" name = "renter_pass"/><br><br>
                                </div>
                                
                                <input type = "submit" class="btn btn-secondary" value = "next ->" name="change_btn"/><br />
                                <p id="error" style="color: red;"></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Creating and verifying connection
        $conn = mysqli_connect('localhost', 'root', '', 'school_management');

        if(!$conn){
            die("Error in connection:". mysqli_error($conn));
        }

        $new_password = sha1($_POST['new_password']);
        $password_rentered = sha1($_POST['renter_pass']);
        //Checking if all fields were entered
        if(empty($new_password) || empty($password_rentered)){
            die('<script type="text/JavaScript"> 
                document.getElementById("error").innerHTML = "Please enter all fields";
                </script>');
        }
        else if($new_password != $password_rentered){
            die('<script type="text/JavaScript"> 
                document.getElementById("error").innerHTML = "The passwords entered do not match.";
                </script>');
        }

        $sql = "UPDATE school_management.Users SET Password = '$new_password' WHERE ID = '$username'";
        //Changes password and redirects to user home page
        if(mysqli_query($conn, $sql)){
            header("Location:  ../User/UserHomePage.html");
        }
        else{
            echo mysqli_error($conn);
        }
        $conn->close();
    } 
?>