<?php
class FileUpload
{
	private $_errors;
	public function doUpload($files,$types,$maxSize,$minSize,$path)
	{
		if(in_array($files['type'],$types))
		{
			if($files['size']<$minSize*1024)
			{
				$this->_errors="<p>File too Small</p>";
				return false;
			}
			if($files['size']>$maxSize*1024)
			{
				$this->_errors="<p>File Too Big</p>";
				return false;
			}
			$ext=strrchr($files['name'],".");
			do
			{
				$orgname=md5(rand(0,999999999)).$ext;
				
			}while(file_exists("$path/$orgname"));
			if(move_uploaded_file($files['tmp_name'],"$path/$orgname"))
			{
				return $orgname;
			}
			else
			{
				$this->_errors="Cannot move uploaded file";
				return false;
			}
			
			
		}
		else
		{
			$this->_errors="<p>Invalid File format</p>";
			return false;
		}
	}
	public function doUploadCustom($files,$types,$maxSize,$minSize,$path,$name)
	{
		if(in_array($files['type'],$types))
		{
			if($files['size']<$minSize*1024)
			{
				$this->_errors="<p>File too Small</p>";
				return false;
			}
			if($files['size']>$maxSize*1024)
			{
				$this->_errors="<p>File Too Big</p>";
				return false;
			}
			$ext=strrchr($files['name'],".");
			
			$orgname=$name.$ext;
				
			if(!file_exists("$path/$orgname"))
			{
				if(move_uploaded_file($files['tmp_name'],"$path/$orgname"))
				{
					return $orgname;
				}
				else
				{
					$this->_errors="Cannot move uploaded file";
					return false;
				}
			}
			else
			{
				$this->_errors="File Already Exists";
				return false;
			}
			
		}
		else
		{
			$this->_errors="<p>Invalid File format</p>";
			return false;
		}
	}
	public function doUploadRandom($files,$types,$maxSize,$minSize,$path)
	{
		// Check if file was uploaded without errors
		if (!isset($files) || $files['error'] !== UPLOAD_ERR_OK) {
			$this->_errors="<p>File upload error: " . $this->getUploadErrorMessage($files['error']) . "</p>";
			return false;
		}
		
		$ext=strrchr($files['name'],".");
		if(in_array(strtolower($ext),$types)) // Convert to lowercase for case-insensitive comparison
		{
			if($files['size']<$minSize*1024)
			{
				$this->_errors="<p>File too Small</p>";
				return false;
			}
			if($files['size']>$maxSize*1024)
			{
				$this->_errors="<p>File Too Big</p>";
				return false;
			}
			
			do{
				$orgname=md5(rand())."_".substr(md5(rand()),rand(1,10),rand(10,20)).$ext;
				$filepath = "$path/$orgname";
			}while(file_exists($filepath));
			
			// Ensure the upload directory exists
			if (!is_dir($path)) {
				// Try to create the directory
				if (!mkdir($path, 0755, true)) {
					$this->_errors="<p>Upload directory does not exist and cannot be created: " . $path . "</p>";
					return false;
				}
			}
			
			// Check directory permissions more thoroughly
			if (!is_writable($path)) {
				// Try to make it writable
				if (!chmod($path, 0755)) {
					$this->_errors="<p>Upload directory is not writable and permissions cannot be changed: " . $path . "</p>";
					return false;
				}
			}
			
			// Additional check using file_put_contents to test writability
			$testFile = $path . '/test_write.tmp';
			if (@file_put_contents($testFile, 'test') === false) {
				$this->_errors="<p>Upload directory is not writable (test write failed): " . $path . "</p>";
				return false;
			} else {
				// Clean up test file
				@unlink($testFile);
			}
			
			if(move_uploaded_file($files['tmp_name'],$filepath))
			{
				return $orgname;
			}
			else
			{
				$this->_errors="Cannot move uploaded file to: " . $filepath . " (Check directory permissions)";
				return false;
			}
		}
		else
		{
			$this->_errors="<p>Invalid File format. Allowed formats: " . implode(', ', $types) . "</p>";
			return false;
		}
	}
	
	private function getUploadErrorMessage($errorCode) {
		switch ($errorCode) {
			case UPLOAD_ERR_INI_SIZE:
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			case UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded';
			case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary folder';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk';
			case UPLOAD_ERR_EXTENSION:
				return 'File upload stopped by extension';
			default:
				return 'Unknown upload error';
		}
	}
	
	public function errors()
	{
		return $this->_errors;
	}
	
}
?>