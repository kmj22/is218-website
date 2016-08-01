<?php

	class signUpController extends controller{
		
		public function __construct(){
						
			$this->html = app::menu();
		}
		
		public function get(){
			if (isset($_REQUEST['message'])){
				if ($_REQUEST['message'] == 1){
					echo 'Error: Passwords did not match';
				}				
				else if ($_REQUEST['message'] == 2){
					echo 'Error: Please fill in all fields';
				}				
				else if ($_REQUEST['message'] == 3){
					echo 'Error: Invalid Email';
				}
			}
			
			$form = new signUpView;
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
			$_POST['firstName'] = strip_tags($_POST['firstName']);		
			$_POST['lastName'] = strip_tags($_POST['lastName']);		
			$_POST['Email'] = strip_tags($_POST['Email']);		
			$_POST['Password'] = strip_tags($_POST['Password']);		
			$_POST['confirmPassword'] = strip_tags($_POST['confirmPassword']);		
		
			//if password and confirm password not the same
			if (strcmp($_POST['Password'],$_POST['confirmPassword']) != 0){
				header('Location: index.php?controller=signUpController&message=1');	
				return;
			}
			//if all fields not filled
			if (empty($_POST['firstName']) or empty($_POST['lastName']) or
				empty($_POST['Email']) or empty($_POST['Password'])){
				header('Location: index.php?controller=signUpController&message=2');	
				return;				
			}
			
			//activation key
			$key = md5($_POST['firstName'] . $_POST['lastName'] . $_POST['Email']);
			
			//send activation email
			$success = $this->sendMail($_POST['Email'],$_POST['firstName'] . ' ' . $_POST['lastName'],$key);		

			//incorrect email
			if (!$success){
				header('Location: index.php?controller=signUpController&message=3');	
				return;				
			}
			
			$iv = mcrypt_create_iv(10, MCRYPT_DEV_RANDOM);
			$hashPassword = hash('sha256',$iv . $_POST['Password']);
			
			$user = new userModel;
			$user->setPassword($hashPassword);
			$user->setFirstName($_POST['firstName']);
			$user->setLastName($_POST['lastName']);
			$user->setEmail($_POST['Email']);
			$user->setKey($key);
			$user->setSalt($iv);
			$user->save();
			
			//alert user to activation email
			header('Location: index.php?message=email&address=' . $_POST['Email']);
		}
		public function put(){
			
		}
		public function delete(){
			
		}
		public function sendMail($to, $name, $key){	
			require("class.phpmailer.php");			
			require("class.smtp.php");			
						
			$mail = new PHPMailer;

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  					// Specify main SMTP server
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'kjaremko13@gmail.com';                 // SMTP username
			$mail->Password = 'rykymy51';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption,
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->setFrom('donotreply@kevynscars.com', 'Kevyn\'s Cars');
			$mail->addAddress($to, $name);     						// Add a recipient

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Activation Link';
			$mail->Body    = 'Dear ' . $name . ',<br />' .
							'Thank you for registering an account at Kevyn\'s Cars.  Please click the following link to complete your activation:' . '<br />' . '<a href="https://web.njit.edu/~kmj22/is218/index.php?message=confirm&key=' . $key . '">Activate Your Account</a>';

			return ($mail->send());
		}
	}
  
