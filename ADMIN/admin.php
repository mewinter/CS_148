<?php // Karol Zieba for Bob Erickson - Last updated 2015-03-17 to add code GET tag
$crn  = '91954';                     // Course Number, according to the registrar. This is the only thing to change for each class
$class_folder = get_class_folder();  // Local folder that leads to student's class files
$class_path = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . "/${class_folder}"; // Full path to our class
$admin_path = "${class_path}/ADMIN"; // Full path to the admin folder.

$bob_url = 'https://rerickso.w3.uvm.edu/education/blackboard'; // Location of Bob's files to sync
$assignment_filename = "assignment_${crn}.json";               // Assignment Due Date json
$privileged_filename = "privileged_${crn}.json";               // List of TA/graders json
$code_exts = array('py', 'php', 'xml', 'csv', 'html', 'xhtml', 'css', 'js', 'sql', 'java', 'json');
$image_exts = array('jpg', 'jpeg', 'tiff', 'png', 'gif', 'bmp');
$validation_exts = array('php', 'css', 'html', 'xhtml');
$assignments = get_assignments($admin_path, $assignment_filename, 'noSubmit');
$restricted_assignments = get_restricted_assignments($admin_path, $assignment_filename, 'noSubmit');
$sync = array("${bob_url}/bin/admin_${crn}" => "$admin_path/admin.php",
	      "${bob_url}/json/${assignment_filename}" => "$admin_path/${assignment_filename}",
	      "${bob_url}/json/${privileged_filename}" => "$admin_path/${privileged_filename}");
$body = array();
$title = "ADMIN:";

// If there are no get statements then just list all available assignments.
// Also try to synchronize our files.
if (empty($_GET))
  {
    $title .= " Listing Assignments";
    $body[] = list_assignments($assignments, $class_path);

    foreach ($sync as $remote => $local)
      $body[] = sync_file($remote, $local);
    $body[] = update_admin_htaccess($admin_path, $privileged_filename, $restricted_assignments);
    $body[] = create_class_htaccess($class_path);
  }

// If a folder is requested then display its contents
if (isset($_GET['folder']))
  {
    $title .= " Listing Folder Contents";
    $folder = clean_path(filter_input(INPUT_GET, 'folder', FILTER_SANITIZE_SPECIAL_CHARS));
    $file_paths = bob_scandir("${class_path}/${folder}", $admin_path, false);
    if ($file_paths === False)
      $body[] = "<p class=\"error\">Folder does not exist. You should create it!</p>";
    else
      $body[] = list_files($file_paths, $class_path, $class_folder, $code_exts, $validation_exts, $assignments[$folder]);
  }

// If all files are requested, then show them a list of all files
if (isset($_GET['all']))
  {
    $title .= " Listing All Files and Directories";
    $file_paths = bob_scandir($class_path, $admin_path, true);
    if ($file_paths === False)
      $body[] = "<p class=\"error\">Something is really weird... report this to your TA or Bob.</p>";
    else
      $body[] = list_files($file_paths, $class_path, $class_folder, $code_exts, $validation_exts);
  }

// Provide the contents of this code file
if (isset($_GET['file']))
  {
    $title .= " Showing Code File Contents";
    $relative_path = clean_path(filter_input(INPUT_GET, 'file', FILTER_SANITIZE_SPECIAL_CHARS));
    $body[] = show_file($relative_path, $class_path, $code_exts);
  }

// Provide the contents of this code file
if (isset($_GET['code']))
  {
    header('Content-Type:text/plain');
    $relative_path = clean_path(filter_input(INPUT_GET, 'code', FILTER_SANITIZE_SPECIAL_CHARS));
    print file_get_contents("$class_path/$relative_path");
    die();
  }

// Provide the contents of all code files in this folder, often an assignment, recursively.
if (isset($_GET['files']))
  {
    $title .= " Showing All Code File Contents with Folder";
    $folder = clean_path(filter_input(INPUT_GET, 'files', FILTER_SANITIZE_SPECIAL_CHARS));
    $paths = bob_scandir("${class_path}/${folder}", $admin_path, true);
    if ($paths === False or count($paths) == 0)
      $body[] = "<p class=\"error\">No files found.</p>";
    else
      foreach ($paths as $file_path)
	{
	  $relative_path = substr($file_path, strlen($class_path));
	  if (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), $code_exts))
	    $body[] = show_file($relative_path, $class_path, $code_exts);
	  elseif (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), $image_exts))
	    $body[] = "<h1>${relative_path}</h1><p><img src=\"/${class_folder}/${relative_path}\" alt=\"${relative_path}\"></p>";
	  elseif (strtolower(pathinfo($file_path, PATHINFO_EXTENSION)) == 'pdf')
	    $body[] = "<h1>${relative_path}</h1><p><object type=\"application/pdf\" src=\"/${class_folder}/${relative_path}\">It appears that your browser does not support embedding pdf documents.</object></p>";
	  else
	    $body[] = "<p>Skipping ${relative_path}</p>";
	}
  }

