<?php
	class carModel extends model{
		private $id;
		private $price;
		private $condition;
		private $image = '';
		
		public function setID($id){
			$this->id = $id;
		}
		public function getID(){
			return $this->id;
		}			
		public function setPrice($price){
			$this->price = $price;
		}
		public function getPrice(){
			return $this->price;
		}		
		public function setCondition($condition){
			$this->condition = $condition;
		}
		public function getCondition(){
			return $this->condition;
		}		
		public function setImage($image){
			$this->image = $image;
		}
		public function getImage(){
			return $this->image;
		}
		public function save(){
			session_start();
			
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//prepare sql and bind params
				$stmt = $conn->prepare("INSERT INTO inventory (carID, createdBy, price, status, image)
				VALUES (:carID, :createdBy, :price, :status, :image)");
				$stmt->bindParam(':carID',$this->id);
				$stmt->bindParam(':createdBy',$_SESSION['user']);
				$stmt->bindParam(':price',$this->price);
				$stmt->bindParam(':status',$this->condition);
				$stmt->bindParam(':image',$this->image);

				$stmt->execute();
				$conn = null;				 
			}
			catch(PDOException $e){
				 echo $e->getMessage();
			}			
		}
	}