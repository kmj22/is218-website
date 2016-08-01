<?php
	class profileView{
		public function getHTML(){
			
			
			//request tells which user's profile to display
			$id = $_REQUEST['user'];
			
			//retrieve data for row from inventory table
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				
				//get user info
				$stmt = $conn->prepare("SELECT * FROM users WHERE userID = " . $id);
				$stmt->execute();
				$userInfo = $stmt->fetchAll()[0];					
				
				//get cars that the user is selling
				$stmt = $conn->prepare("SELECT * FROM inventory WHERE createdBy = " . $id);
				$stmt->execute();
				$userCars = $stmt->fetchAll();				 
			}
			catch(PDOException $e){
				 echo $e->getMessage();
				 return "an error establishing dbconnection has occurred";
			}			
			
			//table for info about seller
			$infoTable = new htmlTable;
			
			//get the rest from inventory table
			$tableRow = array('' => 'User', ' ' => $userInfo['email']);
			$infoTable->addTableItem($tableRow);			
			$tableRow = array('' => 'Name', ' ' => $userInfo['firstName'] . ' ' . $userInfo['lastName']);
			$infoTable->addTableItem($tableRow);			
			
			//if the user has any cars
			if (!empty($userCars)){

				//table for cars seller is selling
				$carTable = new htmlTable;
			
				//add each car's info as a row in the table
				foreach ($userCars as $key => $value){ 
					//use VIN to get make, model, and year
					$carInfo = $this->vinDecode($value['carID']);
					
					//user should only be able to modify car details if it's theirs
					$myCar = (isset($_SESSION['user']) and 
						strcmp($value['createdBy'], $_SESSION['user']) == 0);
				
					$tableRow = array('Make' => $carInfo['make'], 
								'Model' => $carInfo['model'], 
								'Year' => $carInfo['year'],
								'Condition' => $value['status'],
								'Date' => $value['createdOn'],
								'Price' => '$' . $value['price'],
								'Image' => '',
								
								//view car
								'' => '<a href="index.php?controller=viewCarController&guid=' . $value['inventoryID'] . '">View</a>', 
								
								//edit car
								' ' => $myCar? '<a href="index.php?controller=editController&guid=' . $value['inventoryID'] . '">Edit</a>' : '');
					//make the thumbnail, if an image was uploaded
					if (isset($value['image']) and $value['image'] !== ''){
						//echo 'value is...' . $value['image'] . '....';
						//echo 'tr....' . ($value['image'] !== 'n/a') . 't...';
						$src = substr($value['image'],10,-2);
						$dest = 'uploads/thumbnail_'.$key.'.jpeg';
						$this->make_thumb($src,$dest,40);
						$tableRow['Image'] = '<img src="'.'uploads/thumbnail_'.$key.'.jpeg'.'">';
					}
					
					$carTable->addTableItem($tableRow);
				}
			}	
				
			$conn = null;
			
			$html = $infoTable->getTable() . '</ br><h2>Cars</h2></ br>';
			if (isset($carTable)){
				$html .= $carTable->getTable();
			}
			return $html;
		}		
		
		public function make_thumb($src, $dest, $desired_width) {
		
			/* read the source image */
			$source_image = imagecreatefromjpeg($src);
			$width = imagesx($source_image);
			$height = imagesy($source_image);
			
			/* find the "desired height" of this thumbnail, relative to the desired width  */
			$desired_height = floor($height * ($desired_width / $width));
			
			/* create a new, "virtual" image */
			$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
			
			/* copy source image at a resized size */
			imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
			
			/* create the physical thumbnail image to its destination */
			imagejpeg($virtual_image, $dest);
		}
		
		public function vinDecode($vin){
			$ch = curl_init();
			$url = "https://api.edmunds.com/api/vehicle/v2/vins/" .	$vin . "?fmt=json&api_key=u48d7aetdk4yz6pwqnzd62ez";

			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$output = curl_exec($ch);
			curl_close($ch); 

			$response = json_decode($output, true);
			
			$carData['make'] = $response['make']['name'];
			$carData['model'] = $response['model']['name'];
			$carData['year'] = $response['years'][0]['year'];
			
			return $carData;
		}
	}