<?php

	class signUpView{
		public function getHTML(){
			$html = '<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<title>PHP File Uploader</title>
			 
				<!-- Bootstrap core CSS -->
				<link href="boostrap/css/bootstrap.min.css" rel="stylesheet">
				
			  </head>';
			
			$form = new htmlForm('index.php?controller=signUpController','POST');
			$form->addInput('First Name','text','','','firstName');
			$form->addInput('Last Name','text','','','lastName');
			$form->addInput('Email','text');
			$form->addInput('Password','password');
			$form->addInput('Confirm Password','password', '','','confirmPassword');
			$form->addInput('','hidden', '','','Secret');
			
			$html .= $form->getForm();
			return $html;
		}
	}