$(document).ready(function(){
    $.ajax({ 
        url: "../Website/WebsitePHP.php",
        success: function(data){
            if(data == "NotSignedIn"){            
                location.href = '../Index.php';    
            }
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
                var nav_item_add = $('<li class="nav-item"><a class="nav-link" id="hostelNav" href="../Hostel/HostelPage.html">Hostel</a></li>');
                $("#permItems").append(nav_item_add);
            }
            if(array.OnlineTutorialsPerm != "N"){            
                var nav_item_add = $('<li class="nav-item"><a class="nav-link" id="tutorialNav" href="../OnlineTutorials/TutorialPage.html">Tutorials</a></li>');
                $("#permItems").append(nav_item_add);
            } 
        }
    });
});