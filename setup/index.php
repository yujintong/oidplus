<!DOCTYPE html>
<html lang="en">

<head>
	<title>OIDplus setup</title>
	<link rel="stylesheet" href="setup.css">
	<script src="../3p/sha3_js/sha3.js"></script><!-- https://github.com/emn178/js-sha3 -->
	<script src="setup.js"></script>
</head>

<body onLoad="javascript:rebuild()">

<h1>OIDplus setup - Database connectivity</h1>

<div id="step1">
<h2>Step 1: Enter setup information</h2>
<form id="step1_form">
<p>Which admin password do you want?<br><input id="admin_password" type="password" autocomplete="new-password" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()"> <span id="password_warn"></span></p>
<p>Please repeat the password input:<br><input id="admin_password2" type="password" autocomplete="new-password" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()"> <span id="password_warn2"></span></p>
<p>---</p>
<p>MySQL hostname (usually <b>localhost</b>):<br><input id="mysql_host" type="text" value="localhost" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()">  <span id="mysql_host_warn"></p>
<p>MySQL username:<br><input id="mysql_username" type="text" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()"> <span id="mysql_username_warn"></p>
<p>MySQL password:<br><input id="mysql_password" type="password" autocomplete="new-password" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()"></p>
<p>MySQL database name:<br><input id="mysql_database" type="text" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()"> <span id="mysql_database_warn"></p>
<p>Tablename prefix (e.g. <b>oidplus_</b>):<br><input id="tablename_prefix" type="text" value="oidplus_" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()"></p>
<p>---</p>
<p><input id="recaptcha_enabled" type="checkbox" onclick="javascript:rebuild()"> <label for="recaptcha_enabled">RECAPTCHA Enabled</label></p>
<p>RECAPTCHA Public key<br><input id="recaptcha_public" type="text" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()"></p>
<p>RECAPTCHA Private key<br><input id="recaptcha_private" type="text" onKeyPress="javascript:rebuild()" onKeyUp="javascript:rebuild()"></p>
<p>---</p>
<p>SSL enforcement<br><select name="enforce_ssl" id="enforce_ssl" onclick="javascript:rebuild()">
<option value="0">No SSL available (don't redirect)</option>
<option value="1">Enforce SSL (always redirect)</option>
<option value="2" selected>Intelligenet SSL detection (redirect if port 443 is open)</option>
</select></p>
</form>
</div>

<div id="step2">
<h2>Step 2: Import data</h2>
<p><b>If you already have an OIDplus database and just want to rebuild the config file, please ignore this step.</b></p>
<p>Otherwise, import one of the following MySQL dumps in your database:</p>
<p><ul>
	<li><a href="struct_empty.sql.php" id="struct_1" target="_blank">Empty OIDplus database without example data</a><br><br></li>
	<li><a href="struct_with_examples.sql.php" id="struct_2" target="_blank">OIDplus database with example data</a></li>
</ul></p>
<p><font color="red">All data from the previous OIDplus instance will be deleted during the import</font></p>

<!--
TODO: At this step we could also show what we would need to do at command line:
wget ...
mysql < ...
-->

</div>

<div id="step3">
<h2>Step 3: Save config.inc.php file</h2>
<p>Save following contents into the file <b>includes/config.inc.php</b>:</p>
<code><font color="darkblue"><div id="config"></div></font></code>
</div>

<div id="step4">
<h2>Step 4: Continue to next step</h2>
<p><input type="button" onclick="document.location='../'" value="Continue"></p>
<!-- <p><a href="../">Run the OIDplus system</a></p> -->
</div>

</body>
</html>
