<?php
include "top.php";
?>

<div id="header">
<h1>Upcoming</h1>
</div>

<?php
//now print out each record
$columns = 4; 
$query = "SELECT fldPicture, fldTitle, fldDescription, fldStatus FROM tblMovies where fldStatus='Upcoming'";
//$info2 = $thisDatabaseReader->testquery($query, "", 0, 0, 0, 0, false, false);
$queryDescription = $thisDatabaseReader->select($query, "", 1, 0, 2, 0, false, false);
foreach ($queryDescription as $rec) {
    //print '<tr>';
    print '<span><img class="imgdescription" src="' . $rec['fldPicture'] . '">';
    print '<p class="txtdescription">' . $rec['fldTitle'] . '</p></span>';
    
}
?>

<div id="footer">
<?php
include "footer.php";
?>
 </div>