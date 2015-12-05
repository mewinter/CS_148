<?php
/* the purpose of this page is to display a form to allow a user and allow us
 * to add a new user or update an existing user 
 * 
 * Written By: Meaghan Winter

 */

include "top.php";

$query2 = "SELECT pmkMovieId, fldTitle, fldStatus ";
$query2 .= "FROM tblMovies ";
$query2 .= "WHERE fldStatus = 'Upcoming' ";
$query2 .= "ORDER BY fldTitle";
$movies = $thisDatabaseReader->testquery($query2, "", 1, 1, 2, 0, false, false);
$movies = $thisDatabaseReader->select($query2, "", 1, 1, 2, 0, false, false);

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
 ?>