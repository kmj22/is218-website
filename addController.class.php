<?php

	class addController extends controller{
		
		public function __construct(){
			
			$this->html = app::menu();
		}
		
		public function get(){
			$form = new addView;
			$form_html = $form->getHTML();
			$this->html .= $form_html;
      
      if (isset($_REQUEST['message']) and $_REQUEST['message'] == 1){
        echo 'Please fill in a Condition, VIN, and Price';
      }
		}
		public function post(){
			//Honeypot
			if ($_POST['Secret'] !== ''){
				echo 'Nice try, robot';
				return;
			}	
      
      //not all fields filled
      if (empty($_POST['Condition']) or empty($_POST['Price']) or empty($_POST['VIN'])){
			  header('Location: index.php?controller=addController&message=1');
        return;
      }
      
			//clean up potention html injections
			$_POST['Condition'] = strip_tags($_POST['Condition']);
			$_POST['VIN'] = strip_tags($_POST['VIN']);
			$_POST['Price'] = strip_tags($_POST['Price']);      
			
			//upload image
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
			echo $response;
			
			$car = new carModel;
			$car->setCondition($_POST['Condition']);
			$car->setID($_POST['VIN']);
			$car->setPrice($_POST['Price']);
			if (isset($image)){
				$car->setImage('<img src="'.$image.'">');
			}
			$car->save();
			header('Location: index.php');
		}
		public function put(){
			
		}
		public function delete(){
			
		}
	}
  
