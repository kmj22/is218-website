<?php

	class editController extends controller{
		
		public function __construct(){
			
			$this->html = app::menu();	
		}
		
		
		public function get(){
			$page = new editView;
			$page_html = $page->getHTML();
			$this->html .= $page_html;
      
      if (isset($_REQUEST['message']) and $_REQUEST['message'] == 1){
        echo 'Please fill in a Condition, VIN, and Price';
      }      
		}
		public function post(){
			//Honeypot
			if (isset($_POST['Secret']) and $_POST['Secret'] !== ''){
				echo 'Nice try, robot';
				return;
			}
      
       //not all fields filled
      if (!isset($_POST['delete_btn']) and (empty($_POST['Condition']) or empty($_POST['Price']) or empty($_POST['VIN']))){
			  header('Location: index.php?controller=editController&message=1&guid=' . $_GET['guid']);
        return;
      }
      
			//clean up potention html injections
			$_POST['Condition'] = strip_tags($_POST['Condition']);
			$_POST['VIN'] = strip_tags($_POST['VIN']);
			$_POST['Price'] = strip_tags($_POST['Price']);
			
			//upload new image
			$name     = $_FILES['file']['name'];
			$tmpName  = $_FILES['file']['tmp_name'];
			$error    = $_FILES['file']['error'];
			$size     = $_FILES['file']['size'];
			$ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		   
			switch ($error) {
				case UPLOAD_ERR_OK:
					//upload file
					$targetPath =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR. 'uploads' . DIRECTORY_SEPARATOR. $name;
					move_uploaded_file($tmpName,$targetPath);
					
					//get image href
					if (is_file('uploads/'.$name)) {
						$image = 'uploads/'.$name;
					}
					else{
						echo 'File upload error.';
					}
					break;
				case UPLOAD_ERR_INI_SIZE:
					$response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$response = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
					break;
				case UPLOAD_ERR_PARTIAL:
					$response = 'The uploaded file was only partially uploaded.';
					break;
				case UPLOAD_ERR_NO_FILE:
					$response = 'No file was uploaded.';
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
					break;
				case UPLOAD_ERR_EXTENSION:
					$response = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
					break;
				default:
					$response = 'Unknown error';
				break;
			}
			
			//get guid from the url
			$id = $_GET['guid'];	
			
			//retrieve data from inventory table
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			}
			catch(PDOException $e){
				 echo $e->getMessage();
				 return "an error establishing dbconnection has occurred";
			}			

			if (isset($image)){
				$image = "<img src=\"" . $image . "\">";
			}
			else{
				$image = '';
			}
			
			//which button was pressed, submit or delete?
			if (isset($_POST['submit_btn'])){
				//update row
				$sql = "UPDATE inventory SET status='" . $_POST['Condition'] . "', " .
				"carID= '" . $_POST['VIN'] . "', price='" . $_POST['Price'] . "', " .
				"image= '" . $image . "' " .
				"WHERE inventoryID='" . $id . "'";
				
				$stmt = $conn->prepare($sql);
				$stmt->execute();
			}
			else if (isset($_POST['delete_btn'])){
				//delete row
				$sql = "DELETE FROM inventory WHERE inventoryID='" . $id . "'";			
				$conn->exec($sql);
			}
			
			$conn = null;
			header('Location: index.php?controller=homepageController');
		}
		public function put(){
			
		}
		public function delete(){	

		}
	}
  
