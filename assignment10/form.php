<?php
/* the purpose of this page is to display a form to allow a user and allow us
 * to add a new user or update an existing user 
 * 
 * Written By: Meaghan Winter

 */

include "top.php";

$query1 = "SELECT fldGenre, pmkMovieId FROM tblMovies GROUP BY fldGenre";
$genres = $thisDatabaseReader->testquery($query1, "", 0, 0, 0, 0, false, false);
$genres = $thisDatabaseReader->select($query1, "", 0, 0, 0, 0, false, false);

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
 ?>