///////////////////////////////////////////////////////////////////////////////
// Support Functions //////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

// Returns the root path of this class. This is to ensure that this class path serves as our root
// so that we can not show other file contents.
function get_class_folder()
{
  $path = '';
  $split = split("/", filter_input(INPUT_SERVER, 'PHP_SELF'));
  for ($i = 0; $i < count($split) - 2; $i++)
    if ($split[$i])
      $path .= "/" . $split[$i];
  if ($path[0] == '/')
    return substr($path, 1);
  return $path;
}

// Return an array of all assignment folders as keys and unix timestamp due dates as values. These are
// taken from our assignment json file.
function get_assignments($admin_path, $assignment_filename, $nosubmit_folder)
{
  $assignments = array();
  $contents = file_get_contents($admin_path . '/' . $assignment_filename);
  $json = json_decode($contents);
  foreach ($json->{"assignments"} as $assign_json)
    if ($assign_json->{'folder'} != $nosubmit_folder)
      $assignments[$assign_json->{'folder'}] = $assign_json->{"dueDate"};
  return $assignments;
}
// Same as the above, except for all assignments returns whether they're restricted or not
// restricted folder are basically folders with the admin .htaccess in them
function get_restricted_assignments($admin_path, $assignment_filename, $nosubmit_folder)
{
  $rassignments = array();
  $contents = file_get_contents($admin_path . '/' . $assignment_filename);
  $json = json_decode($contents);
  foreach ($json->{"assignments"} as $assign_json)
    if ($assign_json->{'folder'} != $nosubmit_folder)
      $rassignments[$assign_json->{'folder'}] = $assign_json->{"restricted"};
  return $rassignments;
}


// Creates a default class htaccess file that forces the use of https and
// creates a default index page that displays contents. If an htaccess file
// already exists then it is not changed.
function create_class_htaccess($class_path)
{
    $class_htaccess = "
SetEnv wsgi_max_requests 10
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI}

<Files *>
  Options +Indexes
</Files>
";
    $file_path = "${class_path}/.htaccess";
    if (file_exists($file_path))
      return "<!-- Class htaccess already exists -->";
    if (file_put_contents($file_path, $class_htaccess))
      return "<!-- Created class htaccess -->";
    return "<!-- Unable to create class htaccess -->";
}

// Update the htaccess contents. It will only change if the list of
// TAs or graders changes or the htaccess was deleted.
function update_admin_htaccess($admin_path, $filename, $restricted_assignments)
{
  $output = "";
  $file_path = "${admin_path}/.htaccess";
  $json_path = "${admin_path}/${filename}";
  $existing_contents = file_get_contents($file_path);

  // Fetch our json file and use it to create the expected htaccess contents
  $json_contents = file_get_contents($json_path);
  if (strlen($json_contents) == 0 or $json_contents[0] != '{')
    return "<p class=\"error\">Local file not valid: ${json_path}</p>";
  $json = json_decode($json_contents);

  // Create a list of users who can view this admin page, begining with the user
  $split = split('@', filter_input(INPUT_SERVER, 'SERVER_ADMIN'));
  $users = $split[0];
  foreach ($json->{"privileged"} as $user)
    $users .= ' ' . $user;

    $htaccess_contents = "
SetEnv wsgi_max_requests 10
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI}

<Files *>
  Options -Indexes
  AuthType WebAuth
  require user $users
  satisfy any
  order allow,deny
