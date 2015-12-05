<?php
//##############################################################################
//
// This page lists your tables and fields within your database. if you click on
// a database name it will show you all the records for that table. 
// 
// 
// This file is only for class purposes and should never be publicly live
//##############################################################################
include "top.php";
?>

<div id="header">
<h1>Digi Pix Home Page</h1>
</div>

<?php   
    //print '<table>';
//now print out each record
$columns = 2; 
$query = 'SELECT fldPicture, fldDescription FROM tblMovies';
//$info2 = $thisDatabaseReader->testquery($query, "", 0, 0, 0, 0, false, false);
$queryDescription = $thisDatabaseReader->select($query, "", 0, 0, 0, 0, false, false);
//print '<tr>';
foreach ($queryDescription as $rec) {
    
    print '<span class="description"><img class="imgdescription" src="' . $rec['fldPicture'] . '">';
    print '<p class="txtdescription"><b>Description:</b> ' . $rec['fldDescription'] . '</p></span>';
    
}
//print '</tr>';
//print '</table>';
?>


<div id="footer">
<?php
include "footer.php";
?>
 </div>