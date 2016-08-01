<?php
	class htmlForm {
			private $form; 
			private $action; 
			private $method;
			
			public function __construct($action, $method){
				$this->action = $action;
				$this->method = $method;
			}

			public function addInput($label, $type, $value='', $checked='', $name='')  {
				if ($name == ''){
					$name = $label;
				}
				$input  = '<label for="' . $name . '">' . $label . '</label>';
				$input .= '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" ' . $checked . '><br>';
				$this->form[] = $input;
			}
		  
			public function getForm() {
				//you need to add the action and method attributes
				$formHTML = '<form action="' . $this->action . '" method="' . $this->method . '" enctype="multipart/form-data">';
				foreach($this->form as $formItem) {
					$formHTML .= $formItem;
				}
				$formHTML .= '<input type="submit" name="submit_btn" value="Submit"></form>';
				return $formHTML;
			}
	}