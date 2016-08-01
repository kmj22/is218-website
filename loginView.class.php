<?php

	class loginView{
		public function getHTML(){
			$html = '<form action="index.php?controller=loginController" method="POST" enctype="multipart/form-data">
			<label for="Email">Email</label><input type="text" name="Email" value="" ><br>
			<label for="Password">Password</label><input type="password" name="Password" value="" ><br>
			<label for="Secret"></label><input type="hidden" name="Secret" value="" ><br>';
			
			if (isset($_SESSION['captcha']) and $_SESSION['captcha']){
				$html.='<img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
				<input type="text" name="captcha_code" size="10" maxlength="6" />';
			}
			
			$html.='<input type="submit" name="submit_btn" value="Submit"></form>';
			
			/*
			$form = new htmlForm('index.php?controller=loginController','POST');
			$form->addInput('Email','text');
			$form->addInput('Password','password');
			$form->addInput('Secret','hidden');
			$html .= $form->getForm();
			*/
			
			return $html;
		}
	}