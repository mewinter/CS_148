<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";?>

<ol><li>q01.<a href = "q01.php">SQL:</a>SELECT fldFirstName, fldLastName, fldPhone FROM tblTeachers</li>
    <li>q02.<a href = "q02.php">SQL:</a>SELECT fldCourseName FROM tblCourses WHERE fldCourseName like "Introduction%"</li>
    <li>q03.<a href="q03.php">SQL:</a>SELECT * FROM tblSections WHERE time(fldStart) = time("13:10:00") AND fldBuilding like "KALKIN%" </li>
    <li>q04.<a href="q04.php">SQL:</a>SELECT * FROM tblCourses WHERE fldCourseName LIKE "Database Design for the Web" </li>
    <li>q05.<a href="q05.php">SQL:</a>SELECT fldFirstName, fldLastName FROM tblTeachers WHERE pmkNetID LIKE "r%o" </li>
    <li>q06.<a href="q06.php">SQL:</a>SELECT fldCourseName FROM tblCourses WHERE fldCourseName LIKE "%data%" AND NOT fldDepartment LIKE "cs" </li>
    <li>q07.<a href="q07.php">SQL:</a>SELECT DISTINCT fldDepartment FROM tblCourses'</li>
    <li>q08.<a href="q08.php">SQL:</a>SELECT fldBuilding, COUNT(fldSection) FROM tblSections GROUP BY fldBuilding </li>
    <li>q09.<a href="q09.php">SQL:</a>SELECT fldBuilding, fldNumStudents FROM tblSections WHERE fldDays LIKE "%W%" GROUP BY fldBuilding ORDER BY fldNumStudents DESC </li>
    <li>q010.<a href="q10.php">SQL:</a>SELECT fldBuilding, fldNumStudents FROM tblSections WHERE fldDays LIKE "%F%" GROUP BY fldBuilding ORDER BY fldNumStudents DESC </li>
    <li>q11.<a href="q11.php">SQL:</a>SELECT </li>
    <li>q12.<a href="q12.php">SQL:</a>SELECT </li>    
</ol >
       

<?php
include "footer.php"; ?>
