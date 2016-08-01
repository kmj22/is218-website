<?php
	class editView{
		public function getHTML(){
			
			//get inventoryID from request
			$id = $_REQUEST['guid'];
			
			//retrieve data for row from inventory table
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				$stmt = $conn->prepare("SELECT * FROM inventory WHERE inventoryID = " . $id);
				$stmt->execute();
				$result = $stmt->fetchAll()[0];				 
			}
			catch(PDOException $e){
				 echo $e->getMessage();
				 return "an error establishing dbconnection has occurred";
			}			
		
			if (empty($result)){
				return;
			}			
			
			//include guid in request so it knows what to update
			$form = new htmlForm('index.php?controller=editController&guid=' . $id,'POST');
			//use car's current info as default values
			$form->addInput('Condition','text', $result['status']);
			$form->addInput('VIN','text', $result['carID']);
			$form->addInput('Price','text', $result['price']);
			$form->addInput('Image','file', '','','file');
			$form->addInput('','hidden', '','','Secret');
			
			$html = $form->getForm();
			
			//add delete button
			$delete = '
				<form action="index.php?controller=editController&guid=' . $id . '" method="POST">
					<input type="submit" name="delete_btn" value="Delete">
				</form>
			';
			
			$html .= $delete;
			$conn = null;
			
			return $html;
		}	
	}