<?php
	class accessLogView{
		public function getHTML(){
			
			
			//retrieve data from inventory table
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				$stmt = $conn->prepare("SELECT date, success FROM attempts
										WHERE userID =" . $_SESSION['user']);
				$stmt->execute();
				$result = $stmt->fetchAll();				 
			}
			catch(PDOException $e){
				 echo $e->getMessage();
				 return "an error establishing dbconnection has occurred";
			}			
		
			if (empty($result)){
				return;
			}
			
			//create new table
			$table = new htmlTable;
			
			//add each car's info as a row in the table
			foreach ($result as $key => $value){ 
				$tableRow = array('Date' => $value['date'],
							'Result' => $value['success']);
				$table->addTableItem($tableRow);
			}
			$conn = null;
			
			$html = $table->getTable();
			return $html;
		}		
	}