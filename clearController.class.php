<?php
	class clearController extends controller{
		
		public function get(){
			
			session_unset();
			header('Location: index.php');
		}
		public function post(){

		}
		public function put(){
			
		}
		public function delete(){
			
		}
	}
  
