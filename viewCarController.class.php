<?php
	class viewCarController extends controller{
		
		public function __construct(){
			
			$this->html = app::menu();
		}
		
		public function get(){
			$page = new viewCarView;
			$page_html = $page->getHTML();
			$this->html .= $page_html;
		}
		public function post(){

		}
		public function put(){
			
		}
		public function delete(){
			
		}
	}
  
