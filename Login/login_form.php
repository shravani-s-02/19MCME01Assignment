<?php
    session_start();
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
                            <form class="form-outline mb-4" action = "login_form.php" method = "post">
                                <div class="form-group">
                                    <label class="form-label">Username: </label>
                                    <input type = "text" class="form-control" name = "username"/><br><br>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Password: </label>
                                    <input type = "password" class="form-control" name = "password"/><br><br>
                                </div>
                                
                                <input type = "submit" class="btn btn-secondary" value = "Login" name="loginBtn"/><br />
                                <a href='PasswordChangeRequest.php'>Forgot your password?</a>
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
        $username = $_POST['username'];
        $password_input = $_POST['password'];

        //Checking if all fields were entered
        if(empty($username) || empty($password_input)){
            die('<script type="text/JavaScript"> 
                        document.getElementById("error").innerHTML = "Please enter all fields";
                        </script>');
        }
        
        //Connecting to the database
        $conn = mysqli_connect('localhost', 'root', '', 'school_management');

        if(!$conn){
            die("Error in connection:". mysqli_error($conn));
        }

        //Check if its the users first time loging in 
        $query = "SELECT * FROM school_management.Users WHERE ID='$username' AND Password='$password_input'";
        $results = mysqli_query($conn, $query);

        if (mysqli_num_rows($results) == 1) {
            $row = mysqli_fetch_array($results);

            if(!preg_match("/^[a-fA-F0-9]{40}$/", strval($row['Password']))){
                if (mysqli_num_rows($results) == 1) {
                    $_SESSION['username'] = $username;
                    header("Location:  change_passwd.php");
                    exit;
                }
                else{
                    die('<script type="text/JavaScript"> 
                        document.getElementById("error").innerHTML = "Invalid username and/or password combination";
                        </script>');
                }
            }
        }
        else{
            mysqli_free_result($results);

            //Checking if the username and password match an row in the table
            $password = sha1($password_input);
            $query = "SELECT * FROM school_management.Users WHERE ID='$username' AND Password='$password'";
            $results = mysqli_query($conn, $query);
            if (mysqli_num_rows($results) == 1) {
                //If they match the user is logged in
                $_SESSION['username'] = $username;
                header("Location:  ../User/UserHomePage.html");
                exit;
            }
            else{
                //Else shows error
                die('<script type="text/JavaScript"> 
                    document.getElementById("error").innerHTML = "Invalid username and/or password combination";
                    </script>');
            }
        }
    }
?>