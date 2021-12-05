$(document).ready(function(){
    $.ajax({ 
        url:'LibPageAction.php',
        type: 'POST',
        data:{'action':'pageContent'},
        success: function(data) {
            $("#bookList").append(data);
        }
    });

    $.ajax({ 
        url: "../Website/Permissions.php",
        success: function(data) {
            var array = jQuery.parseJSON(data);
            if(array.LibPerm == "R"){            
                $("#buttons").addClass("invisible");
            }
            if(array.LibPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link active" id="libNav" href="../Library/LibPage.html">Library</a></li>');
                $("#permItems").append(nav_item_add);
            }
            if(array.HostelPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link" id="hostelNav" href="../Hostel/HostelPage.html">Hostel</a></li>');
                $("#permItems").append(nav_item_add);
            }
            if(array.OnlineTutorialsPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link" id="tutorialNav" href="../OnlineTutorials/TutorialPage.html">Tutorials</a></li>');
                $("#permItems").append(nav_item_add);
            } 
        }
    });

    $(".selectionRequireAction").click(function() {
        var id = $("input[name='book']:checked").val();
        if(id == undefined){
            $("#select").modal("toggle");
        }
    });

    /*Issue or return a book*/
    $("#issue_returnAction").click(function() {
        var id = $("input[name='book']:checked").val();
        $("#issueDate").removeClass("form-control-plaintext");
        $("#issue_returnLender").removeClass("form-control-plaintext");
        if(id != undefined){
            $.ajax({
                url: 'LibPageAction.php',
                type: 'POST',
                data:{'action':'issue_returnForm', 'bookID':id},
                success: function (data) {
                    var array = jQuery.parseJSON(data);
                    $('#issue_returnID').val(id);
                    $('#issue_returnName').val(array.Name);
                    $('#issue_returnStatus').val(array.Status);
                    if(array.Status == "NotAvailable"){
                        $("#issue_returnLender").removeClass("form-control");
                        $("#issue_returnLender").addClass("form-control-plaintext");
                        $('#issue_returnLender').val(array.LenderID);
                        $('#issue_returnLender').prop('readonly', true);
                        $('#issueDate').val(array.IssuedDate);
                        $("#issueDate").removeClass("form-control");
                        $("#issueDate").addClass("form-control-plaintext");
                        $('#issueDate').prop('readonly', true);
                        $("#ReturnGroup").removeClass("invisible");
                        $("#returnDate").val($.datepicker.formatDate('yy/mm/dd', new Date()));
                        $("#issue_returnBtn").html("Return");
                    }
                    else if(array.Status == "Available"){
                        $('#issueDate').val("");
                        $('#issue_returnLender').val("");
                        $("#issue_returnLender").addClass("form-control");
                        $('#issue_returnLender').prop('readonly', false);
                        $("#issueDate").addClass("form-control");
                        $("#issueDate").val($.datepicker.formatDate('yy/mm/dd', new Date()));
                        $('#issueDate').prop('readonly', false);
                        $("#returnDate").val("None");
                        $("#ReturnGroup").addClass("invisible");
                        $("#issue_returnBtn").html("Issue");
                    }
                    $("#issue_returnForm").modal("toggle");
                }  
            });
        }
    });

    $("#issue_returnBtn").click(function() {
        var id = $("input[name='book']:checked").val();
        var action = $('#issue_returnBtn').text();
        var IssuedDate = $("#issueDate").val();
        var ReturnedDate = $("#returnDate").val();
        var LenderID = $("#issue_returnLender").val();
        var error = 0;
        if(action == "Return"){
            if(ReturnedDate == '' || LenderID == ''){
                $("#enterFields").modal("toggle");
                error++;
            }
        }
        else{
            if(IssuedDate == '' || LenderID == ''){
                $("#enterFields").modal("toggle");
                error++;
            }
        }
      
        if(LenderID.length < 8 || LenderID.length > 8 ){
            $('#editIDError').empty();
            $('#editIDError').append('ID entered is not 8 characters');
            error++;
        }
        else{
            $('#editIDError').empty();
        }
        if(error == 0){
            $.ajax({
                url: 'LibPageAction.php',
                type: 'POST',
                data:{'action':action , 'bookID':id, 'IssuedDate':IssuedDate, 'ReturnedDate':ReturnedDate, 'LenderID':LenderID},
                success: function (data) {
                    if(data == "success"){
                        location.reload(true);
                    }
                    else if(data == "notExists"){
                        $('#editIDError').empty();
                        $('#editIDError').append('Student with ID \'' + LenderID + '\' does not exist');
                    }
                    else{
                        $("#notSuccess").modal("toggle");
                    }
                }  
            });
        }
    });

    /*Add book*/
    $("#addAction").click(function() {
        $("#addForm").modal("toggle");
    });

    $("#addBtn").click(function() {
        var id = $("#addID").val();
        var name = $("#addName").val();
        var author = $("#addAuthor").val();
        var genre = $("#addGenre").val();
        var error = 0;
        if(id == '' || name == '' || author == '' || genre == ''){
            $("#enterFields").modal("toggle");
            error++;
        }
        if(id.length > 6 && id != ''){
            $('#addIDError').empty();
            $('#addIDError').append('Book ID entered is not greater than 6 characters');
            error++;
        }
        else{
            $('#addIDError').empty();
        }
        if(error == 0){
            $.ajax({
                url: 'LibPageAction.php',
                type: 'POST',
                data:{'action':'add', 'ID':id, 'Name':name, 'Author':author, 'Genre':genre},
                success: function (data) {
                    if(data == "success"){
                        location.reload(true);
                    }
                    else if(data == "exists"){
                        $('#addIDError').empty();
                        $('#addIDError').append('A book with the ID \'' + id +'\' already exists');
                    }
                    else{
                        $("#notSuccess").modal("toggle");
                    }
                }  
            });
        }
    });

    /*Delete book*/
    $("#deleteAction").click(function() {
        var id = $("input[name='book']:checked").val();
        if(id != undefined){
            $("#deleteFormBody").empty();
            $("#deleteFormBody").append("Delete book "+id+"?");
            $("#deleteForm").modal("toggle");
        }
    });

    $("#deleteBtn").click(function() {
        var id = $("input[name='book']:checked").val();
        $.ajax({
            url: 'LibPageAction.php',
            type: 'POST',
            data:{'action':'delete', 'bookID':id},
            success: function (data) {
                if(data == "success"){
                    location.reload(true);
                }
                else{
                    $("#notSuccess").modal("toggle");
                }
            }
        });
    });
});