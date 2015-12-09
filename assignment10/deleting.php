<?php

include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//$update = false;

//$_SERVER["REMOTE_USER"]="ptimsin1";
// SECTION: 1a.
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form

if (isset($_GET["id"])) {
    $pmkUserId = (int) htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

    
    //  you can se;ect the record and display teh info an way you want but it should not be in a form
    
//    $query = 'SELECT fldTitle, fldPost ';
//    $query .= 'FROM tblPost WHERE pmkUsername = ?';
//
//    $results = $thisDatabase->select($query, array($pmkUsername), 1, 0, 0, 0, false, false);
//
}

print "<p>why";
if (isset($_POST["btnDelete"])) {
    print"<p>maybe";
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2a Security
//
    /*    if (!securityCheck(true)) {
      $msg = "<p>Sorry you cannot access this page. ";
      $msg.= "Security breach detected and reported</p>";
      die($msg);
      }
     */
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.
$pmkUserId = (int) htmlentities($_POST["hidUserId"], ENT_QUOTES, "UTF-8"); 
print "<p> id " . $pmkUserId ;
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2e Save Data
//

        $dataEntered = false;
        try {
            $thisDatabaseWriter->db->beginTransaction();

                $query = 'DELETE FROM tblUserInfo where pmkUserId = ?';
                $data = array($pmkUserId);
                print "<p> sql " . $query ; 
                    $results = $thisDatabaseWriter->testquery($query,$data, 1, 0, 0, 0, false, false);
                    $results = $thisDatabaseWriter->delete($query,$data, 1, 0, 0, 0, false, false);
                    
            
                $dataEntered = $thisDatabaseWriter->db->commit();
            //} else {
              //  $thisDatabase->db->rollback();
           // }
            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabaseWriter->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accepting your data please contact us directly.";
        }
} // ends if form was Delete.
//#############################################################################
//
// SECTION 3 Display Form
//
?>
<article id="main">
<?php
//####################################
//
// SECTION 3a.
//
//
//
//
// If its the first time coming to the form or there are errors we are going
// to display the form.
if ($dataEntered) { // closing of if marked with: end body Delete
    
    print "<h1>Record Deleted.</h1> ";
} else {
//####################################
    ?>
        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">
            <fieldset class="wrapper">
                <h3>Delete Record?</h3>

                <input type="hidden" id="hidUserId" name="hidUserId"
                       value="<?php print $pmkUserId; ?>"
                       >
                <input type="Submit" id="btnDelete" name="btnDelete" value="Delete" tabindex="900" class="button">
            </fieldset> <!-- Ends Wrapper -->
        </form>
    <?php
} // end body Delete
?>
</article>

    <?php
   
    include "footer.php";
    if ($debug)
        print "<p>END OF PROCESSING</p>";
    ?>
</article>
</body>
</html>