</Files>
";

    // Handle copying over the htaccess contents over to restricted assignment folders
    foreach ($restricted_assignments as $assignment => $restricted)
      {
	if ($restricted === 0)
	  {
	    $output .= "<!-- Folder ${assignment} not restricted -->\n";
	  }
	else
	  {
	    $assignment_folder = "${admin_path}/../${assignment}";
	    if (is_dir($assignment_folder))
	      {
		$assignment_htaccess_path = "${assignment_folder}/.htaccess";
		$assignemnt_htacces_contents = file_get_contents($assignment_htaccess_path);
		if ($htaccess_contents == $assignemnt_htacces_contents)
		  $output .= "<!-- Folder ${assignment} already appropriately restricted -->\n";
		else if (file_put_contents($assignment_htaccess_path, $htaccess_contents))
		  $output .= "<!-- Folder ${assignment} RESTRICTED -->\n";
		else
		  $output .= "<!-- Folder ${assignment} FAILED TO RESTRICT  $assignment_htaccess_path -->\n";
	      }
	    else
	      {
		$output .= "<!-- Folder ${assignment} not created yet -->\n";
	      }
	  }
      }


    // If there is an update, then write our new admin htaccess. Otherwise
    if ($htaccess_contents == $existing_contents)
      return "${output}\n<!-- No changes needed to ${file_path} -->";
    if (file_put_contents($file_path, $htaccess_contents))
      return "${output}\n<!-- Synchronized ${file_path} -->";
    return "${output}\n<!-- Failed sync on ${file_path} -->";
}

// Traverse the root path, ignoring skiping over any match to bad path. Returns an array of paths
function bob_scandir($root_path, $bad_path, $recursive)
{
  $output = array();
  $paths = scandir($root_path);
  if ($paths === False) return False;
  foreach ($paths as $path)
    {
      $potential_path = "${root_path}/${path}";

      // Ignore any files in the bad (admin) path or files that begin with a period.
      if ($potential_path == $bad_path or $path[0] == '.')
	continue;

      $output[] = $potential_path;

      if ($recursive and is_dir($potential_path)) // Recursively descend and print out files.
	$output = array_merge($output, bob_scandir($potential_path, $bad_folder, $recursive));
    }
  return $output;
}

// Converts a provided octal into a permissions string and reutrns it
function perms($oct)
{
  $str = decoct($oct);
  $out = substr($str, -5, 1) & 4 ? 'd' : '-';
  for ($i = -3; $i < 0; $i++)
    {
      $perm = substr($str, $i, 1);
      $out .= $perm & 4 ? 'r' : '-';
      $out .= $perm & 2 ? 'w' : '-';
      $out .= $perm & 1 ? 'x' : '-';
    }
  return $out;
}

// Retreive a remote file and return its contents
function url_get_contents($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

// Compares the remote file against the local one. If they're different then it copies the
// remote file over to the local one. This has not been tested with binary files.
function sync_file($remote_url, $local_path)
{
  $remote_contents = url_get_contents($remote_url);
  if (strlen($remote_contents) == 0 or
      (substr($remote_contents, 0, 1) != '{' and
       substr($remote_contents, 0, 5) != "<?php"))
    return "<!-- Remote file not valid: $remote_url -->";

  $local_contents = file_get_contents($local_path);
  if (md5($remote_contents) == md5($local_contents))
    return "<!-- No changes needed to $local_path -->";

  if (file_put_contents($local_path, $remote_contents))
    return "<!-- Synchronized $local_path -->";
  else
    return "<!-- Failed sync on $path -->";
}

// Removes .. and /. from paths
function clean_path($dirty_path)
{
  $cleaner_path = preg_replace('/\.[\.]+/', '', $dirty_path);
  return preg_replace('/\/\./', '', $cleaner_path);
}

// Show the contents of a file to the screen.
// Only show it if it's extension is part of the allowable extensions and
// is readable
function show_file($relative_path, $class_path, array $required_exts)
{
  $file_path = "${class_path}/${relative_path}";
  $file_code = "<h1>$relative_path</h1>\n";
  if (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), $required_exts) and
      is_readable($file_path) and basename($relative_path) != "pass.php")
    {

      $contents = file_get_contents($file_path);
      $file_code .= "<pre><code>\n";
      $file_code .= filter_var($contents, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $file_code .= "</code></pre>\n";
    }
  else
    $file_code .= "<p class=\"error\">File is unreadable, has an invalid extension, or is a password file.</p>";
  return $file_code;
}

function list_assignments($assignments, $root_path)
{
  $now = time();
  $output = "<table>\n<tr>";
  $output .= "<th>Permission</th>";
  $output .= "<th>Browse</th>";
  $output .= "<th>Due Date</th>";
  $output .= "<th>Files</th>";
  $output .= "</tr>\n";

  foreach ($assignments as $assignment => $due_date)
    {
      $path = $root_path . '/' . $assignment;
      $stat = @stat($path);

      $output .= '<tr>';
      $output .= '<td>' . perms($stat['mode']) . '</td>';
      $output .= "<td><a class=\"folder\" href=\"?folder=$assignment\">$assignment</a></td>";
      if ($due_date < $now)
	{
	  $output .= "<td class=\"error\">";
	}
      elseif ($due_date <  $now + 604800) // seconds in week
	{
	  $output .= "<td class=\"warning\">";
	}
      else
	{
	  $output .= "<td class=\"success\">";
	}
      $output .= date('Y-m-d H:i:s', $due_date) . "</td>";
      $output .= "<td><a class=\"code\" href=\"?files=$assignment\">All Code</a></td>";
      $output .= "</tr>\n";
    }
  $output .= "</table>\n";
  return $output;
}

