<?php
	class profileController extends controller{
		
		public function __construct(){
						
			$this->html = app::menu();
		}
		
		public function get(){
			$table = new profileView;
			$table_html = $table->getHTML();
			$this->html .= $table_html;
			
		}
		public function post(){

		}
		public function put(){
			
		}
		public function delete(){
			
		}
	}
  
