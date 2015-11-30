<?php

$query = "SELECT fldMovieGenres";
$query .= "FROM tblUserInfo ";

// Step Three: code can be in initialize variables or where step four needs to be
// $buildings is an associative array
$genres = $thisDatabase->testquery($query, "", 0, 1, 0, 0, false, false);
$genres = $thisDatabase->select($query, "", 0, 1, 0, 0, false, false);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h1>This is my trial page</h1>
