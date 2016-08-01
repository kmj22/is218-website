<?php

	class uploadController extends controller{
		
		public function __construct(){
						
			$this->html = app::menu();
		}
		
		public function get(){
			$form = new uploadView;
			$form_html = $form->getHTML();
			$this->html .= $form_html;
		}
		public function post(){
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
					
					//get data from csv file
					if (is_file('uploads/'.$name)) {
						$arrResult = array();
						$arrLines = file('uploads/'.$name);
						foreach($arrLines as $line) {
							$arrResult[] = explode( ',', $line);
						}
						
						$length = count($arrResult);
						for ($i = 1; $i < $length; $i++){
							$car = new carModel;
							$car->setMake(str_replace('"','',$arrResult[$i][3]));
							$car->setModel(str_replace('"','',$arrResult[$i][4]));
							$car->setYear($arrResult[$i][0]);
							$car->save();
						}
					}
					header('Location: index.php?controller=homepageController');
					exit;
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
		}
		public function put(){
			
		}
		public function delete(){
			
		}
	}
  
