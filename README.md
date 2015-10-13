
<p>Sample files for CS 148 class. Be sure to check with your professor if you have problems</p>
<p>This is for an initial database setup.</p>

<p>Generally you don't show all your tables to users so this is just so you can see it working. It is recommended that you put an .htaccess file in the table folder to restrict the code to only you and your course instructor:</p>

<p><pre>
&lt;Files *&gt;
AuthType WebAuth
require user yourUsername yourInstructor etc.
satisfy any
order allow,deny
&lt;/Files&gt;
</pre></p>

<p>Be sure to update bin/pass.php with your passwords. As a rule I always put the bin folder outside of the public (www-root) folder and add ../ as needed for my path structure.</p>

<p>Demo site: <a target="_blank" href='http://rerickso.w3.uvm.edu/education/cs148/assignment1.0'>http://rerickso.w3.uvm.edu/education/cs148/assignment1.0</a></p>

<p>You request your database accounts from: <a href="https://webdb.uvm.edu/" target="_blank">https://webdb.uvm.edu/</a></p>

<p>NOTE: I use my silk account, your zoo account will also work</p>

<p>NOTE you will need to change this line in assignment1.0/lib/constants.php<br><br>define("DATABASE_NAME", strtoupper(get_current_user()) . '_UVM_Courses_Testing');<br><br>So that is has the correct database name that you are using.</p>
