<?php
	class accessLogController extends controller{
		
		public function __construct(){
			
			$this->html = app::menu();
		}
		
		public function get(){
			$table = new accessLogView;
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
  
