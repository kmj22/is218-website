<?php
	class homepageController extends controller{
		
		public function __construct(){
					
			$this->html = app::menu();
			//app::checkInactive();
		}
		
		public function get(){		
			//registration messages
			if (isset($_REQUEST['message'])){
				if ($_REQUEST['message'] == 'email'){
					echo 'Activation email sent to ' . $_REQUEST['address'] . '.  Please check your email to activate your account.';
				}
				else if ($_REQUEST['message'] == 'confirm'){
					$key = $_REQUEST['key'];
					//check if key is stored in db, and activate account
					if ($this->register($key)){
						echo 'Your account has been successfully activated!';
					}
				}
			}
			
			$table = new tableView;
			$table_html = $table->getHTML();
			$this->html .= $table_html;			
			
		}
		public function post(){
			
		}
		public function put(){
			
		}
		public function delete(){
			
		}
		public function register($key){
			//retrieve data for row from user table
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				$stmt = $conn->prepare("SELECT registerKey FROM users WHERE registerKey = '" . $key . "'");
				
				$stmt->execute();
				$result = $stmt->fetchAll()[0];	
				if ($result == null){
					echo 'Error: invalid activation URL';
					return false;
				}
				
				$sql = "UPDATE users SET confirm=1 WHERE registerKey='" . $key . "'";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				$conn = null;
				return true;
			}
			catch(PDOException $e){
				 echo $e->getMessage();
				 return false;
			}		
		}
	}
