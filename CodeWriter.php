<html>
	<head>
		<title>Code Writer</title>
		<!--
			MANUAL CODE CHANGES:
			 
			*index.php  the 'page' parameter
			*includes/main.php   
				include all controllers and models
			*SQL Create Table // model code not creating table yet.
			
			
			
			wishlist:
			-maintain a list of 'page' parameters, and update appropriately
				//create them to be copied and pasted.
				else if($_GET['page'] == "KingFish_View_List") {
					build_KingFish_View_List();
				}else if($_GET['page'] == "KingFish_View_New") {
					build_KingFish_View_New();
				}
				
				//AND ...index.php navTo snippets:
				 	<li>
						<a href="#" onclick="navTo_Monkey()"  ><img class="icon"   alt=" " width="35" height="35"/>Monkey</a>
					</li>

					function navTo_Monkey(){
						 $(location).attr('href',"<?php BASE_URL ?>?page=Monkey_View_List");
					}
				
			 
				
			-have SQL create table if not exists... 
			
		-->
		<style>
			body {
				margin: 0;
				font-family: arial;
				margin: 0;
				text-align: center;
			}
			#form_container {
				margin-top: 25px;
				text-align: left;
				width: 80%;
				margin-left: 20%;
			}
			.list_input {
				width: 400px;
			}
			input, select, textarea {
				margin-bottom: 20px;
			}
			label {
				color: #333
			}

		</style>
	</head>
	<body>
		<h1>Code Writer</h1>
		<div id="form_container">
			<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> >
				<label>Write Code ( one fell swoop) </label></br>
				<ul>
					<li>
						PHP Class
					</li>
					<li>
						JS Object
					</li>
					<li>
						and SQL
					</li>
				</ul>

				<!--<label>Name the file</label></br>
				<input type = "text" name="filename" placeholder="Filename" value="Foo"></input></br>
				-->
				<label>Name the object</label></br>
				<input type = "text" name="objectname" placeholder="Object Name" value="Foo">
				</input></br>
				<label>List of Properties(comma separated)</label></br>
				<input type = "text" class="list_input" name="arrayOfProperties" placeholder="comma separated Properties" value="id, name, thing1, thing2">
				</input></br>
				<label>List of Methods(comma separated)</label></br>
				<input type = "text" class="list_input" name="arrayOfMethods" placeholder="comma separated  Methods" value="init" >
				</input></br>
				<label>Place in appropriate directory?</label>
				<input type="checkbox" class="" name="placeAppropriateDirectory" ></input></br>
				<input type="submit" value="write file" >
				</input>
			</form>
		</div>
	</body>
</html>
<?php
define('BASE_PATH', realpath(dirname(__FILE__)));
define('OS_TYPE', 'mac');
 
// mac or pc
$props = explode(',', preg_replace('/\s+/', '', $_POST['arrayOfProperties']));
$meths = explode(',', preg_replace('/\s+/', '', $_POST['arrayOfMethods']));

$filePath_mac = "file://" . BASE_PATH . "/";
$filePath_pc = "C:/" . BASE_PATH . "/";
$CODE_OBJECT = "anything for now";
//$filename = $_POST['filename'];
$objectName = $_POST['objectname'];
//$arrayOfProperties = array('foo','bar','baz','buz');
$arrayOfProperties = $props;
//$arrayOfMethodNames = array('init','create','display');
$arrayOfMethodNames = $meths;
if(isset($_POST['placeAppropriateDirectory'])){
	if($_POST['placeAppropriateDirectory'] == "on"){
		$appropriateDirectory = true;
	}
}



if (OS_TYPE == "mac") {

	$js_filepath = $filePath_mac;
	$model_filepath = $filePath_mac;
	$controller_filepath = $filePath_mac;
	$view_list_filepath = $filePath_mac;
	$view_new_filepath = $filePath_mac;
	$view_list__filepath = $filePath_mac;
	$includes_filepath = $filePath_mac."/includes/main.php";

} else {

	$js_filepath = $filePath_pc;
	$model_filepath = $filePath_pc;
	$controller_filepath = $filePath_pc;
	$view_list_filepath = $filePath_pc;
	$view_new_filepath = $filePath_pc;
	$view_list__filepath = $filePath_pc;
	$includes_filepath = $filePath_pc."/includes/main.php";;
}


