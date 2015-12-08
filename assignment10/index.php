<?php
//##############################################################################
//
// main home page for the site 
// 
//##############################################################################
include "top.php";
?>

<div id="header">
<h1>Digi Pix Home Page</h1>
</div>

<?php
// Begin output
//maddie's stuff
$columns = 2; 
$query = 'SELECT fldPicture, fldDescription FROM tblMovies';
//$info2 = $thisDatabaseReader->testquery($query, "", 0, 0, 0, 0, false, false);
$queryDescription = $thisDatabaseReader->select($query, "", 0, 0, 0, 0, false, false);
print '<div id="accordion">';
foreach ($queryDescription as $rec) {
    print '<div id="' . $rec['fldMovieId'] . '">';
    print '<img class="accordion" src="' . $rec['fldPicture'] . '">';
    print '</div>';
}
print '</div>';
//end maddie's stuff
print '<p class="container">Welcome to DigiPix Movie Theatre! '
. 'Please browse our Movie selection(current and upcoming). '
        . 'Also make sure to become a member and <a href="suggestions.php">vote</a> for which movie you would '
        . 'like us to play next in our theatre!  </p>';
?>

<div id="footer">
<?php
include "footer.php";
?>
 </div>