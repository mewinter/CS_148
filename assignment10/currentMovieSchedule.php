<!DOCTYPE html>


<?php
include "top.php";
?>
<body>  

<div id="header">
<h1>Current Movie Schedule</h1>
</div>

    <?php
     print '<table>';
    //now print out each record
    $query = 'select distinct pmkTimeStart, fldTitle, fldRating, fldLength from tblSchedule inner join tblMovies on pmkMovieId where pmkMovieId = fnkMovieId'; 
    //$info2 = $thisDatabaseReader->testquery($query, "", 1, 0, 0, 0, false, false);
    $info2 = $thisDatabaseReader->select($query, "", 1, 0, 0, 0, false, false);
    
    $columns = 4;
    
   $headerFields = array_keys($info2[0]);
   $headerArray = array_filter($headerFields, "is_string");
    
   // echo "<h2> Records: " . count($info2) . "</h2>";
    print '<table>';
    //header block
    print '<tr class="tblHeaders">';
    foreach ($headerArray as $key) {
        $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));
        $message = "";
        foreach ($camelCase as $one) {
            $message .= $one . " ";
        }
        print '<th>' . $message . '</th>';
    }
    print '</tr>';
   // print '<table>';
    $highlight = 0; // used to highlight alternate rows
   // print '<p>Total Records:'. count($info2). '</p>';
    print '<p>SQL'. $query. '</p>';
    
    $variable = array_keys($array-variable);
    foreach ($info2 as $rec) {
        $highlight++;
        if ($highlight % 2 != 0) {
            $style = ' odd ';
        } else {
            $style = ' even ';
        }
        print '<tr class="' . $style . '">';
        for ($i = 0; $i < $columns; $i++) { 
            print '<td>' . $rec[$i] . '</td>';
        }
        print '</tr>';
    }
    // all done
    print '</table>';
    print '</aside>';
print '</article>';
?>
 <div id="footer">
<?php
include "footer.php";
?>
 </div>

</body>
</html>