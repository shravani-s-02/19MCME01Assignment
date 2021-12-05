<?php
    session_start();
    if(!$_SESSION['username']){
        header("Location:  ../Login/login_form.php");
    }
    $_SESSION['subjectID'] = isset($_GET['id']) ? $_GET['id'] : '';
?>

<html>
    <head>
        <title> </title>
        <meta charset="utf-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel = "icon" href = "../Website/WebIcon.png" type = "image/x-icon">    
        <link rel="stylesheet" href="../jquery-ui-1.13.0/jquery-ui.min.css">
        <link rel="stylesheet" href="../bootstrap/bootstrap-5.0.2-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../Website/WebsiteAttributesStylesheet.css">

        <script src="../bootstrap/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
        <script src="../jquery-ui-1.13.0/external/jquery/jquery.js"></script>
        <script src="../jquery-ui-1.13.0/jquery-ui.min.js"></script>
        <script src="../Website/WebsiteScript.js"></script>
        <script src="SubjectPageScript.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand">
                    <img src="../Website/WebIcon.png" width="30" height="30" class="d-inline-block align-top" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav" id="permItems">
                        <li class="nav-item">
                            <a class="nav-link" href="../User/UserHomePage.html">Home</a>
                        </li>
                    </ul>
                </div>
                <ul class="navbar-nav mr-auto">
                    <a class="nav-link" href="../Login/log_out.php"><u>Log Out</u></a>
                </ul>
            </div>
        </nav>

        <div class="container rounded">     
            <!--Action buttons-->
            <div id="buttons" >
                <button type="button" class="btn btn-outline-info actionBtn selectionRequireAction" id="editAction">Edit</button>
                <button type="button" class="btn btn-outline-success float-end actionBtn" id="addAction">Add</button>
                <button type="button" class="btn btn-outline-danger float-end actionBtn selectionRequireAction" id="deleteAction">Delete</button>
            </div> 
            <hr>
            <!--Posts for subject-->
            <div id="posts">
                
            </div>

            <!--Edit post form-->
            <div class="modal fade" id="editForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Edit post</h5>
                        </div>
                        <div class="modal-body" id="addFormBody">
                            <form class="form-outline mb-4" >
                                <div class="form-group">
                                    <label class="form-label">Title: </label>
                                    <input type = "text" class="form-control" id="editTitle"/>
                                    <div class="form-text">Title of the post, max 150 characters</div><br>
                                </div>
                                <p id="editTitleError" style="color: red;"></p>

                                <div class="form-group">
                                    <label class="form-label">Description: </label>
                                    <textarea class="form-control" id="editDesc" rows="4"></textarea>
                                    <div class="form-text">Description of the post, max 500 characters</div><br>
                                </div>
                                <p id="editDescError" style="color: red;"></p>

                                <div class="form-group">
                                    <label class="form-label">Embed: </label>
                                    <input type = "text" class="form-control" id="editEmbed"/>
                                    <div class="form-text">Link of youtube video to be added to the post, the link must be in embedable form ex:"https://www.youtube.com/embed/videoID"</div><br>
                                </div>
                                <p id="editEmbedError" style="color: red;"></p>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary CancelButton" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="editBtn">Edit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--Add post form-->
            <div class="modal fade" id="addForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Add post</h5>
                        </div>
                        <div class="modal-body" id="addFormBody">
                            <form class="form-outline mb-4" >
                                <div class="form-group">
                                    <label class="form-label">Title: </label>
                                    <input type = "text" class="form-control" id="addTitle"/>
                                    <div class="form-text">Title of the post, max 150 characters</div><br>
                                </div>
                                <p id="addTitleError" style="color: red;"></p>
                                <div class="form-group">
                                    <label class="form-label">Description: </label>
                                    <textarea class="form-control" id="addDesc" rows="4"></textarea>
                                    <div class="form-text">Description of the post, max 500 characters</div><br>
                                </div>
                                <p id="addDescError" style="color: red;"></p>
                                <div class="form-group">
                                    <label class="form-label">Embed: </label>
                                    <input type = "text" class="form-control" id="addEmbed"/>
                                    <div class="form-text">Link of youtube video to be added to the post, the link must be in embedable form ex:"https://www.youtube.com/embed/videoID"</div><br>
                                </div>
                                <p id="addEmbedError" style="color: red;"></p>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary CancelButton" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="addBtn">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--Delete post popup form-->
            <div class="modal fade" id="deleteForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Delete post</h5>
                        </div>
                        <div class="modal-body" id="deleteFormBody">
                            Delete this post?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary CancelButton" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="deleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Error popups-->
        <div class="modal fade" id="enterFields" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        Enter the post title 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="select" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        Select a post to perform this action on
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="notSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        Action was unsuccessfull
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>