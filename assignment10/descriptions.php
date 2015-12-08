<?php

include "top.php";
print '<div id="header">';
print '<h1>Movie Descriptions</h1>';
print '</div>';
print '<br />';
//print '<div class="description">';
//print '<table>';

//now print out each record
$columns = 2; 
$query = 'SELECT fldPicture, fldDescription, fldRating, fldLength, lstTitle FROM tblMovies';
//$info2 = $thisDatabaseReader->testquery($query, "", 0, 0, 0, 0, false, false);
$queryDescription = $thisDatabaseReader->select($query, "", 0, 0, 0, 0, false, false);

//print '<tr>';
print '<div class="container-fluid">';
print '<div class="row">';
foreach ($queryDescription as $rec) {
    
    print '<div class="col-md-3"><img src="' . $rec['fldPicture'] . '">';
    print '<p><b>Title:</b> ' . $rec['lstTitle'];
    print '<br />';
    print '<b>Description:</b> ' . $rec['fldDescription'];
    print '<br />';
    print '<b>Rating:</b> ' . $rec['fldRating'];
    print '<br />';
    print '<b>Length:</b> ' .$rec['fldLength'];
    print '</p><br /><br /><br /><br /></div>'; 
}
print '</div>';
//print '<br />';
//print '<br />';
print '</div>';
//print '</div>';<br />
//print '</table>';

include "footer.php";
?>




