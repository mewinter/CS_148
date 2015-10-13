<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";?>

<article><ol><li>q01.<a href = "q01.php">SQL:</a>SELECT DISTINCT fldCourseName FROM tblCourses, tblEnrolls WHERE tblCourses.pmkCourseId = tblEnrolls.fnkCourseId AND tblEnrolls.fldGrade = 100 ORDER BY tblCourses.fldCourseName</li>
    <li>q02.<a href = "q02.php">SQL:</a>SELECT DISTINCT fldDays, fldStart, fldStop FROM tblSections JOIN tblTeachers ON tblSections.fnkTeacherNetId = tblTeachers.pmkNetID WHERE tblTeachers.fldLastName = "Snapp" AND tblTeachers.fldFirstName = "Robert Raymond" ORDER BY tblSections.fldStart</li>
    <li>q03.<a href="q03.php">SQL:</a>SELECT DISTINCT fldDays, fldStart, fldStop FROM tblSections JOIN tblTeachers ON tblSections.fnkTeacherNetId = tblTeachers.pmkNetId WHERE tblTeachers.pmkNetId = "jlhorton" ORDER BY tblSections.fldStart </li>
    <li>q04.<a href="q04.php">SQL:</a>SELECT fnkSectionId, fldFirstName, fldLastName FROM tblEnrolls JOIN tblStudents on tblEnrolls.fnkStudentId = tblStudents.pmkStudentId WHERE tblEnrolls.fnkCourseId = 392 ORDER BY tblEnrolls.fnkSectionId, fldLastName, fldFirstName</li>
    <li>q05.<a href="q05.php">SQL:</a>SELECT tblTeachers.fldFirstName, tblTeachers.fldLastName,  count(tblStudents.fldFirstName) as total FROM tblSections JOIN tblEnrolls on tblSections.fldCRN  = tblEnrolls.`fnkSectionId` JOIN tblStudents on pmkStudentId = fnkStudentId JOIN tblTeachers on tblSections.fnkTeacherNetId=pmkNetId WHERE fldType != "LAB" group by fnkTeacherNetId ORDER BY total desc</li>
    <li>q06.<a href="q06.php">SQL:</a>SELECT fldFirstName, fldPhone, fldSalary FROM tblTeachers WHERE fldSalary < (SELECT AVG(fldSalary) FROM tblTeachers) </li>
    <li>q07.<a href="q07.php">SQL:</a> </li>
    <li>q08.<a href="q08.php">SQL:</a> </li>  
    </ol></article>
       

<?php
include "footer.php"; ?>
