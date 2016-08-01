<?php

	class loginController extends controller{
		
		public function __construct(){
			$this->html = app::menu();		
			
		}
		
		public function get(){
			$form = new loginView;
			$form_html = $form->getHTML();
			$this->html .= $form_html;
		}
		public function post(){		
			//Honeypot
			if ($_POST['Secret'] !== ''){
				echo 'Nice try, robot';
				return;
			}
			
			//clean up potention html injections
			$_POST['Email'] = strip_tags($_POST['Email']);
			$_POST['Password'] = strip_tags($_POST['Password']);
			
			//retrieve data for row from inventory table
			$host = "sql1.njit.edu";
			$username = "kmj22";
			$password = "YcsZZWDJ";
			$dbname = "kmj22";

			try{
				$conn = new PDO("mysql:host={$host};dbname={$dbname}",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				
				$stmt = $conn->prepare("SELECT userID, password, salt, confirm FROM users WHERE email = '" . $_POST['Email'] . "'");
				$stmt->execute();
				$result = $stmt->fetchAll();
				
				//user not found
				if (empty($result)){
					echo 'Incorrect email or password';
					$conn = null;
					return;
				}
				else{
					$result = $result[0];
				}
				
				//count failed attempts
				$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM attempts attmpt
										WHERE attmpt.date >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
										AND userID = " . $result['userID'] . "
										AND success = 'failure'");
				$stmt->execute();
				$attempts = $stmt->fetchAll()[0]['cnt'];

				//check if too many failed attempts
				if ($attempts > 4){
					echo 'Sorry, too many incorrect login attempts.  You can try to login again in an hour.';
					$conn = null;
					return;
				}
				//check captcha
				else if ($attempts > 1){
					if (isset($_SESSION['captcha']) and $_SESSION['captcha']){
						include_once '/afs/cad.njit.edu/u/k/m/kmj22/public_html/is218/' . '/securimage/securimage.php';
						
						$securimage = new Securimage();

						if ($securimage->check($_POST['captcha_code']) == false) {
							echo "The security code entered was incorrect.<br /><br />";
							echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
							exit;
						}
					}
					else{
						$_SESSION['captcha'] = true;	
					}
				}
				
				
			}
			catch(PDOException $e){
				 echo $e->getMessage();
			}			
			
			$success= 'success';
			
			//check password
			$salt = $result['salt'];
			$pass = $_POST['Password'];
			$hashPassword = hash('sha256',$salt . $pass);
						
			if (strcmp($result['password'],$hashPassword) != 0){//incorrect password
				echo 'Incorrect email or password';
				$success = 'failure';
			}
			
			//is account activated?			
			else if($result['confirm'] == 0){
				echo 'Sorry, your account has not been activated yet.  Please check your email for an activation link.';
			}	
			
			//success, store user in session
			else{
				$_SESSION['user'] = $result['userID'];
				header('Location: index.php');
			}
			
			//log an attempt
			$stmt = $conn->prepare("INSERT INTO attempts (userID, success)
			VALUES (:userID, :success)");
			$stmt->bindParam(':userID',$result['userID']);
			$stmt->bindParam(':success',$success);
			$stmt->execute();			
			$conn = null;				 
		}
		public function put(){
			
		}
		public function delete(){
			
		}
	}
  
