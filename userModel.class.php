<?php
	class userModel extends model{
		private $email;
		private $password;
		private $first;
		private $last;
		private $rKey;
		private $salt;

		public function setEmail($email){
			$this->email = $email;
		}
		public function getEmail(){
			return $this->email;
		}		
		public function setPassword($password){
			$this->password = $password;
		}
		public function getPassword(){
			return $this->password;
		}
		public function setFirstName($first){
			$this->first = $first;
		}
		public function getFirstName(){
			return $this->first;
		}
		public function setLastName($last){
			$this->last = $last;
		}
		public function getLastName(){
			return $this->last;
		}
		public function setKey($key){
			$this->rKey = $key;
		}
		public function getKey(){
			return $this->rKey;
		}		
		public function setSalt($salt){
			$this->salt = $salt;
		}
		public function getSalt(){
			return $this->salt;
		}	
		
		public function save(){
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//prepare sql and bind params
				$stmt = $conn->prepare("INSERT INTO users (password, salt, firstName, lastName, email, registerKey)
				VALUES (:password, :salt, :firstName, :lastName, :email, :registerKey)");
				$stmt->bindParam(':password',$this->password);
				$stmt->bindParam(':salt',$this->salt);
				$stmt->bindParam(':firstName',$this->first);
				$stmt->bindParam(':lastName',$this->last);
				$stmt->bindParam(':email',$this->email);
				$stmt->bindParam(':registerKey',$this->rKey);

				$stmt->execute();
				$conn = null;				 
			}
			catch(PDOException $e){
				 echo $e->getMessage();
			}			
		}
	}