<?php
/* the purpose of this page is to display a form to allow a user and allow us
 * to add a new user or update an existing user 
 * 
 * Written By: Meaghan Winter

 */

include "top.php";
?>

<div id="header">
    <h1>Current Movie Schedule</h1>
</div>

<?php
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
$debug = true;
$update = false;

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
//if (isset($_GET["id"])) {
//    $pmkUserId = (int) htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

    $queryinfo = 'SELECT fldFirstName, fldLastName, fldBirthDate, fldEmail, fnkGenre, fldFrequency ';
    $queryinfo .= 'FROM tblUserInfo '
            . 'WHERE pmkUserId = ?';
    $resultsInfo = $thisDatabaseWriter->select($queryinfo, array($pmkUserId), 1, 0, 0, 0, false, false);

    $querypick = 'SELECT fldMoviePick, fnkUserId FROM tblUserPicks WHERE fnkUserId = ?';
    $resultsPick = $thisDatabaseWriter->select($querypick, array($pmkUserId), 1, 0, 0, 0, false, false);

    $queryMovie = "SELECT pmkMovieId, lstTitle, fldStatus ";
    $queryMovie .= "FROM tblMovies WHERE fldStatus = 'Upcoming' ORDER BY lstTitle";
    $movies = $thisDatabaseReader->select($queryMovie, "", 1, 1, 2, 0, false, false);

    $pmkUserId = $resultsPick [0]['fnkUserId'];
    $firstName = $resultsInfo[0]["fldFirstName"];
    $lastName = $resultsInfo[0]["fldLastName"];
    $birthday = $resultsInfo[0]["fldBirthDate"];
    $email = $resultsInfo[0]["fldEmail"];
    $genresList = $resultsInfo[0]["fnkGenre"];

    $movie = $resultsPick[0]['lstTitle'];

    $frequency = $resultsInfo[0]["fldFrequency"];

//// query for genre initialization
//$query1 = "SELECT fldGenre, pmkMovieId FROM tblMovies GROUP BY fldGenre";
////$query1 .= "FROM tblMovies ";
////$query1 .= "GROUP BY fldGenre ";
//// Step Three: code can be in initialize variables or where step four needs to be
//// $buildings is an associative array
//$genres = $thisDatabaseReader->select($query1, "", 0, 0, 0, 0, false, false);
//
//query for movie pick initialization 
if ($debug) {
    print '<p> initialize genres</p>';
} else {
    $pmkUserId = -1;
    $firstName = "";
    $lastName = "";
    $birthday = "";
    $email = "";
    $action = true;
    $comedy = false;
    $drama = false;
    $romance = false;
    $adventure = false;
//    $genres = "";
    $movie = '';
    $frequency = '';
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$firstNameERROR = false;
$lastNameERROR = false;
$birthdayERROR = false;
$emailERROR = false;
$actionERROR = false;
$comedyERROR = false;
$dramaERROR = false;
$romanceERROR = false;
$adventureERROR = false;

//$genresERROR = false;
$movieERROR = false;
$frequencyERROR = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
$data = array();
$dataEntered = false;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
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
    $dataInfo[] = $pmkUserId;
    $dataPick[] = $pmkUserId;
    print $pmkUserId;
    // I am not putting the ID in the $data array at this time

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataInfo[] = $firstName;

    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $dataInfo[] = $lastName;

    $birthday = htmlentities($_POST["txtBirthday"], ENT_QUOTES, "UTF-8");
    $dataInfo[] = $birthday;

    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL, 'UTF-8');
    $dataInfo[] = $email;

//    $genres = filter_var($_POST["chkGenres"], ENT_QUOTES, 'UTF-8');

    if (isset($_POST["chkAction"])) {
        $chkAction = 'Action';
    } else {
        $chkAction = '';
    }

    if (isset($_POST["chkComedy"])) {
        $chkComedy = 'Comedy';
    } else {
        $chkComedy = '';
    }

    if (isset($_POST["chkDrama"])) {
        $chkDrama = 'Drama';
    } else {
        $chkDrama = '';
    }

    if (isset($_POST["chkRomance"])) {
        $chkRomance = 'Romance';
    } else {
        $chkRomance = '';
    }

    if (isset($_POST["chkAdventure"])) {
        $chkAdventure = 'Adventure';
    } else {
        $chkAdventure = '';
    }

    $genres = $chkAction;
    $genres .= $chkComedy;
    $genres .= $chkDrama;
    $genres .= $chkRomance;
    $genres .= $chkAdventure;

    print $genres;
    $dataInfo[] = $genres;

    $movie = htmlentities($_POST["lstTitle"], ENT_QUOTES, 'UTF-8');
    $dataPick[] = $movie;

    $frequency = htmlentities($_POST["radFrequency"], ENT_QUOTES, 'UTF-8');
    $dataInfo[] = $frequency;
