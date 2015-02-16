<?php
/** uploadmage.class.php
 * Author: Olga Smirnova
 * November 2014
 * 1 -writes a form for creating new riddle
 * 2 -filters and validates all inputs
 * 3 -puts data of new riddle into DB
*/


require_once "../conf/config.php";
require_once CLASS_PATH. '/resizeImage.class.php';


 
class uploadImage
{
    
    private $aFiles = array();
    
    private $original_folder;
    private $thumb_folder;
    private $display_folder;
 
    function __construct()
    {
       $this->aFiles = $this->getTargetFile();

       //folders' paths
       $this->original_folder = IMG_ORIGINAL_PATH; //original img files here
       $this->thumb_folder = IMG_THUMBS_PATH; //thumbs of original img files here
       $this->display_folder = IMG_DISPLAY_PATH; //formated size img files here
    }

/* getTargetFile
 * grab $_FILES values for $target_file
 * @return $_FILES values for $target_file
 */ 
    function getTargetFile()
    {
       $aFiles = array_values($_FILES);
       return $aFiles[0];
    }
 
 
/* checkUploadErrors
 * check if there is any upload error
 * @return T/F
 */
	private function checkUploadErrors()
	{
        return (isset($this->aFiles['error']) && $this->aFiles['error'] == 0);
	}

/* checkExtension
 * @param str, $target_file, the file name
 * we have an array of acceptable file extensions = array(jpg, jpeg, png, gif)
 * get the extension of $target_file and check if it is in that array
 * make sure that extension is in lowercase
 * @return T/F
 */
	private function checkExtension()
	{
		$name = $this->aFiles['name'];
		$aSupported_extensions = array(
		                   				'gif',
		                   				'jpg',
		                   				'jpeg',
		                   				'png',
		   							);
		
		$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		return in_array($ext, $aSupported_extensions);
	}

/* checkRealImage
 * @param str, $target_file,the file name
 * check if image file is a actual image or a fake image
 * @return T/F
 */
	private function checkRealImage()
	{
		return getimagesize($this->aFiles['tmp_name']);
	}
	
/* checkSize
 * @param str, $target_file,the file name
 * check the image size
 * @return T/F
 */
	private function checkSize()
	{
		return $this->aFiles['size'] < 800000 && $this->aFiles['size'] >300;
	}	


/* checkName
 * @param str, $target_file, the file name
 * @param str, $target_dir, the direction for putting uploading file
 * sanitize the file name
 * checkes if there is a file with the same name in $target_dir folder already, and if it is - change the name of uploading file
 * put the uploaded file into the folders of chosen path
 * resize uploadedfile and put the small version into thumb_folder and big one - into display_folder
 * @return T/F
 */
	private function checkNameAndPut()
	{
		$old_name = trim(filter_var($this->aFiles['name'], FILTER_SANITIZE_STRING));
        $target_dir = $this->original_folder;
		if(file_exists($target_dir. $old_name))
		{
			$new_name = uniqid("CP").$old_name;
			$name = $target_dir. basename($new_name);
		}else{
			$name = $target_dir. basename($old_name);
		}
		move_uploaded_file($this->aFiles['tmp_name'], $name);

		$new_name=  substr(strstr($name, "original/"), 9);
		$img = new abeautifulsite\SimpleImage($name); //use resizeImage.class.php to resize img

		if($img->get_height > $img->get_width)
		{
			$img->fit_to_height(350)->save($this->display_folder.$new_name, 90);

		}else{
			$img->fit_to_width(400)->save($this->display_folder.$new_name, 90);

		}

		$img->thumbnail(100)->save($this->thumb_folder.$new_name, 60);
		return $new_name;
	}
   
/* upload
 * call to all validatin and resizinf methods for uploading img
 * @return @new_name - new name of uploaded file (the value of which later will be checked in security.class.php in order to write validation_msg, if somethings wrong, or to upload th new name into DB, if img passed the validation successfully)
 */
    function upload()
    {
        if($this->checkUploadErrors())
        {	if($this->checkExtension())
            {	if($this->checkRealImage())
                {	if($this->checkSize())
                    {   $new_name = $this->checkNameAndPut();
                        if($new_name)
                        {
                        		return $new_name;
                        }else{ $new_name = 5; return $new_name;}
                    }else{ $new_name = 4; return $new_name;}
                }else{ $new_name = 3; return $new_name;}
            }else{ $new_name = 2; return $new_name;}
        }else{ $new_name = 1; return $new_name;}
    }
} // end of class
// EOF uploadImage.class.php