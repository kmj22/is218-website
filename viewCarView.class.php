<?php

	class viewCarView{
		public function getHTML(){
			
			
			//use guid from request to get inventoryID
			$id = $_REQUEST['guid'];
			
			//retrieve data for row from inventory table
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				//$stmt = $conn->prepare("SELECT * FROM inventory WHERE inventoryID = " . $id);
				$stmt = $conn->prepare("
					SELECT inventory.carID, inventory.status, inventory.createdBy, inventory.createdOn, inventory.price, inventory.image, users.email
					FROM inventory INNER JOIN users
					ON inventory.createdBy = users.userID
					WHERE inventoryID = " . $id);
				
				$stmt->execute();
				$result = $stmt->fetchAll()[0];				 
			}
			catch(PDOException $e){
				 echo $e->getMessage();
				 return "an error establishing dbconnection has occurred";
			}			

			$table = new htmlTable;

			//use VIN to get make, model, year
			$carInfo = $this->vinDecode($result['carID']);
			
			//add data from edmunds api
			foreach ($carInfo as $key => $value){
				$tableRow = array('' => ucfirst($key), ' ' => $value);
				$table->addTableItem($tableRow);
			}

			//get the rest from inventory table
			$tableRow = array('' => 'Condition', ' ' => $result['status']);
			$table->addTableItem($tableRow);			
			$tableRow = array('' => 'Merchant', ' ' => '<a href="index.php?controller=profileController&user=' . $result['createdBy'] . '">'.$result['email'] . '</a>');
			$table->addTableItem($tableRow);			
			$tableRow = array('' => 'Date Posted', ' ' => $result['createdOn']);
			$table->addTableItem($tableRow);
			$tableRow = array('' => 'Price', ' ' => '$' . $result['price']);
			$table->addTableItem($tableRow);	
			$tableRow = array('' => 'Image', ' ' => $result['image']);
			$table->addTableItem($tableRow);			
			
			$conn = null;
			$html = $table->getTable();
			return $html;
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
			
      if (!isset($response['errorType'])){	
  			$carData['make'] = $response['make']['name'];
  			$carData['model'] = $response['model']['name'];
  			$carData['year'] = $response['years'][0]['year'];
        return $carData;
      }
      return array('make' => '', 'model' => '', 'year' => '');
		}
	}