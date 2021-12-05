$(document).ready(function(){
    var embedPattern = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    $.ajax({ 
        url:'SubjectPageAction.php',
        type: 'POST',
        data:{'action':'pageContent'},
        success: function(data) {
            var array = jQuery.parseJSON(data);
            $("#posts").append(array.content);
            $('title').html(array.title);
        }
    });

    $.ajax({
        url: '../Website/Permissions.php',
        success: function(data) {
            var array = jQuery.parseJSON(data);
            if(array.OnlineTutorialsPerm == "R"){            
                $("#buttons").addClass("invisible");
            }
        }
    });

    $(".selectionRequireAction").click(function() {
        var id = $("input[name='post']:checked").val();
        if(id == undefined){
            $("#select").modal("toggle");
        }
    });

    /*Edit post*/
    $("#editAction").click(function() {
        var id = $("input[name='post']:checked").val();
        if(id != undefined){
            $.ajax({
                url: 'SubjectPageAction.php',
                type: 'POST',
                data:{'action':'editForm', 'id':id},
                success: function (data) {
                    var array = jQuery.parseJSON(data);
                    $('#editTitle').val(array.PostTitle);
                    $('#editDesc').val(array.PostDesc);
                    $('#editEmbed').val(array.PostEmbed);
                }
            });
            $("#editForm").modal("toggle");
        }
    });

    $("#editBtn").click(function() {
        var id = $("input[name='post']:checked").val();
        var title = $('#editTitle').val();
        var desc = $('#editDesc').val();
        var embed = $('#editEmbed').val();
        var error = 0;
        if(title == '' ){
            $('#enterFields').modal("toggle");
            error++;
        }
        if(title.length > 150){
            $('#editTitleError').empty();
            $('#editTitleError').append('Number of chacters in the title exceeds 150');
            error++;
        }
        else{
            $('#editTitleError').empty();
        }

        if(desc.length > 500){
            $('#editDescError').empty();
            $('#editDescError').append('Number of chacters in the description exceeds 500');
            error++;
        }
        else{
            $('#editDescError').empty();
        }

        if(!embed.match(embedPattern) && embed != ''){
            $('#editEmbedError').empty();
            $('#editEmbedError').append('The link is not in the embedable format');
            error++;
        }
        else{
            $('#editEmbedError').empty();
        }

        if(error == 0){
            $.ajax({
                url: 'SubjectPageAction.php',
                type: 'POST',
                data:{'action':'edit', 'id':id, 'title':title, 'desc':desc, 'embed':embed},
                success: function (data) {
                    $('body').append(data);
                    if(data == "success"){
                        location.reload(true);
                    }
                    else{
                        $("#editForm").modal("toggle");
                        $("#notSuccess").modal("toggle");
                    }
                }
            });
        }
    });

    /*Add post*/
    $("#addAction").click(function() {
        $("#addForm").modal("toggle");
    });

    $("#addBtn").click(function() {
        var title = $('#addTitle').val();
        var desc = $('#addDesc').val();
        var embed = $('#addEmbed').val();
        var error = 0;
        if(title == '' ){
            $('#enterFields').modal("toggle");
            error++;
        }
        if(title.length > 150){
            $('#addTitleError').empty();
            $('#editTitleError').append('Number of chacters in the title exceeds 150');
            error++;
        }
        else{
            $('#addTitleError').empty();
        }

        if(desc.length > 500){
            $('#addDescError').empty();
            $('#addDescError').append('Number of chacters in the description exceeds 500');
            error++;
        }
        else{
            $('#addDescError').empty();
        }

        if(!embed.match(embedPattern) && embed != ''){
            $('#addEmbedError').empty();
            $('#addEmbedError').append('The link is not in the embedable format');
            error++;
        }
        else{
            $('#addEmbedError').empty();
        }

        if(error == 0){
            $.ajax({
                url: 'SubjectPageAction.php',
                type: 'POST',
                data:{'action':'add', 'title':title, 'desc':desc, 'embed':embed},
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

    /*Delete post*/
    $("#deleteAction").click(function() {
        var id = $("input[name='post']:checked").val();
        if(id != undefined){
            $("#deleteForm").modal("toggle");
        }
    });

    $("#deleteBtn").click(function() {
        var id = $("input[name='post']:checked").val();
        $.ajax({
            url: 'SubjectPageAction.php',
            type: 'POST',
            data:{'action':'delete', 'id':id},
            success: function (data) {
                if(data == "success"){
                    location.reload(true);
                }
                else{
                    $("#deleteForm").modal("toggle");
                    $("#notSuccess").modal("toggle");
                }
            }
        });
    });
});