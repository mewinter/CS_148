<!-- ######################     Main Navigation   ########################## -->
<br />
<hr />
<nav class="navbar navbar-default">
    <ol>
        <?php
        // This sets the current page to not be a link. Repeat this if block for
        //  each menu item 
        if ($path_parts['filename'] == "index") {
            print '<li class="active">Home</li>';
        } else {
            print '<li><a href="index.php">Home</a></li>';
        }
        
        //Detailed Show Time
        if ($path_parts['filename'] == "Current Show Times") {
            print '<li class="activePage">Current Show Times</li>';
        } else {
            print '<li><a href="currentMovieSchedule.php">Current Show Times</a></li>';
        }
        
        if ($path_parts['filename'] == "descriptions") {
            print '<li class="activePage">Movie Descriptions</li>';
        } else {
            print '<li><a href="descriptions.php">Movie Descriptions</a></li>';
        }
        
        //About 
        if ($path_parts['filename'] == "about") {
            print '<li class="activePage">About</li>';
        } else {
            print '<li><a href="about.php">About</a></li>';
        }
        
        if ($path_parts['filename'] == "suggestions") {
            print '<li class="activePage">Suggestions</li>';
        } else {
            print '<li><a href="suggestions.php">Suggestions</a></li>';
        }
          //Suggestions
        if ($path_parts['filename'] == "Suggestions Listed") {
            print '<li class="activePage">Suggestions Listed</li>';
        } else {
            print '<li><a href="post.php">Suggestions List</a></li>';
        }
        if ($path_parts['filename'] == "upcoming") {
            print '<li class="activePage">Upcoming Movies</li>';
        } else {
            print '<li><a href="upcoming.php">Upcoming Movies</a></li>';
        }
        
         //Employment 
        if ($path_parts['filename'] == "Employment") {
            print '<li class="activePage">Employment</li>';
        } else {
            print '<li><a href="employment.php">Employment</a></li>';
        }
        
        
        
//        if ($path_parts['filename'] == "populate-table.php") {
//            print '<li class="activePage">Populate Tables</li>';
//        } else {
//            print '<li><a href="populate-table.php">Populate Tables</a></li>';
//        }
        
        ?>
    </ol>
</nav>
<hr />
<!-- #################### Ends Main Navigation    ########################## -->
