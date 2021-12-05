$(document).ready(function(){
    $.ajax({ 
        url:'HostelPageAction.php',
        type: 'POST',
        data:{'action':'pageContent'},
        success: function(data) {
            $("#roomList").append(data);
        }
    });

    $.ajax({ 
        url: "../Website/Permissions.php",
        success: function(data) {
            var array = jQuery.parseJSON(data);
            if(array.LibPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link" id="libNav" href="../Library/LibPage.html">Library</a></li>');
                $("#permItems").append(nav_item_add);
            }
            if(array.HostelPerm != "N"){    
                var nav_item_add = $('<li class="nav-item"><a class="nav-link active" id="hostelNav" href="../Hostel/HostelPage.html">Hostel</a></li>');
                $("#permItems").append(nav_item_add);
            }
            if(array.OnlineTutorialsPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link" id="tutorialNav" href="../OnlineTutorials/TutorialPage.html">Tutorials</a></li>');
                $("#permItems").append(nav_item_add);
            } 
        }
    });

    $.ajax({
        url: '../Website/Permissions.php',
        success: function(data) {
            var array = jQuery.parseJSON(data);
            if(array.HostelPerm == "R"){            
                $("#buttons").addClass("invisible");
            }
        }
    });

    $(".selectionRequireAction").click(function() {
        var id = $("input[name='room']:checked").val();
        if(id == undefined){
            $("#selectBody").empty();
            $("#selectBody").append("Select a room to perform this action on");
            $("#select").modal("toggle");
        }
    });

    $(".selectionRequireAction2").click(function() {
        var id = $("input[name='occu']:checked").val();
        if(id == undefined){
            $("#selectBody").empty();
            $("#selectBody").append("Select a occupant to perform this action on");
            $("#select").modal("toggle");
        }
    });

    /*Edit Room details*/
    $("#editAction").click(function() {
        var roomDetails = $("input[name='room']:checked").val();
        var array = roomDetails.split(',');
        $('#OcupantDetails').empty();
        if(roomDetails != undefined){
            $('#editRoom').val(array[0]);
            $('#editBuilding').val(array[1]);
            $.ajax({
                url: 'HostelPageAction.php',
                type: 'POST',
                data:{'action':'editForm', 'room':array[0], 'building':array[1]},
                success: function (data) {
                    $('body').append(data);
                    $('#editLimit').val(data);
                }
            });
            $("#editForm").modal("toggle");
        }
    });

    $("#editBtn").click(function() {
        var roomDetails = $("input[name='room']:checked").val();
        var room = roomDetails.split(',');
        var Limit  = $("#editLimit").val();
        if(Limit == ''){
            $("#enterFields").modal("toggle");
        }
        else{
            $.ajax({
                url: 'HostelPageAction.php',
                type: 'POST',
                data:{'action':'edit', 'room':room[0], 'building':room[1], 'Limit':Limit},
                success: function (data) {
                    if(data == "success"){
                        location.reload(true);
                    }
                    else{
                        $("#notSuccess").modal("toggle");
                    }
                }
            });
        }
    });

    /*Check in occupant*/
    $("#checkInAction").click(function() {
        var roomDetails = $("input[name='room']:checked").val();
        var array = roomDetails.split(','); 
        if(roomDetails != undefined){
            $.ajax({
                url: 'HostelPageAction.php',
                type: 'POST',
                data:{'action':'checkInForm', 'room':array[0], 'building':array[1]},
                success: function (data) {
                    if(data == 'Full'){
                        $("#selectBody").empty();
                        $("#selectBody").append("Room has max number of occupants");
                        $("#select").modal("toggle");
                    }
                    else if(data == 'Availble'){
                        $('#checkInRoom').val(array[0]);
                        $('#checkInBuilding').val(array[1]);
                        $("#checkInForm").modal("toggle");
                    }
                }
            });
        }
    });

    $("#checkInBtn").click(function() {
        var roomDetails = $("input[name='room']:checked").val();
        var room = roomDetails.split(',');
        var id  = $("#moveInID").val();
        var moveInDate  = $("#addMoveInDate").val();
        var error = 0;
        if(id == '' || moveInDate == ''){
            error++;
            $("#enterFields").modal("toggle");
        }
        if(id.length < 8 || id.length > 8 ){
            $('#checkInError').empty();
            $('#checkInError').append('ID entered is not 8 characters');
            error++;
        }
        else{
            $('#editIDError').empty();
        }
        if(error == 0){
            $.ajax({
                url: 'HostelPageAction.php',
                type: 'POST',
                data:{'action':'checkIn', 'room':room[0], 'building':room[1], 'id':id, 'moveInDate':moveInDate},
                success: function (data) {
                    if(data == "success"){
                        location.reload(true);
                    }
                    else if(data == "alreadyMovedIn"){
                        $('#checkInError').empty();
                        $('#checkInError').append("Student with '" + id + "' has already been assigned a room");
                    }
                    else if(data == "notExists"){
                        $('#checkInError').empty();
                        $('#checkInError').append("Student '" + id + "' does not exist");
                    }
                    else{
                        $("#notSuccess").modal("toggle");
                    }
                }
            });
        }
    });

    /*Check out occupant*/
    $("#checkOutAction").click(function() {
        var roomDetails = $("input[name='occu']:checked").val();
        var array = roomDetails.split(',');
        if(roomDetails != undefined){
            $('#editRoom').val(array[0]);
            $('#editBuilding').val(array[1]);
            $.ajax({
                url: 'HostelPageAction.php',
                type: 'POST',
                data:{'action':'checkOutForm', 'room':array[0], 'building':array[1], 'id':array[2]},
                success: function (data) {
                    $('#checkOutRoom').val(array[0]);
                    $('#checkOutBuilding').val(array[1]);
                    $('#checkOutID').val(array[2]);
                    $('#checkOutMoveInDate').val(data);
                    $("#checkOutForm").modal("toggle");
                }
            });
        }
    });

    $("#checkOutBtn").click(function() {
        var occupantDetails = $("input[name='occu']:checked").val();
        var occupant = occupantDetails.split(',');
        var moveInDate = $("#checkOutMoveInDate").val();
        var moveOutDate = $("#addMoveOutDate").val();
        if(moveOutDate == ''){
            $("#enterFields").modal("toggle");
        }
        else{
            $.ajax({
                url: 'HostelPageAction.php',
                type: 'POST',
                data:{'action':'checkOut', 'room':occupant[0], 'building':occupant[1], 'id':occupant[2], 'moveInDate':moveInDate, 'moveOutDate':moveOutDate},
                success: function (data) {
                    if(data == "success"){
                        location.reload(true);
                    }
                    else{
                        $("#notSuccess").modal("toggle");
                    }
                }
            });
        }
    });

    /*Add Room*/
    $("#addAction").click(function() {
        $("#addForm").modal("toggle");
    });

    $("#addBtn").click(function() {
        var room = $('#addRoom').val();
        var building = $('#addBuilding').val();
        var limit = $('#addLimit').val();
        if(room == '' || building == '' || limit == ''){
            $("#enterFields").modal("toggle");
        }
        else if(limit < 0 || room < 0 || building < 0){
            $('#addRoomError').empty();
            $('#addRoomError').append("Invalid input. All numbers must be positive");
        }
        else{
            $.ajax({
                url: 'HostelPageAction.php',
                type: 'POST',
                data:{'action':'add', 'room':room, 'building':building, 'limit':limit},
                success: function (data) {
                    if(data == "success"){
                        location.reload(true);
                    }
                    else if(data == "exists"){
                        $('#addRoomError').empty();
                        $('#addRoomError').append("A room with the no. " + room + " in building no." + building + " already exists");
                    }
                    else{
                        $("#notSuccess").modal("toggle");
                    }
                }
            });
        }
    });

    /*Delete room*/
    $("#deleteAction").click(function() {
        var roomDetails = $("input[name='room']:checked").val();
        if(roomDetails != undefined){
            var array = roomDetails.split(',');
            $("#deleteFormBody").empty();
            $("#deleteFormBody").append("Delete room "+ array[0] +" in building " + array[1] + "?");
            $("#deleteForm").modal("toggle");
        }
    });

    $("#deleteBtn").click(function() {
        var roomDetails = $("input[name='room']:checked").val();
        var array = roomDetails.split(',');
        $.ajax({
            url: 'HostelPageAction.php',
            type: 'POST',
            data:{'action':'delete', 'room':array[0], 'building':array[1]},
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