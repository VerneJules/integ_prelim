<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<?php
	$student_id = "";
	if(isset($_GET["student_id"])){
		$student_id = $_GET["student_id"];
	}
	if(isset($_POST["student_id"])){
		$student_id = $_POST["student_id"];
	}
	$code = "";
	if(isset($_POST["code"])){
		$code = $_POST["code"];
	}
	$search = "";
	if(isset($_GET["search"])){
		$search = $_GET["search"];
	}
	if(isset($_POST["search"])){
		$search = $_POST["search"];
	}
	$student_script = "";
	if(isset($_POST["student_script"])){
		$student_script = $_POST["student_script"];
	}
	
	//code to validate start
	$valid = false;
	if(strlen($student_id)>0){
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$current_path = (substr($actual_link,-1) == "/")?$actual_link:pathinfo($actual_link)["dirname"];
		//code to validate start
		$student_nickname = "";
		$student_fullname = "";
		$valid = false;
		$url = $current_path."/"."validator_ws.php";
		$data = array('student_id' => $student_id, 'code' => $code);

		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) {
			$valid = false;
		}else{
			try{
				$rj = json_decode($result);
				$student_nickname = $rj->nickname;
				$student_fullname = $rj->fullname;
				$valid = true;
			}catch(Exception $e){
				$valid = false;
			}
		}
		//code to validate end
		if(isset($_POST["student_id"]) && $valid){
			$savefile = fopen("saved/".$student_id.".js", "w");
			fwrite($savefile, $student_script);
			fclose($savefile);
		}
	}