$directory = "./" . $objectName . "/";
// only create directory if needed.
if(!$appropriateDirectory){
	if (!mkdir($directory, 0, true)) {
		die('Failed to create folders...');
	}
}


if ($CODE_OBJECT != "nonsense") {
	// JS OBJECT---////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	unset($js_final_filepath);
	
	$directory = "./" . $objectName . "/";
	//APPROPRIATE:
	if($appropriateDirectory){
	 $directory = "js/";
	}
	$js_final_filepath = $js_filepath . $directory .= $objectName . ".object.js";
	echo("\n\$js_final_filepath: ".$js_final_filepath);
	//---WRITE
	$fp = fopen($js_final_filepath, "w") or die("Couldn't open $js_final_filepath");
	//array of properties to comma separated parameters
	$arrayOfPropertiesToString = implode(',', $arrayOfProperties);
	fwrite($fp, "function " . $objectName . "(" . $arrayOfPropertiesToString . ") { \n");
	fclose($fp);
	//----APPEND
	$fp = fopen($js_final_filepath, "a") or die("Couldn't open $js_final_filepath");
	//LOOP PROPERTIES
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		fputs($fp, "\tthis." . $arrayOfProperties[$x] . " = " . $arrayOfProperties[$x] . ";\n");
	}
	// END FUNCTION HEAD
	fputs($fp, "}\n");
	// PROTOTYPE PROPERTIES
	fputs($fp, "\n//Properties \n");
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		fputs($fp, "" . $objectName . ".prototype." . $arrayOfProperties[$x] . " = '';\n");
	}
	// PROTOTYPE METHODS
	fputs($fp, "\n//Methods \n");
	$max = count($arrayOfMethodNames);
	for ($x = 0; $x < $max; $x++) {
		fputs($fp, "" . $objectName . ".prototype." . $arrayOfMethodNames[$x] . " = function(){\n\t //code \n};\n");
	}
	// USE CASE
	fputs($fp, "\n//---Use Case \n");
	fputs($fp, "//var " . strtolower($objectName) . " = new " . $objectName . "(" . $arrayOfPropertiesToString . "); \n");

	fclose($fp);
	echo('<h3>JS OBJECT</h3>');
	showCode($js_final_filepath);

}


