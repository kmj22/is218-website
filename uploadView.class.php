<?php

	class uploadView{
		public function getHTML(){
			$html = '			 
			  <body>
			 
				<!-- Static navbar -->
				<div class="navbar navbar-default navbar-static-top">
				  <div class="container">
					<div class="navbar-header">
					  <a class="navbar-brand" href="index.php">PHP File Uploader</a>
					</div>
				  </div>
				</div>
			 
			 
				<div class="container">
			 
					  <div class="row">
						<div class="col-lg-12">
						   <form class="well" action="index.php?controller=uploadController" method="post" enctype="multipart/form-data">
							  <div class="form-group">
								<label for="file">Select a file to upload</label>
								<input type="file" name="file">
								<p class="help-block">Only jpg,jpeg,png and gif file with maximum size of 1 MB is allowed.</p>
							  </div>
							  <input type="submit" class="btn btn-lg btn-primary" value="Upload">
							</form>
						</div>
					  </div>
				</div> <!-- /container -->
			 
			  </body>';
			return $html;
		}
	}