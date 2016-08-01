<?php
	abstract class model{
		protected $guid;
		public function __construct(){
			session_start();
      //session_unset();
			$this->guid = uniqid();
		}
		public function save(){
			$_SESSION[$this->guid] = (array) $this;
		}
	}