?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Integrative and Programming Technologies - Prelim Exam</title>
        <link rel="stylesheet" type="text/css" href="exam.css" >
        <script type="text/javascript">
        // <![CDATA[
	var contacts = null;
        // ]]>
        </script>
    </head>
    
    <body>
        <h1>Integrative and Programming Technologies</h1>
        <h3>Prelim Exam</h3>
	<form name="exam" method="post" target="_self">
	<div>
		<label for="student_id">ID Number:</label>
		<input type="text" id="student_id" name="student_id" value="<?php echo $student_id ?>" />
		<label for="code">Code:</label>
		<input type="password" id="code" name="code" value="<?php echo $code; ?>" />
		<input type="button" onclick="validate_student()" value="validate" />
		<br /><h4 id="student_fullname"></h4>
	</div>
	<div id="prelim" style="display:<?php if(isset($_POST["student_id"]) && $valid){echo 'block;';}else{echo 'none;';}?>">
		<div id="instructions">
			<p>The form below the blue line is a Contact Lookup Tool. The application is initially connecting to the webservice "contact_ws.php" returning all contact information via json format and storing it in the global variable contact_info.</p>
			<p>Write a script that will filter the list of contacts and their projects based on the search keywords (should work for both nickname and full name).</p>
			<p>The list of contacts by nickname in the unordered list element (id ="contacts"). As you type a text in the search box (id="search"), the contacts should be filtered based on the search term. If a contact is clicked, its details should reflect the values in the form.</p>
			<p>Do the same thing for list the projects of the contacts using the search box (id="search_project"). List down their projects in the table (id="projects").</p>
			<p>The following ID's are used in the form below.</p>
			<ul>
				<li>Nickname: nickname</li>
				<li>Fullname: fullname</li>
				<li>Image: photo</li>
				<li>Birthday: bday</li>
				<li>Gender: gender</li>
				<li>Address: address</li>
				<li>Email Address: email</li>
				<li>Projects: projects</li>
			</ul>
			<br /><input type="button" onclick="load_script()" value="Load" />
			<span class="warning">WARNING: This will load your saved code and overwrite whatever progress you have below.</span>
			<h4 id="warning"></h4>
		</div>
		<div>
			<label for="student_script">Insert Javascript Code Here:</label><br />
			<textarea id="student_script" name="student_script"><?php echo $student_script; ?></textarea>
			<input type="submit" value="Save" />
			<span class="warning">WARNING: This will overwrite whatever you have submitted.</span>
		</div>
		<hr />
		<div>
			<label for="search">Search:</label>
			<input type="text" id="search" name="search" value="<?php echo $search; ?>" />
		</div>
		<div id="leftnav">
			<ul class="contact_list" id="contacts">
			</ul>
		</div>
		<div id="profile">
			<table>
				<tr>
					<td>Nickname:</td>
					<td id="nickname">N/A</td>
					<td rowspan="2"><img id="photo" src="images/000.jpg" /></td>
				</tr>
				<tr>
					<td>Full Name:</td>
					<td id="fullname">N/A</td>
				</tr>
				<tr>
					<td>Birthday:</td>
					<td id="bday" colspan=2>N/A</td>
				</tr>
				<tr>
					<td>Gender:</td>
					<td id="gender" colspan=2>N/A</td>
				</tr>
				<tr>
					<td>Address:</td>
					<td id="address" colspan=2>N/A</td>
				</tr>
				<tr>
					<td>Email Address:</td>
					<td id="email" colspan=2>N/A</td>
				</tr>
				<tr>
					<td>Projects:</td>
					<td colspan=2><input type="text" id="search_project"></input></td>
				</tr>
				<tr>
					<td colspan=3><table id="projects"></table></td>
				</tr>
			</table>
		</div>
	</div>
	</form>
		
        <script id="input" type="text/javascript">
        // <![CDATA[
	var validate_student = function(){
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(this.readyState == 4){
				if (this.status == 200) {
					var xhr_content = this.responseText;
					try{
						var data = JSON.parse(xhr_content);
						document.getElementById("student_fullname").textContent = data["fullname"];
						document.getElementById("prelim").style.display = "block";
					}catch(err){
						document.getElementById("student_fullname").textContent = "Failed Validation";
						document.getElementById("prelim").style.display = "none";
					}
				} else {
					document.getElementById("student_fullname").textContent = "Failed Validation";
					document.getElementById("prelim").style.display = "none";
				}
			}
		}
		xhr.open("post", "validator_ws.php", true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.send("student_id"+"="+document.getElementById("student_id").value+"&"+"code"+"="+document.getElementById("code").value);
	}
	var load_script = function(){
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(this.readyState == 4){
				if (this.status == 200) {
					var xhr_content = this.responseText;
					try{
						document.getElementById("student_script").value = xhr_content;
					}catch(err){
						document.getElementById("warning").textContent = "Failed Load";
					}
				} else {
					document.getElementById("warning").textContent = "Failed Load";
				}
			}
		}
		xhr.open("post", "load_ws.php", true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.send("student_id"+"="+document.getElementById("student_id").value+"&"+"code"+"="+document.getElementById("code").value);
	}
	
	var search_box = document.getElementById("search");
	var search_project_box =  document.getElementById("search_project");
	if (search_box.addEventListener) {
		search_box.addEventListener('keydown', function(e){ if(e.keyCode == 13){ event.preventDefault(); this.dispatchEvent(new Event("change")); } } );
		search_project_box.addEventListener('keydown', function(e){ if(e.keyCode == 13){ event.preventDefault(); this.dispatchEvent(new Event("change")); } } );
	} else if (search_box.attachEvent) {
		search_box.attachEvent('keydown', function(e){ if(e.keyCode == 13){ event.preventDefault(); this.dispatchEvent(new Event("change"));} } );
		search_project_box.attachEvent('keydown', function(e){ if(e.keyCode == 13){ event.preventDefault(); this.dispatchEvent(new Event("change"));} } );
	}
	var search_contacts = function(){
	}
	var contact_selected = function(){
		console.info("contacts selected");
	}
	var search_projects = function(){
	}
        // ]]>
        </script>
		
        <script id="input" type="text/javascript">
        // <![CDATA[
	<?php echo $student_script; ?>
        // ]]>
        </script>
		
        <script type="text/javascript">
        // <![CDATA[
            var load_contacts = function(){
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function(){
                    if(this.readyState == 4){
                        if (this.status == 200) {
                            var xhr_content = this.responseText;
                            try{
                                contacts = JSON.parse(xhr_content);
                                document.getElementById("contacts").innerHTML = "";
                                contacts.forEach(function(item, index, arr){
                                    var li = document.createElement("li");
                                    var text = document.createTextNode(item["nickname"]);
                                    li.appendChild(text);
                                    document.getElementById("contacts").appendChild(li);
                                });
                            }catch(err){
                            }
                        }
                    }
                }
                var search_word = document.getElementById("search").value;
                xhr.open("post", "contact_ws.php", true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	      xhr.send();
            }
            
            var search_box = document.getElementById("search");
	  var contact_list = document.getElementById("contacts");
	  var search_project_box =  document.getElementById("search_project");
            if (search_box.addEventListener) {
                search_box.addEventListener('change', search_contacts);
                contact_list.addEventListener('click', contact_selected);
                search_project_box.addEventListener('change', search_projects);
            } else if (search_box.attachEvent) {
                search_box.attachEvent('onchange', search_contacts);
                contact_list.attachEvent('click', contact_selected);
                search_project_box.attachEvent('onchange', search_projects);
            }
            load_contacts();
        // ]]>
        </script>
    </body>
</html>