print '<p>test ' . $frequency;


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c Validation
//

    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have extra character.";
        $firstNameERROR = true;
    }

    if ($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to have extra character.";
        $lastNameERROR = true;
    }

    if ($birthday == "") {
        $errorMsg[] = "Please enter your birthday";
        $birthdayERROR = true;
    }// should check to make sure its the correct date format
    //
//email checking
    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2d Process Form - Passed Validation
//
// Process for when the form passes validation (the errorMsg array is empty)
//

    if (!$errorMsg) {
        if ($debug) {
            print '<p> 2d';
            print "<p>Form is valid</p>";
        }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2e Save Data
        if ($debug) {
            print '<p> 2e';
        }

        $dataEntered = false;
        try {
            $thisDatabaseWriter->db->beginTransaction();

            $queryInfo = 'INSERT INTO tblUserInfo SET pmkUserId = ?, fldFirstName = ?, fldLastName = ?, fldBirthDate = ?, '
                    . 'fldEmail = ?, fnkGenre = ?, fldFrequency = ? ';

            //     if ($_SERVER["REMOTE_USER"] == 'mewinter') {
            $resultsInfo = $thisDatabaseWriter->insert($queryInfo, $dataInfo);
            $pmkUserId = $thisDatabaseWriter->lastInsert();
            if ($debug) {
                print "<p>pmk= " . $pmkUserId;
            }

            $queryPick = 'INSERT INTO tblUserPicks SET ';
            $queryPick .= 'fnkUserId = ? ,';
            $queryPick .= 'fldMoviePick = ? ';
            // $dataPick[] = $primaryKey;

            $resultsPick = $thisDatabaseWriter->insert($queryPick, $dataPick);
            // delete this line, i dont think you need it (85% sure anyway) $primaryKey = $thisDatabaseWriter->lastInsert();
//            $queryPick = 'INSERT INTO tblUserPicks SET ';
//            $queryPick .= 'fldMoviePick = ? ,';
//            $queryPick .= 'fnkUserId = ? ';
//
//            $resultsPick = $thisDatabaseWriter->insert($queryPick, $dataPick);
//            $primaryKey = $thisDatabaseWriter->lastInsert();
//            if ($debug) {
//                print "<p>pmk= " . $primaryKey;
//            }
            $dataEntered = $thisDatabaseWriter->db->commit();

            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabaseWriter->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accpeting your data please contact us directly.";
        }

        $message = '<h2>Here is the information you submitted:</h2>';

        foreach ($_POST as $key => $value) {
            $message .= "<p>";
            $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));
            foreach ($camelCase as $one) {
                $message .= $one . " ";
            }
            $message .= " : " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
        }
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2g Mail to user
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email; // the person who filled out the form
        $cc = "";
        $bcc = "";
        $from = "MoviePix <contact@movies.com>";

        // subject of mail should make sense to your form
        $todaysDate = strftime("%x");
        $subject = "Learn more about MoviePix: " . $todaysDate;

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // end form is valid
} // ends if form was submitted.
//#############################################################################
//
// SECTION 3 Display Form
//
?>
<article id="main">
<?php
//####################################
// SECTION 3a.
// If its the first time coming to the form or there are errors we are going
// to display the form.
if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
    print "<h1>Your Request has ";
    if (!$mailed) {
        print "not ";
    }
    print "been processed</h1>";
    print "<p>A copy of this message has ";
    if (!$mailed) {
        print "not ";
    }
    print "been sent</p>";
    print "<p>To: " . $email . "</p>";
    print "<p>Mail Message:</p>";
    print $message;
} else {
//####################################
//
// SECTION 3b Error Messages
//
// display any error messages before we print out the form
    if ($errorMsg) {
        print '<div id="errors">';
        print '<h1>Your form has the following mistakes</h1>';

        print "<ol>\n";
        foreach ($errorMsg as $err) {
            print "<li>" . $err . "</li>\n";
        }
        print "</ol>\n";
        print '</div>';
    }
//####################################
//
// SECTION 3c html Form
//
    /* Display the HTML form. note that the action is to this same page. $phpSelf
      is defined in top.php
      NOTE the line:
      value="<?php print $email; ?>
      this makes the form sticky by displaying either the initial default value (line 35)
      or the value they typed in (line 84)
      NOTE this line:
      <?php if($emailERROR) print 'class="mistake"'; ?>
      this prints out a css class so that we can highlight the background etc. to
      make it stand out that a mistake happened here.
     */
    ?>
        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">
            <fieldset>
                <!--class="wrapper">-->
                <legend><h2>User Information</h2></legend>

                    <input type="hidden" id="hidUserId" name="hidUserId"
                           value="
    <?php print $pmkUserId; ?>
    "
                           >

                <label for="txtFirstName" class="required">First Name
                    <input type="text" id="txtFirstName" name="txtFirstName"
                           value="<?php print $firstName; ?>"
                           tabindex="100" maxlength="45" placeholder="Enter your first name"
    <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           autofocus>
                </label>

                <label for="txtLastName" class="required">Last Name
                    <input type="text" id="txtLastName" name="txtLastName"
                           value="<?php print $lastName; ?>"
                           tabindex="100" maxlength="45" placeholder="Enter your last name"
    <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           >
                </label>

                <label for="txtBirthday" class="required">Birthday
                    <input type="text" id="txtBirthday" name="txtBirthday"
                           value="<?php print $birthday; ?>"
                           tabindex="100" maxlength="45" placeholder="YYYY-MM-DD"
    <?php if ($birthdayERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           >
                </label>  

                <label for="txtEmail" class="required">Email
                    <input type="text" id="txtEmail" name="txtEmail"
                           value="<?php print $email; ?>"
                           tabindex="120" maxlength="45" placeholder="Enter a valid email address"
    <?php if ($emailERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()" 
                           autofocus>
                </label>


            </fieldset> <!-- ends contact -->


            <fieldset class="checkbox">
                <legend>Which movie genres do you like (check all that apply):</legend>
                <label><input type="checkbox" 
                              id="chkAction" 
                              name="chkAction" 
                              value="Action"
    <?php if ($action) print ' checked '; ?>
                              tabindex="180"> Action</label>
                <label><input type="checkbox" 
                              id="chkComedy" 
                              name="chkComedy" 
                              value="Comedy"
    <?php if ($comedy) print ' checked '; ?>
                              tabindex="190"> Comedy</label>
                <label><input type="checkbox" 
                              id="chkDrama" 
                              name="chkDrama" 
                              value="Drama"
    <?php if ($drama) print ' checked '; ?>
                              tabindex="200"> Drama</label>
                <label><input type="checkbox" 
                              id="chkRomance" 
                              name="chkRomance" 
                              value="Romance"
    <?php if ($romance) print ' checked '; ?>
                              tabindex="210"> Romance</label> 
                <label><input type="checkbox" 
                              id="chkAdventure" 
                              name="chkAdventure" 
                              value="Adventure"
    <?php if ($Adventure) print ' checked '; ?>
                              tabindex="220"> Adventure</label>
            </fieldset> <!-- ends wrapper Two -->

            <!----------------- -- MOVIE PICK ------------------------------------------------>

            <label for="lstTitle"><legend><h2>Upcoming Movie Pick</h2></legend> 
                <select id="lstTitle" name="lstTitle" tabindex="300">;
    <?php
    foreach ($movies as $row) {

        print '<option ';
        if ($movie == $row["lstTitle"])
            print " selected= 'selected' ";
        print 'value="' . $row["lstTitle"] . '">' . $row["lstTitle"];

        print '</option>';
    }

    print '</select></label>';
    ?>
                    <!----------------- -- END MOVIE PICK------------------------------------------------>

                    <!----------------- -- EMAIL FREQUENCY ------------------------------------------------>

                    <fieldset class="radio">
                        <legend><h2>Please indicate your Email Frequency:</h2></legend>

                        <label for="radWeekly">
                            <input type="radio" 
                                   id="radWeekly" 
                                   name="radFrequency" 
                                   value="Weekly">Weekly
                        </label>

                        <label for="radMonthly">
                            <input type="radio" 
                                   id="radMonthly" 
                                   name="radFrequency" 
                                   value="Monthly">Monthly
                        </label>

                        <label for="radNever">
                            <input type="radio" 
                                   id="radNever" 
                                   name="radFrequency" 
                                   value="Never">Never
                        </label>

                        <!----------------- -- END EMAIL FREQUENCY ------------------------------------------------>
                    </fieldset>


                    </fieldset> <!-- ends wrapper Two -->
                    <fieldset class="buttons">
                        <legend></legend>
                        <input type="submit" id="btnSubmit" name="btnSubmit" value="Save" tabindex="900" class="button">
                    </fieldset> <!-- ends buttons -->
                    </fieldset> <!-- Ends Wrapper -->
                    </form>
    <?php
} // end body submit
?>
                </article>

                <?php
                include "footer.php";
                if ($debug)
                    print "<p>END OF PROCESSING</p>";
                ?>