// Returns output HTML of a table that contains a row for each file in $file_paths.
// These are aranged similarly to the index that Apache provides.
function list_files($file_paths, $root_path, $class_folder, array $code_exts,
		    array $validation_exts, $due_date=null)
{
  // Prepare header row
  $output = "<table>\n<tr>";
  $output .= "<th>Permission</th>";
  //$output .= "<th>User</th>";
  //$output .= "<th>Group</th>";
  $output .= "<th>File</th></th>";
  $output .= "<th>Modify Date</th>";
  $output .= "<th>Size</th>";
  $output .= "<th>Extra</th>";
  $output .= "</tr>\n";

  foreach ($file_paths as $file_path)
    {
      $relative_path = substr($file_path, strlen($root_path) + 1);
      $split = split("/", $relative_path);
      $filename = $split[count($split) - 1];
      $stat = @stat($file_path);

      $output .= '<tr>';
      $output .= '<td>' . perms($stat['mode']) . '</td>';
      //$output .= '<td>' . $stat['uid'] . '</td>';
      //$output .= '<td>' . $stat['gid'] . '</td>';

      // Display the correct link to our files
      if (is_dir($file_path))
	$output .= "<td><a class=\"folder\" href=\"?folder=$relative_path\"";
      elseif (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), $code_exts))
	$output .= "<td><a class=\"code\" href=\"?file=$relative_path\"";
      else
	$output .= "<td><a class=\"show\" target=\"_blank\" href=\"/${class_folder}/${relative_path}\"";
      $output .= ">" . (is_null($due_date) ? $relative_path : $filename) . "</a></td>";

      // Show the date. Depending on the due date color the text
      $ctime = date('Y-m-d H:i:s', $stat['ctime']);
      $mtime = date('Y-m-d H:i:s', $stat['mtime']);
      $output .= "<td onMouseOver=\"this.innerHTML='$ctime'\" onMouseOut=\"this.innerHTML='$mtime'\" ";
      if (!is_null($due_date))
	{
	  if ($stat['ctime'] < $due_date and $stat['mtime'] < $due_date)
	    $output .= "class=\"success\"";
	  else
	    $output .= "class=\"error\"";
	}
      elseif ($stat['ctime'] != $stat['mtime'])
	$output .= "class=\"warning\"";
      $output .= ">$mtime</td>";

      // Add a cell for the size. Ignore directories
      if (is_dir($file_path))
	$output .= "<td>&nbsp</td>";
      else
	$output .= '<td class="right">' . $stat['size'] . '</td>';

      // Add a cell for a validation link. Only do it for files we're checking.
      if (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), $validation_exts))
	{
	  $server_name = filter_input(INPUT_SERVER, 'SERVER_NAME');
	  $url = "https://${server_name}/${class_folder}/${relative_path}";
	  $output .= "<td><a target=\"_blank\" href=\"http://validator.w3.org/check?uri=$url\">Validate</a></td>";
	}
      elseif (is_dir($file_path))
	$output .= "<td><a class=\"code\" href=\"?files=${relative_path}\">View Code</a></td>";

      $output .= "<td>&nbsp;</td>";

      $output .= "</tr>\n";
    }
  $output .= "</table>\n";

  if (count($file_paths) == 0)
    $output .= "<p class=\"error\">There are no files to display</p>";

  return $output;
}


///////////////////////////////////////////////////////////////////////////////
// HTML ///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="<?php echo $bob_url; ?>/css/admin.css">
    <link rel="stylesheet" href="<?php echo $bob_url; ?>/css/highlight/styles/default.css">
    <script src="<?php echo $bob_url; ?>/css/highlight/highlight.pack.js"></script>
  <script type="text/javascript">hljs.initHighlightingOnLoad();</script>
   </head>
   <body>
    <nav>
    <ul>
    <li><a href="?">Assignments</a></li>
    <li><a href="?all">List of Files</a></li>
    <li><a href="#" onClick="history.go(-1);return true;">Go Back</a></li>
    </ul>
    </nav>
<?php print implode("\n", $body); ?>
  </body>
</html>
  
