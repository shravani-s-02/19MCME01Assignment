$(document).ready(function(){
  //To add the buttons to the page
    $.ajax({ 
        url:"../Website/Permissions.php",
        success: function(data) {
          var array = jQuery.parseJSON(data);
          $('#ID').text(array.ID);
          $('#Name').text(array.Name);
          if(array.LibPerm != "N"){            
              var button_added = $('<a href="../Library/LibPage.html" class="btn btn-outline-primary btn-square-md" role="button">Library</a>');
              $("#buttons").append(button_added);
          }
          if(array.HostelPerm != "N"){            
            var button_added = $('<a href="../Hostel/HostelPage.html" class="btn btn-outline-primary btn-square-md" role="button">Hostel</a>');
            $("#buttons").append(button_added);
          }
          if(array.OnlineTutorialsPerm != "N"){            
            var button_added = $('<a href="../OnlineTutorials/TutorialPage.html" class="btn btn-outline-primary btn-square-md" role="button">Tutorials</a>');
            $("#buttons").append(button_added);
          } 
        }
    });
});