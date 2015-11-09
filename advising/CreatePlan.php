<?php
include "top.php";

/* ##### Step one
*
* create your database object using the appropriate database username
*/

//require_once('../bin/myDatabase.php');
//
//$dbUserName = get_current_user() . '_reader';
//$whichPass = "r"; //flag for which one to use.
//$dbName = strtoupper(get_current_user()) . '_UVM_Courses';
//
//$thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);
//
//


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;
if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}
if ($debug)
    print "<p>DEBUG MODE IS ON</p>";
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
$NetId = "";
$Term = "Fall";
$Department = "ANFS";
$Course = 'ANFS 491';
$StudentId = "";
$AdvisorId = '';
$Year = '';

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$TermERROR = false;
$DepartmentERROR = false;
$CourseERROR = false;
$StudentIdERROR = false;
$AdvisorIdERROR = false;
$YearERROR = false;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
// array used to hold form values that will be written to a CSV file
$dataRecord = array();
$mailed = false; // have we mailed the information to the user?
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    // 
    if (!securityCheck(true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2b Sanitize (clean) data 
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.
    $Term = htmlentities($_POST["radTerm"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $Term;
    $Department = filter_var($_POST["txtDepartment"], ENT_QUOTES, 'UTF-8');
    $dataRecord[] = $Department;
    $Course = filter_var($_POST["txtCourse"], ENT_QUOTES, 'UTF-8');
    $dataRecord[] = $Course;
    $StudentId = filter_var($_POST["txtStudentId"], ENT_QUOTES, 'UTF-8');
    $dataRecord[] = $StudentId;
    $AdvisorId = filter_var($_POST["txtAdvisorId"], ENT_QUOTES, 'UTF-8');
    $dataRecord[] = $AdvisorId;
    $Year = filter_var($_POST["txtYear"], ENT_QUOTES, 'UTF-8');
    $dataRecord[] = $Year;

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2c Validation
    //
    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.
    if ($StudentId == "") {
        $errorMsg[] = "Please enter Student NetId";
        $StudentIdERROR = true;
    } elseif (!verifyAlphaNum($StudentId)) {
        $errorMsg[] = "Student netid appears to have extra character.";
        $StudentIdERROR = true;
    }

    if ($AdvisorId == "") {
        $errorMsg[] = "Please enter Advisor NetId";
        $AdvisorIdERROR = true;
    } elseif (!verifyAlphaNum($AdvisorId)) {
        $errorMsg[] = "Your first name appears to have extra character.";
        $AdvisorIdERROR = true;
    }

    if ($Year == "") {
        $errorMsg[] = "Please enter the Year of Class";
        $YearERROR = true;
    } elseif (!verifyAlphaNum($Year)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $YearERROR = true;
    }
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2d Process Form - Passed Validation
    //
// SECTION: 2d Process Form - Passed Validation
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //
    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";
        //dddddddddddddddddddddd
        //
        // SECTION: 2e Save Data
        //insert into tblplan
        $sql = "INSERT INTO tblFourYearPlan(fldMajor, fldMinor, fldCatalogYear, fnkStudentId, fnkAdvisorId) VALUES (?, ?, ?, ?, ?)";
        $data = array($major, $minor, $startYear, $studentNetId, $advisorNetId);
        print "<p>SQL 1: " . $sql;
        $plan = $thisDatabaseWriter->insert($sql, $data, 0, 0, 0, 0, false, false);
        $planId = $thisDatabaseWriter->lastInsert();
        // insert into semester plan

        $sql = "INSERT INTO tblSemesterPlan(pmkYear, pmkTerm, fldDisplayOrder, fnkPlanId) VALUES ";
        $semesterPlanData = array();
        $term = 1;
        $display = 1;
        for ($i = $startYear; $i < $startYear + 4; $i++) {
            if ($term != 1)
                $sql .= ", ";

// fall
            $sql .= "(?, ?, ?, ?), ";
            $semesterPlanData[] = $i;
            if ($term % 2) {
                $semesterPlanData[] = "Fall";
            } else {
                $semesterPlanData[] = "Spring";
            }
            $semesterPlanData[] = $display;
            $semesterPlanData[] = $planId;
            $term++;
            $display++;
// spring
            $sql .= "(?, ?, ?, ?) ";
            $semesterPlanData[] = $i + 1;
            if ($term % 2) {
                $semesterPlanData[] = "Fall";
            } else {
                $semesterPlanData[] = "Spring";
            }
            $semesterPlanData[] = $display;
            $semesterPlanData[] = $planId;
            $term++;
            $display++;
        }

        print "<p>SQL 2: " . $sql;
        print "<p>Data<pre>";
        print_r($semesterPlanData);
        print "</pre></p>";

        $semesterPlan = $thisDatabaseWriter->insert($sql, $semesterPlanData, 0, 0, 0, 0, false, false);

// insert into semester plan course pulling from default courses
        // how doi set the year to be correct
        //get year from plan
        $defaultPlanYear = 2015;

        $difference = $defaultPlanYear - $startYear;

        if ($difference > 0) {
            $difference = "-" . $difference;
        } elseif ($difference < 0) {
            $difference = "+" . ($difference * -1);
        } else {
            $difference = "";
        }
        $sql = "INSERT INTO tblSemesterPlanCourses(fnkPlanId, fnkYear, fnkTerm, fnkCourseId, fldDisplayOrder, fldRequirement) ";
        $sql .= "SELECT " . $planId . " as fnkPlanId, (fnkYear" . $difference . ") as fnkYear, fnkTerm, fnkCourseId, fldDisplayOrder, fldRequirement FROM tblDefaultPlanCourses WHERE fnkDefaultPlanId=" . $defaultPlanId . " ORDER BY tblDefaultPlanCourses.fnkYear ASC, fnkTerm, fldDisplayOrder";
        print "<p>SQL 3: " . $sql;
        $planCourses = $thisDatabaseWriter->insert($sql, "", 1, 1, 0, 0, false, false);


        die("<p>dig it: " . $planId . "</p>");
    } // end valid form
} // end defaul submit
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2e Save Data
        //
        // This block saves the data to a CSV file.
        $fileExt = ".csv";
        $myFileName = "data/registration";
        $filename = $myFileName . $fileExt;
        if ($debug)
            print "\n\n<p>filename is " . $filename;
        // now we just open the file for append
        $file = fopen($filename, 'a');
        // write the forms informations
        fputcsv($file, $dataRecord);
        // close the file
        fclose($file);
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2f Create message
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).
        $message = '<h2>Your information.</h2>';
        foreach ($_POST as $key => $value) {
            $message .= "<p>";
            $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));
            foreach ($camelCase as $one) {
                $message .= $one . " ";
            }
            $message .= " = " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
        }
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2g Mail to user
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $StudentId.'@uvm.edu'; // the person who filled out the form
        $cc = "";
        $bcc = "";
        $from = "WRONG site <noreply@yoursite.com>";
        // subject of mail should make sense to your form
        $todaysDate = strftime("%x");
        $subject = "Four Year Plan: " . $todaysDate;
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

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

            <fieldset class="wrapper">
                <legend>Create/Edit 4 Year Plan</legend>
                <p></p>

                <fieldset class="wrapperTwo">
                    <legend>Please complete the following form</legend>

                    <fieldset class="contact">
                        <legend>Contact Information</legend>
                        <label for="txtStudentId" class="required">Student Net Id
                            <input type="text" id="txtStudentId" name="txtStudentId"
                                   value="<?php print $StudentId; ?>"
                                   tabindex="100" maxlength="45" placeholder="Enter Student NetId"
                                   <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>

                        <label for="txtAdvisorId" class="required">Advisor Net Id
                            <input type="text" id="txtAdvisorId" name="txtAdvisorId"
                                   value="<?php print $StudentId; ?>"
                                   tabindex="100" maxlength="45" placeholder="Enter Advisor NetId"
                                   <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>
                        
                        
                        
                    </fieldset> <!-- ends contact -->
                    
                    <fieldset class="radio">
                        <legend>Term</legend>
                        <label><input type="radio" 
                                      id="radFall" 
                                      name="radTerm" 
                                      value="Fall"
                                      <?php if ($Term == "Fall") print 'checked' ?>
                                      tabindex="140">Fall</label>
                        <label><input type="radio" 
                                      id="radSpring" 
                                      name="radTerm" 
                                      value="Spring"
                                      <?php if ($Term == "Spring") print 'checked' ?>
                                      tabindex="150">Spring</label>
                        <label><input type="radio" 
                                      id="radWinter" 
                                      name="radTerm" 
                                      value="Winter"
                                      <?php if ($Term == "Winter") print 'checked' ?>
                                      tabindex="160">Winter</label>
                        <label><input type="radio" 
                                      id="radSummer" 
                                      name="radTerm" 
                                      value="Summer"
                                      <?php if ($Term == "Summer") print 'checked' ?>
                                      tabindex="170">Summer</label>
                    </fieldset> <!-- ends class year --> 
                    
                    <fieldset  class="listbox">	
                        <label for="txtDepartment">Department</label>
                        <select id="txtDepartment" 
                                name="txtDepartment" 
                                tabindex="330" >
                            <option <?php if ($Department == "CS") print " selected "; ?>
                                value="CS">CS</option>

                            <option <?php if ($Department == "BSAD") print " selected "; ?>
                                value="BSAD" 
                                >BSAD</option>

                        </select>
                    </fieldset>     <!-- ends drop down list Department -->
                    
                    <fieldset  class="listbox">	
                        <label for="txtCourse">Course</label>
                        <select id="txtCourse" 
                                name="txtCourse" 
                                tabindex="330" >
                            <option <?php if ($Course == "unsure") print " selected "; ?>
                                value="Unsure">Unsure</option>

                            <option <?php if ($Course == "CS 008") print " selected "; ?>
                                value="CS 008" 
                                >CS 008</option>

                            <option <?php if ($festivals == "CS 21") print " selected "; ?>
                                value="CS 21" >CS 21</option>

                            <option <?php if ($festivals == "CS 148") print " selected "; ?>
                                value="CS 148" >CS 148</option>

                        </select>
                    </fieldset>     <!-- ends drop down list -->

                </fieldset> <!-- ends wrapper Two -->

                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->

            </fieldset> <!-- Ends Wrapper -->
        </form>

        <?php
    } // end body submit
    ?>

</article>

<?php include "footer.php"; ?>

</body>
</html>