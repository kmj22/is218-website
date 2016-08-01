<?php
	class htmlMenu {
		private $menu;

		public function addMenuItem($title, $href, $target='_self'){
			$link = '<a href="' . $href . '" target="' . $target . '">' . $title . '</a>';			
			$this->menu[] = $link;
		}
      
		public function getMenu(){
			//adding the menu unorderd list, this could be a form but you need to add the action and method attributes
			$menuHTML = '<ul>';
			foreach($this->menu as $menuItem) {
				$menuHTML .= '<li>' . $menuItem . '</li>';
			}
			$menuHTML .= '</ul>';
			return $menuHTML;
		}
	}