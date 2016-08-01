<?php	
	class htmlTable {
		private $table;

		public function addTableItem($obj)  {
			$this->table[] = $obj;
		}
      
		public function getTable() {
			$tableHTML = '<table class="datagrid">';
			//use get_object_vars() in both inner foreach loops if $obj will be an array of objects
			//instead of a 2D array
			
			//header row - displays properties of the obj
			$tableHTML .= '<tr>';
				foreach($this->table[0] as $tableProperty=>$tableValue) {
					 $tableHTML .= '<th>' . $tableProperty . '</th>';
				}
			$tableHTML .= '</tr>';
			
			//content rows
			foreach($this->table as $tableItem) {
				$tableHTML .= '<tr>';
				foreach($tableItem as $tableProperty=>$tableValue){
					$tableHTML .= '<td>' . $tableValue . '</td>';
				}
				$tableHTML .= '</tr>';
			}
			$tableHTML .= '</table>';
			return $tableHTML;
		}
	}   