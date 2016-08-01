<?php
	class app{
		public function __construct(){
			session_start();
			$this->checkInactive();
			
			//css
			$page_output = '<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
			 
				<!-- Bootstrap core CSS -->
				<link href="table.css" rel="stylesheet">
				
			  </head>';
			  
			//default controller
			$controller = 'homepageController';
			//if request specifies a controller, use that one
			if (isset($_REQUEST['controller'])){
				$controller = $_REQUEST['controller'];
			}
			
			//instantiate controller and determine type of request
			$route = new $controller;
			$request_method = $_SERVER['REQUEST_METHOD'];
			$route->$request_method();
			
			//generate and produce html
			$page_output .= $route->getHTML();
			echo $page_output;
		}
	
		//get menubar html
		public static function menu(){
		
			$menu = new htmlMenu;
			$menu->addMenuItem('Home','index.php');
			
			//only give user ability to login/register if they aren't signed in
			if (!isset($_SESSION['user'])){
				$menu->addMenuItem('Sign Up','index.php?controller=signUpController');
				$menu->addMenuItem('Login','index.php?controller=loginController');
			}
			
			//only allow logout if logged in
			else{
				$menu->addMenuItem('My Profile','index.php?controller=profileController&user=' . $_SESSION['user']);
				$menu->addMenuItem('Access Log','index.php?controller=accessLogController');
				$menu->addMenuItem('Add Car','index.php?controller=addController');				
				$menu->addMenuItem('Logout','index.php?controller=clearController');
			}
			
			return $menu->getMenu();
		}
		
		public static function checkInactive(){
			if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
				// last request was more than 30 minutes ago
				session_unset();     // unset $_SESSION variable for the run-time 
				session_destroy();   // destroy session data in storage
				header('Location: index.php');
			}
			$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
		}
	}