//PHP CONTROLLER---////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($CODE_OBJECT != "nonsense") {
	
	unset($controller_final_filepath);
	$directory = "./" . $objectName . "/";
	//APPROPRIATE:
	if($appropriateDirectory){
	 $directory = "includes/controllers/";
	}
	$controller_final_filepath = $controller_filepath . $directory .= $objectName . ".controller.php";
	echo("\n\$controller_final_filepath: ".$controller_final_filepath);
	//---WRITE
	$fp = fopen($controller_final_filepath, "w") or die("Couldn't open $controller_final_filepath");
	//array of properties to comma separated parameters
	$arrayOfPropertiesToString = implode(',', $arrayOfProperties);
 
	fputs($fp,"<?php
if (isset(\$_POST['method'])) {
	 	include(\"../assets/php/security.php\");
	 	\$_data = corruptDataCheck(\$_POST);
		if(\$_data ==1){
			\$warning = corruptionWarning();
			echo(\"<script> console.log('---> WARNING  !!! \".\$warning.\"');  </script>\");
		}	
	\tswitch (\$_data['method']) { ");
	
	fputs($fp,"\n\t\tcase 'create' :
			 // include main includes for ajax only:
			include(\"../models/" . ucfirst($objectName) . ".model.php\");
			\$" . strtolower($objectName) . " = new " . ucfirst($objectName) . "(); 
			
			\$" . strtolower($objectName) . "->init(");
			
			// \$_data['id'], \$_data['name'], \$_data['thing1'], \$_data['thing2']);
			//LOOP PROPERTIES
			$max = count($arrayOfProperties);
			for ($x = 0; $x < $max; $x++) {
				//fputs($fp, "\tprivate $" . $arrayOfProperties[$x] . ";\n");
				if($x == 0){
					fputs($fp,"\$_data['" . $arrayOfProperties[$x] . "']");
				}else{
					fputs($fp,",\$_data['" . $arrayOfProperties[$x] . "']");
				}
			}
			fputs($fp,");");
			
			fputs($fp,"\n\t\t\t\$" . strtolower($objectName) . " ->create_" . ucfirst($objectName) . "( \$" . strtolower($objectName) . ");
			unset(\$" . strtolower($objectName) . ");
			break;");
			
	fputs($fp,"\n\t\tcase 'read' :
			read_" . ucfirst($objectName) . "();
			break;");
	fputs($fp,"\n\t\tcase 'update' :
			include(\"../models/" . ucfirst($objectName) . ".model.php\");
 			\$" . strtolower($objectName) . " = new " . ucfirst($objectName) . "(); 
 			
			\$" . strtolower($objectName) . "->init(");
			//\$_data['id'], \$_data['name'], \$_data['thing1'], \$_data['thing2']);
			$max = count($arrayOfProperties);
			for ($x = 0; $x < $max; $x++) {
				//fputs($fp, "\tprivate $" . $arrayOfProperties[$x] . ";\n");
				if($x == 0){
					fputs($fp,"\$_data['" . $arrayOfProperties[$x] . "']");
				}else{
					fputs($fp,",\$_data['" . $arrayOfProperties[$x] . "']");
				}
			}
			 fputs($fp,");");
			 
			fputs($fp,"\n\t\t\t\$" . strtolower($objectName) . "->set_id(\$_data['id']);
			\$" . strtolower($objectName) . " ->update_" . ucfirst($objectName) . "(\$" . strtolower($objectName) . ");
			unset(\$" . strtolower($objectName) . ");
			break;");
	fputs($fp,"\n\t\tcase 'delete' :
			include(\"../models/" . ucfirst($objectName) . ".model.php\");
			\$" . strtolower($objectName) . " = new " . ucfirst($objectName) . "();
			\$" . strtolower($objectName) . "->set_id(\$_data['id']);
			\$" . strtolower($objectName) . " ->delete_" . ucfirst($objectName) . "( \$" . strtolower($objectName) . ");
			unset(\$" . strtolower($objectName) . ");
			break;");
	fputs($fp,"\n\t\tdefault :
			break;");
		fputs($fp,"\n} //end switch \n");	
		
		fputs($fp,"\n} //end isset POST \n");
		//FUNCTIONS
	fputs($fp,"\nfunction build_" . ucfirst($objectName) . "_View_List(){
	\$" . strtolower($objectName) . " = new " . ucfirst($objectName) . "();
	\$data = \$" . strtolower($objectName) . "->read_" . ucfirst($objectName) . "();	 
	build_Page(\"" . ucfirst($objectName) . ".view.list.php\",\$data);
}");
	fputs($fp,"\nfunction build_" . ucfirst($objectName) . "_View_New(){
	\$" . strtolower($objectName) . " = new " . ucfirst($objectName) . "();
	\$data =\"\";	 
	build_Dialog(\"" . ucfirst($objectName) . ".view.new.php\",\$data);
}");


	if($appropriateDirectory){
		$i_fp = fopen($includes_filepath, "a") or die("Couldn't open $includes_filepath");
	 	 // fputs to includes/main.php
	 	 //require_once "includes/controllers/KingFish.controller.php";
		//require_once "includes/models/KingFish.model.php";
		fputs($i_fp,"\nrequire_once \"includes/controllers/" . ucfirst($objectName) . ".controller.php\";");
		fputs($i_fp,"\nrequire_once \"includes/models/" . ucfirst($objectName) . ".model.php\";");
		fclose($i_fp);
		
	}

	fclose($fp);
	echo('<h3>PHP CONTROLLER</h3>');
	showCode($controller_final_filepath);

 
}


if ($CODE_OBJECT != "nonsense") {
	//PHP MODEL---////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	unset($model_final_filepath);
	$directory = "./" . $objectName . "/";
	//APPROPRIATE:
	if($appropriateDirectory){
	 $directory = "includes/models/";
	}
	$model_final_filepath = $model_filepath . $directory .= $objectName . ".model.php";
	echo("\n\$model_final_filepath: ".$model_final_filepath);
	//---WRITE
	$fp = fopen($model_final_filepath, "w") or die("Couldn't open $model_final_filepath");
	//array of properties to comma separated parameters
	$arrayOfPropertiesToString = implode(',', $arrayOfProperties);
	fwrite($fp,"<?php \ninclude_once('Model.model.SQLi.php');");
	fputs($fp, "\n\nclass " . $objectName . " extends Model  { \n\n");
	fclose($fp);
	//----APPEND
	$fp = fopen($model_final_filepath, "a") or die("Couldn't open $model_final_filepath");
	//LOOP PROPERTIES
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		fputs($fp, "\tprivate $" . $arrayOfProperties[$x] . ";\n");
	}
	//CONSTRUCT
	fputs($fp,"\n\tfunction __construct(){
		parent::__construct();
	} ");
	fputs($fp,"\n\tpublic function model_connect() {
		return parent::connect();
	}");
	
	fputs($fp, "\n\tfunction init(");
	//PROPERTIES
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		if ($x == 0) {
			fputs($fp, "$" . $arrayOfProperties[$x]);
		} else {
			fputs($fp, ",$" . $arrayOfProperties[$x]);
		}
	}
	//END
	fputs($fp, "){\n");
	//DEFINE PROPERTIES
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		fputs($fp, "\t\t\$this -> " . $arrayOfProperties[$x] . " = $" . $arrayOfProperties[$x] . ";\n");
	}
	//END
	fputs($fp, "\t} \n");
	// GETTERS & SETTERS
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		fputs($fp, "\tpublic function set_" . $arrayOfProperties[$x] . "($" . $arrayOfProperties[$x] . "){\n");
		fputs($fp, "\t\t\$this -> " . $arrayOfProperties[$x] . " = $" . $arrayOfProperties[$x] . ";\n\t}\n");
		fputs($fp, "\tpublic function get_" . $arrayOfProperties[$x] . "(){\n");
		fputs($fp, "\t\treturn \$this -> " . $arrayOfProperties[$x] . "; \n\t}\n");
	}
	// USE CASE(instantiate via POST array)
	fputs($fp, "\n//---USE CASE (instantiate via POST array)---------------\n//\$" . strtolower($objectName) . " = new " . $objectName . "(");
	for ($x = 0; $x < $max; $x++) {
		if ($x == 0) {
			fputs($fp, " \$_POST['" . $arrayOfProperties[$x] . "']");
		} else {
			fputs($fp, ", \$_POST['" . $arrayOfProperties[$x] . "']");
		}
	}
	fputs($fp, "); \n");
	// -----SQL-----------------------
	 fputs($fp,"
	 \n\tpublic function read_".ucfirst($objectName)."(\$return = \"\") {
		\$con = \$this -> model_connect();
		\$sql = \" SELECT * FROM \" . \$this -> getDatabase() . \".".strtolower($objectName)." ;\";
		\$data = \$this -> exe_sql(\$con, \$sql, \$return);
		return \$data;
	 \n\t} ");
	// SQL INSERT-----------------------------------------------------------------------------
	fputs($fp, "\n//---SQL INSERT -------------------------------\n");
	  
	 fputs($fp,"
	 \n\tfunction create_" . ucfirst($objectName) . "(\$" . strtolower($objectName) . ",\$return = \"json\") {
		\$con = \$this -> model_connect();");
		
		fputs($fp, "\n\t\t\$sql = \"INSERT INTO \".\$this -> getDatabase().\"." . strtolower($objectName) . " (");
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		if ($x == 0) {
			fputs($fp, "" . $arrayOfProperties[$x]);
		} else {
			fputs($fp, "," . $arrayOfProperties[$x]);
		}
	}
	fputs($fp, ")\n\t\tVALUES(");
	for ($x = 0; $x < $max; $x++) {
		if ($x == 0) {
			fputs($fp, "'\".\$" . strtolower($objectName) . "->get_" . $arrayOfProperties[$x] . "().\"' ");
		} else {
			fputs($fp, ", '\".\$" . strtolower($objectName) . "->get_" . $arrayOfProperties[$x] . "().\"' ");
		}
	}
	fputs($fp, ");\"; ");
		
		fputs($fp,"\n\t\$data = \$this -> exe_sql(\$con,\$sql, \$return);
		 // in the case of an insert , the return data will be the \"last id inserted\".
		echo(\$data);
	 \n\t } ");
	 
	 
	 
	//SQL UPDATE-----------------------------------------------------------------------------
 
	 fputs($fp,"
	 function update_" . ucfirst($objectName) . "(\$" . strtolower($objectName) . ",\$return = \"json\") {
		\$con = \$this -> model_connect();");
	fputs($fp, "\n\t\t\$sql = \"UPDATE \".\$this -> getDatabase().\"." . strtolower($objectName) . " set ");
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		if ($x == 0) {
			fputs($fp, "" . $arrayOfProperties[$x] . " = '\".\$" . strtolower($objectName) . "->get_" . $arrayOfProperties[$x] . "().\"' ");
		} else {
			fputs($fp, ", " . $arrayOfProperties[$x] . " = '\".\$" . strtolower($objectName) . "->get_" . $arrayOfProperties[$x] . "().\"' ");
		}
	}
	fputs($fp, " WHERE ");
	fputs($fp, "id = \".\$" . strtolower($objectName) . "->get_id().\"\";");
	fputs($fp,"	\n\t\t\$data = \$this -> exe_sql(\$con, \$sql, \$return);
 		echo(\$data);
	}
	 ");
	  
	fputs($fp,"
	function delete_" . ucfirst($objectName) . "(\$" . strtolower($objectName) . ",\$return = \"json\"){
		\$con = \$this -> model_connect();
		\$sql = \"DELETE FROM \" . \$this -> getDatabase() . \"." . strtolower($objectName) . " WHERE id = \" . \$" . strtolower($objectName) . " -> get_id() . \"  ;\";
		\$data = \$this -> exe_sql(\$con, \$sql, \$return);
		echo(\$data);
	}
	");
	
	//SQL CREATE TABLE------------------------------------------------------------------------------
fputs($fp,"function create_table_" . ucfirst($objectName) . "(){" );
fputs($fp,"\n\t\t\$con = \$this -> model_connect();");
fputs($fp, "\n\t\t\$sql = \" CREATE TABLE IF NOT EXISTS ");
	fputs($fp, "`" . strtolower($objectName) . "`" . " (");
	for ($x = 0; $x < $max; $x++) {
		if ($x == 0) {
			fputs($fp, "`" . $arrayOfProperties[$x] . "`" . " bigint(20) NOT NULL AUTO_INCREMENT");
			fputs($fp, ", PRIMARY KEY" . "(`" . $arrayOfProperties[$x] . "`)");
		} else {
			fputs($fp, ", `" . $arrayOfProperties[$x] . "`  varchar(20) NOT NULL");
		}
	}
	fputs($fp, ")\n\t\t ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='viaCodeWriter' AUTO_INCREMENT=1 ; \" ;\n");

fputs($fp,"\n\t\t\$data = \$this -> exe_sql(\$con, \$sql, \$return);");
fputs($fp,"\n}");

	// END CLASS
	fputs($fp, "\n\t }\n?>\n\n");
	fclose($fp);
	echo('<h3>PHP MODEL and SQL</h3>');
	showCode($model_final_filepath);
}

if ($CODE_OBJECT != "nonsense") {
	//VIEW NEW---////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	unset($view_new__final_filepath);
	$directory = "./" . $objectName . "/";
	//APPROPRIATE:
	if($appropriateDirectory){
	 $directory = "includes/views/";
	}
	$view_new__final_filepath = $view_new_filepath . $directory .= $objectName . ".view.new.php";
	echo("\n\$view_new__final_filepath: ".$view_new__final_filepath);
	echo($view_new__final_filepath);
	//---WRITE
	$fp = fopen($view_new__final_filepath, "w") or die("Couldn't open $view_new__final_filepath");
	//array of properties to comma separated parameters
	$arrayOfPropertiesToString = implode(',', $arrayOfProperties);
	fwrite($fp, "<div data-role='main' class='ui-content'>
	<div class='ui-field-contain'  >
		<!-- NEW VIEW -->
		<!-- LOOP: Properties to Text Inputs -->
		<!--
			Validation Methods:
			onblur='checkNotEmpty(this);'
			onblur='checkEmail(this);'
			onblur='checkPhone(this);'
		-->");
	//LOOP PROPERTIES
	$max = count($arrayOfProperties);
	for ($x = 0; $x < $max; $x++) {
		fputs($fp, "\t\n<label for='" . $arrayOfProperties[$x] . "' >" . $arrayOfProperties[$x] . " </label>");
		if($arrayOfProperties[$x] == "id"){
			fputs($fp, "\t\n<input type='hidden' name = '" . $arrayOfProperties[$x] . "' id = '" . $arrayOfProperties[$x] . "' data-clear-btn='true' class='info'   >\n");
			
		}else{
			fputs($fp, "\t\n<input type='text' name = '" . $arrayOfProperties[$x] . "' id = '" . $arrayOfProperties[$x] . "' data-clear-btn='true' class='info'  onblur='checkNotEmpty(this);' >\n");
				
			
		}
	
	}
	fwrite($fp, "\n</div>");
	fwrite($fp, "\t\n<a href='' onclick='add_" . ucfirst($objectName) . "();' data-transition='slide' class='ui-btn  ui-corner-all ui-icon-check ui-btn-icon-right'>ok</a> ");
	fwrite($fp, "\n<script>");
	fwrite($fp, "\n\tfunction add_" . ucfirst($objectName)  . "() {\n\t var " . $objectName . "_obj = {};");
	fwrite($fp, "\n\t\$.each(\$('.info'), function(i, e) {");
	fwrite($fp, "\n\tswitch(e.name) {");
	//loop
	for ($x = 0; $x < $max; $x++) {
		fputs($fp, "\n\t\tcase '" . $arrayOfProperties[$x] . "' :");
		fputs($fp, "\n\t\t".$objectName."_obj.".$arrayOfProperties[$x]." = e.value; break;");
	}
	fwrite($fp, "\n\tdefault:");
	fwrite($fp, "\n\t}");
	fwrite($fp, "\n\t});");
	fwrite($fp, "\n\tvar params = \$.param(".$objectName."_obj);");
	fwrite($fp, "\n\tvar queryString = 'method=create&'+ params;");
	fwrite($fp, "\n\tvar url = '<?php BASE_URL?>includes/controllers/".ucfirst($objectName).".controller.php';");
	fwrite($fp, "\n\tajax_datastring_URL_callback(queryString, url, add_".ucfirst($objectName) ."_callback);");
	fwrite($fp, "\n\tfunction add_".ucfirst($objectName) ."_callback(data) {");
	fwrite($fp, "\n\t\$(location).attr('href','<?php BASE_URL?>?page=".ucfirst($objectName)."_View_List');");
	fwrite($fp, "\n\t}");
	
	fwrite($fp, "\n}");
	fwrite($fp, "\n</script>");
	fwrite($fp, "\n</div>\n</div>");
	 
	fwrite($fp, "\n\t<!-- HANDLE A RADIO GROUP ");
	fwrite($fp, "\n\tif (e.name == 'propertyX') {
			\t\t$('input[name*=propertyX]:checked').each(function() {
				\t\t".$objectName."_obj.propertyX = $(this).val();
			\t\t});
		\t}");
	fwrite($fp, "\n\t--> ");
	
	fclose($fp);
	 
	fclose($fp);
	echo('<h3>NEW VIEW</h3>');
	showCode($view_new__final_filepath);
}

if ($CODE_OBJECT != "nonsense") {
	//VIEW LIST---////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	unset($view_new__final_filepath);
	$directory = "./" . $objectName . "/";
	//APPROPRIATE:
	if($appropriateDirectory){
	 $directory = "includes/views/";
	}
	$view_list_final_filepath = $view_list_filepath . $directory .= $objectName . ".view.list.php";
	echo("\n\$view_new__final_filepath: ".$view_new__final_filepath);
	echo($view_list_final_filepath);
	//---WRITE
	$fp = fopen($view_list_final_filepath, "w") or die("Couldn't open $view_list_final_filepath");
	//array of properties to comma separated parameters
	$arrayOfPropertiesToString = implode(',', $arrayOfProperties);
	fwrite($fp, "<!-- LIST VIEW -->
<!-- LOOP: Properties to Text Inputs -->
<!--
	Validation Methods:
	onblur='checkNotEmpty(this);'
	onblur='checkEmail(this);'
	onblur='checkPhone(this);'
-->");
	fputs($fp,"\n<?php 
\$p = 0;
while (\$p < count(\$data)) {
	echo(\"<div data-role='panel' id='\" . \$data[\$p]['id'] . \"'> 
			<h2>\" . \$data[\$p]['id'] . \"</h2>\");");
			    $max = count($arrayOfProperties);
				for ($x = 0; $x < $max; $x++) {
 					fputs($fp,"\n\t echo(\"<p>". ucfirst($arrayOfProperties[$x]).":<input type='text' id='".$arrayOfProperties[$x]."'  name='".$arrayOfProperties[$x]."' class='info' value='\" . \$data[\$p]['".$arrayOfProperties[$x]."'] . \"'/></p> \"); ");
				}
				fputs($fp,"\n\t echo(\"<a onclick='edit_".ucfirst($objectName)."( \".\$data[\$p]['id'].\");' class='ui-btn ui-btn-inline ui-shadow ui-corner-all ui-mini ui-icon-edit ui-btn-icon-notext'></a>
				<a onclick='delete_".ucfirst($objectName)."( \".\$data[\$p]['id'].\");' class='ui-btn ui-btn-inline ui-shadow ui-corner-all ui-mini ui-icon-delete ui-btn-icon-notext'></a>
 			</div>\");

	\$p++;
}
?>
	");	 
	
	
	fputs($fp,"
	
<div data-role=\"main\" class=\"ui-content  content_wrapper\">
	<h2>".ucfirst($objectName)."</h2>
	<div data-role=\"navbar\">
		<ul>
			<li>
				<a href=\"<?php BASE_URL?>?page=".ucfirst($objectName)."_View_New\"  data-prefetch data-transition='fade' class=\"ui-btn ui-state-persist\">New ".ucfirst($objectName)."</a>
			</li>
		</ul>
	</div> 
	 
	<ul  id=\"".strtolower($objectName)."_list\" data-role=\"listview\" data-autodividers=\"true\" data-inset=\"true\"  >
		<?php
		\$i = 0;
		while (\$i < count(\$data)) {
			echo(\"<li><a href='#\" . \$data[\$i]['id'] . \"'>\" . \$data[\$i]['name'] . \"</a></li>\");
			\$i++;
		}
		?>
	</ul>
</div>
<script>

	function edit_".ucfirst($objectName)."(id){
			if(confirm('Are you sure?'))
			{
				 verify_edit(id);
				 } else {
				  deny();
			}
		}
		
	function verify_edit(id) {
		var ".ucfirst($objectName)."_obj = {};
		".ucfirst($objectName)."_obj.id = id;
		
		$.each($(\"#\" + id + \" .info\"), function(i, e) {
			//console.log(i + \"||\" + e.name + \"||\" + e.value);
			switch(e.name) {");
						 
				for ($x = 0; $x < $max; $x++) {
 					//fputs($fp,"\n echo(\" <p>". ucfirst($arrayOfProperties[$x]).":<input type='text' id='".$arrayOfProperties[$x]."'  name='".$arrayOfProperties[$x]."' class='info' value='\" . \$data[\$p]['".$arrayOfProperties[$x]."'] . \"'     /></p> \"); ");
					fputs($fp,"\n\t\t\t\tcase \"".$arrayOfProperties[$x]."\": ");
					fputs($fp,"\n\t\t\t\t\t".ucfirst($objectName)."_obj.".$arrayOfProperties[$x]." = e.value;");
					fputs($fp,"\n\t\t\t\tbreak;");
					
				}	
							
				fputs($fp,"		 
					default:
					//default code block
					}
				
		});

			console.log(".ucfirst($objectName)."_obj);
			var params = $.param(".ucfirst($objectName)."_obj);
			var queryString = \"method=update&\"+params;
			var url = \"<?php BASE_URL?>includes/controllers/".ucfirst($objectName).".controller.php\";
			//alert(queryString+url);
			ajax_datastring_URL_callback(queryString, url, update_".ucfirst($objectName)."_callback);
	}
	
	function update_".ucfirst($objectName)."_callback(data){
		//alert('success ... maybe.'+ data);
		 $(location).attr('href',\"<?php BASE_URL?>?page=".ucfirst($objectName)."_View_List\");
	}
	
	function deny() {
		//alert(\"you denied\");
	}
	
	function delete_".ucfirst($objectName)."(id){
			if(confirm('Are you sure you want to delete this ".ucfirst($objectName)."?'))
			{
				 verify_delete(id);
				} else {
				 deny();
			}
	}
	
	function verify_delete(id) {
		var ".ucfirst($objectName)."_obj = {};
		".ucfirst($objectName)."_obj.id = id;
		console.log(".ucfirst($objectName)."_obj);
		var queryString = \"method=delete&id=\" + ".ucfirst($objectName)."_obj.id  ;
		var url = \"<?php BASE_URL?>includes/controllers/".ucfirst($objectName).".controller.php\";
		ajax_datastring_URL_callback(queryString, url, delete_".ucfirst($objectName)."_callback);
	}
	
	function delete_".ucfirst($objectName)."_callback(data){
		 $(location).attr('href',\"<?php BASE_URL?>?page=".ucfirst($objectName)."_View_List\");
	}
	
	 
</script>
	");
	
	
	
	 fputs($fp,"\n <!-- --- V I E W   S N I P P E T S  ---------------:");
	fputs($fp,"\n--- index.php PAGE CONDITIONS:");
	fputs($fp,"\nelse if(\$_GET['page'] == \"".ucfirst($objectName)."_View_List\") {");
	fputs($fp,"\n\tbuild_".ucfirst($objectName)."_View_List();");
	fputs($fp,"\n}else if(\$_GET['page'] == \"".ucfirst($objectName)."_View_New\") {");
	fputs($fp,"\n\tbuild_".ucfirst($objectName)."_View_New();");
	fputs($fp,"\n}");
	
	 
	 
	 fputs($fp,"\n--- footer.php navTo snippets: -----------");
	 fputs($fp,"\n<li>");
	 fputs($fp,"\n\t<a href=\"<?php BASE_URL ?>?page=".ucfirst($objectName)."_View_List\"   data-prefetch data-transition='fade'   ><img class=\"icon\"   alt=\" \" width=\"35\" height=\"35\"/>".ucfirst($objectName)."</a>");
	 fputs($fp,"\n</li>");
	 
	 
	 fputs($fp,"\n--- MYSQL TABLE don't forget.......: ----------- \n -->");
	  
	 
	fclose($fp);
	echo('<h3>LIST VIEW</h3>');
	showCode($view_list_final_filepath);
}


/*
 * 
 
 if ($CODE_OBJECT != "nonsense") {

	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx"); 
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");
	fputs($fp,"xxxxxxxxx");

	fclose($fp);
	echo('<h3>NEW OBJECT IS ... </h3>');
	showCode($NEW_OBJECT_NAME_final_filepath);
}

 * 
 * 
 */

 
function showCode($page) {
	$codeToHTML = new CodeToHtml;
	$codeToHTML -> viewCode($page);
}

class CodeToHtml {
	public function viewCode($page) {
		$code = htmlspecialchars(file_get_contents($page));
		echo("<style> .codeViewClass{
									background:black;
									text-align:left;
									color:green;
									height:200px;
									overflow-y:scroll;
									width:80%;
									margin-left:10%;
									margin-top:15px;
									} </style>");
		echo("<div class='codeViewClass'><pre>" . $code . "</pre></div>");
	}

}
?>
