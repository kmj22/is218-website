<?php

	class addView{
		public function getHTML(){
			$html = '<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">		
			  </head>';
			
			$form = new htmlForm('index.php?controller=addController','POST');
			$form->addInput('Condition','text');
			$form->addInput('VIN','text');
			$form->addInput('Price','text');
			$form->addInput('Image','file','','','file');
			$form->addInput('','hidden', '','','Secret');
			
			$html .= $form->getForm();
			return $html;
		}
	}