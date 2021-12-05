$(document).ready(function(){
    $.ajax({ 
        url:'TutorialPageAction.php',
        type: 'POST',
        data:{'action':'pageContent'},
        success: function(data) {
            $("#subjects").append(data);
        }
    });

    $.ajax({ 
        url: "../Website/Permissions.php",
        success: function(data) {
            var array = jQuery.parseJSON(data);
            if(array.OnlineTutorialsPerm == "R" || array.OnlineTutorialsPerm == "T"){            
                $("#buttons").addClass("invisible");
            }
            if(array.LibPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link" id="libNav" href="../Library/LibPage.html">Library</a></li>');
                $("#permItems").append(nav_item_add);
            }
            if(array.HostelPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link" id="hostelNav" href="../Hostel/HostelPage.html">Hostel</a></li>');
                $("#permItems").append(nav_item_add);
            }
            if(array.OnlineTutorialsPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link active" id="tutorialNav" href="../OnlineTutorials/TutorialPage.html">Tutorials</a></li>');
                $("#permItems").append(nav_item_add);
            } 
        }
    });


    $(".selectionRequireAction").click(function() {
        var id = $("input[name='subject']:checked").val();
        if(id == undefined){
            $("#select").modal("toggle");
        }
    });

    /*Edit subject details*/
    $("#editAction").click(function() {
        var id = $("input[name='subject']:checked").val();
        if(id != undefined){
            $('#addID').val(id);
            $.ajax({
                url: 'TutorialPageAction.php',
                type: 'POST',
                data:{'action':'editForm', 'id':id},
                success: function (data) {
                    var resultArray = jQuery.parseJSON(data);
                    $('#editID').val(id);
                    $('#editName').val(resultArray['Name']);
                    $('#editInstructor').val(resultArray['Instruct']);
                }
            });
            $("#editForm").modal("toggle");
        }
    });

    $("#editBtn").click(function() {
        var id= $('#editID').val();
        var name = $('#editName').val();
        var  instructor = $('#editInstructor').val();
        if(name == '' || instructor == ''){
            $("#enterFields").modal("toggle");
        }
        else{
            $.ajax({
                url: 'TutorialPageAction.php',
                type: 'POST',
                data:{'action':'edit', 'id':id, 'name':name, 'instructor':instructor},
                success: function (data) {
                    if(data == "success"){
                        location.reload(true);
                    }
                    else if(data == "TNotExists"){
                        $("#editError").empty();
                        $("#editError").append('A user with the ID "' + instructor +'" does not exists');
                    }
                    else{
                        $("#notSucess").modal("toggle");
                    }
                }
            });
        }
    });

    /*Add subject*/
    $("#addAction").click(function() {
        $("#addForm").modal("toggle");
    });

    $("#addBtn").click(function() {
        var id= $('#addID').val();
        var name = $('#addName').val();
        var instructor = $('#addInstructor').val();
        if(id == '' || name == '' || instructor == ''){
            $("#enterFields").modal("toggle");
        }
        else{
            $.ajax({
                url: 'TutorialPageAction.php',
                type: 'POST',
                data:{'action':'add', 'id':id, 'name':name, 'instructor':instructor},
                success: function (data) {
                    if(data == "success"){
                        location.reload(true);
                    }
                    else if(data == "exists"){
                        $("#addError").empty();
                        $("#addError").append('A subject with the ID "' + id +'" already exists');
                    }
                    else if(data == "TNotExists"){
                        $("#addError").empty();
                        $("#addError").append('A teacher with the ID "' + instructor +'" does not exists');
                    }
                    else{
                        $("#notSucess").modal("toggle");
                    }
                }
            });
        }
    });

    /*Delete subject*/
    $("#deleteAction").click(function() {
        var id = $("input[name='subject']:checked").val();
        if(id != undefined){
            $("#deleteFormBody").empty();
            $("#deleteFormBody").append("Delete subject "+ id +"?");
            $("#deleteForm").modal("toggle");
        }
    });

    $("#deleteBtn").click(function() {
        var id = $("input[name='subject']:checked").val();
        $.ajax({
            url: 'TutorialPageAction.php',
            type: 'POST',
            data:{'action':'delete', 'id':id},
            success: function (data) {
                if(data == "success"){
                    location.reload(true);
                }
                else{
                    $("#notSucess").modal("toggle");
                }
            }
        });
    });
});