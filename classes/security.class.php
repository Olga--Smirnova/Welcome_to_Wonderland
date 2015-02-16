<?php
/**
  * security.class.php
  * Author: Olga Smirnova
  * November 2014
  * 
  * 
  * 
*/
//to make password_hash working on old php versions
require "password.php";

class Security
{
    public $aFiltered;
 
/** filterEverything - sanitizes all input fields
  * @data, array, array of $_POST data
  * returns @aFiltered, array of filtered data
*/
  function filterEverything($data)
  {
      $this->aFiltered = array();
      foreach($data as $key => $value)
      {
          $this->aFiltered[$key] = filter_var(trim($value));  
      }
      return $this->aFiltered;
  }
 
/** checkPOSTdata - validates, sanitizes and filters all input fields
  * stores the success/fail of the test in the $testArray - return T/F.
*/    
  function checkPOSTdata()
  {     
      $this->filterEverything($_POST);
      foreach($this->formArray as $field_name => $aInfo)
      {
          //loop the list of validation methods defined for particular input field
          foreach ($aInfo['valid'] as $index => $func)
          {
              $this->$func($field_name);
          }   
          
          if(in_array(false, $this->testArray[$field_name]))
          {
              return false;
          }else{
              return true;
          } 
      }
            
      if(in_array(false, $this->testArray))
      {
          print_r($this->testArray);
          return false;
      }else{
          print_r($this->testArray);
          return true;
      } 
  } // end checkPOSTda

/** checkEmpty - checks if there's any data in input fields.
  * writes a message which will be used if the form needs to be re-written
  * stores the success/fail of the test in the $testArray - return T/F.
*/
  function checkEmpty($field_name)
  {
      if (empty($this->aFiltered[$field_name])) 
      {
          $this->formArray[$field_name]['msg']= '*field can\'t be empty';
          $this->testArray[$field_name]['checkEmpty'] = false;
      }
      else
      {
          $this->testArray[$field_name]['checkEmpty'] = true;
      }
  }
    
/* checkLength_16 - checks the length
 * @field_name, str, a name of input fild which value's we want to check
 * rewrites validation_msg
 * stores the success/fail of the test in the $testArray - return T/F.
 */
	function checkLength_16($field_name)
	{
        $length = strlen($this->aFiltered[$field_name]);
		if($length<=16 && $length>=3)
        {
            $this->testArray[$field_name]['checkLength_16'] = true;
        }else{
            $this->formArray[$field_name]['msg']= '*3-16 characters required';
            $this->testArray[$field_name]['checkLength_16'] = false;
        }

    }

/* checkLength_18 - checks the length
 * @field_name, str, a name of input fild which value's we want to check
 * rewrites validation_msg
 * stores the success/fail of the test in the $testArray - return T/F.
 */
  function checkLength_18($field_name)
  {
      $length = strlen($this->aFiltered[$field_name]);
      if($length<=18 && $length>=8)
      {
          $this->testArray[$field_name]['checkLength_18'] = true;
      }else{
          $this->formArray[$field_name]['msg']= '*8-18 characters required';
          $this->testArray[$field_name]['checkLength_18'] = false;
      }

  }
    
/** checkLength_60 - checks the length
  * @field_name, str, a name of input fild which value's we want to check
  * rewrites validation_msg
  * stores the success/fail of the test in the $testArray - return T/F.
*/
  function checkLength_60($field_name)
  {
      $length = strlen($this->aFiltered[$field_name]);
      if($length<=60 && $length>=1)
      {
          $this->testArray[$field_name]['checkLength_60'] = true;
      }else{
          $this->formArray[$field_name]['msg']= '*1-60 characters required';
          $this->testArray[$field_name]['checkLength_60'] = false;
      }

  }

/** checkLength_100 - checks the length
  * @field_name, str, a name of input fild which value's we want to check
  * rewrites validation_msg
  * stores the success/fail of the test in the $testArray - return T/F.
*/
  function checkLength_100($field_name)
  {
      $length = strlen($this->aFiltered[$field_name]);
      if($length<=100 && $length>=1)
      {
          $this->testArray[$field_name]['checkLength_100'] = true;
      }else{
          $this->formArray[$field_name]['msg'] = '*100 characters at most';
          $this->testArray[$field_name]['checkLength_100'] = false;
      }

  }

/** checkLength_2000 - checks the length
  * @field_name, str, a name of input fild which value's we want to check
  * rewrites validation_msg
  * stores the success/fail of the test in the $testArray - return T/F.
*/
  function checkLength_2000($field_name)
  {
      $length = strlen($this->aFiltered[$field_name]);
      if($length<=2000 && $length>=1)
      {
          $this->testArray[$field_name]['checkLength_2000'] = true;
      }else{
          $this->formArray[$field_name]['msg']= '*2000 characters at most';
          $this->testArray[$field_name]['checkLength_2000'] = false;
      }

  }

/** checkTextarea - checks characters of input
  * @field_name, str, a name of input fild which value's we want to check
  * rewrites validation_msg
  * stores the success/fail of the test in the $testArray - return T/F.
*/
	function checkTextarea($field_name)
	{
	    if(preg_match('/^[a-zA-Z0-9?$@#()\'!,+\-=_:.&€£*%\s]+$/', $field_name))
	    {
	        $this->testArray[$field_name]['checkTextarea'] = true;
	    }else{
	        $this->formArray[$field_name]['msg']='*A-z/0-9/,.!?()+=-_ only';
	        $this->testArray[$field_name]['checkTextarea'] = false;
	    }
	}

/** checkName - checks characters of input (username, password, titles)
  * @field_name, str, a name of input fild which value's we want to check
  * rewrites validation_msg
  * stores the success/fail of the test in the $testArray - return T/F.
*/
  function checkName($field_name)
  {
      if(preg_match('/^[a-zA-Z0-9_-][\s\S]+$/', $this->aFiltered[$field_name]))
      {
          $this->testArray[$field_name]['checkName'] = true;
      }else{
          $this->formArray[$field_name]['msg']='*A-z/0-9/hyphen/underscore';
          $this->testArray[$field_name]['checkName'] = false;
      }
  }


/** checkMail - checks characters of input
  * @field_name, str, a name of input fild which value's we want to check
  * rewrites validation_msg
  * stores the success/fail of the test in the $testArray - return T/F.
*/
  function checkMail($field_name)
  {
      if(!filter_var($this->aFiltered[$field_name], FILTER_VALIDATE_EMAIL))
      {
          $this->formArray[$field_name]['msg']='*please, enter a valid email address';
          $this->testArray[$field_name]['checkMail'] = false;
      }else{
      	$this->testArray[$field_name]['checkMail'] = true;
      }

  }

/** checkPic - writes validation messages for different type of errors for loading image
  * @img, str, new name of uploaded image
  * @field_name, str, a name of input fild which value's we want to check
  * writes validation_msg
  * stores the success/fail of the test in the $testArray - return T/F.
*/
    function checkPic($img, $field_name)
    {
        switch($img)
        {
            case 1: 
            case 5:
                    $this->formArray[$field_name]['msg'] = '*sorry, an error occurred';
                    $this->testArray[$field_name]['checkPic'] = false;
                    return false;
                    break;
            case 2: 
            case 3: 
                    $this->formArray[$field_name]['msg'] = '*choose a valid image, please';
                    $this->testArray[$field_name]['checkPic'] = false;
                    return false;
                    break;
            case 4: $this->formArray[$field_name]['msg'] = '*image size is incorrect';
                    $this->testArray[$field_name]['checkPic'] = false;
                    return false;
                    break;
            default: $this->testArray[$field_name]['checkPic'] = true;
                    return true;
        }
    }

/** nameExist - checks if such name (email or username, for example) are already exists in DB
  * @field_name, str, a name of input fild which value's we want to check
  * writes validation_msg
  * stores the success/fail of the test in the $testArray - return T/F.
*/
	function nameExist($field_name)
	{
		$this->formArray[$field_name]['msg'] = '*already exists';
		$this->testArray[$field_name]['nameExist'] = false;
	}


/** PrepStatement
 * requiers name of column to find(str) and name of table to search(str)
 * returns the prepered statement
 */
  function PrepStatement($column, $table)
  {   
        $input_user = strtolower($this->aFiltered['username']);
        $DB_output = 'SELECT '. $column.' FROM '. $table. ' WHERE username = "'. $input_user. '"' ;
	
        return $DB_output;
	}	
	
/** logIn
  * @user, str, input from username field
  * @userDB, str, username from DB
  * @pass, str, input from pasword field
  * @hased_pass, str, hased password from DB
  * returns T/F
*/
	function logIn($pass, $hashed_pass)
	{
		return password_verify($pass, $hashed_pass);
	}

/** createPassword
  * creates new hased password for new user (to be put into DB)
  * @pass, str, input password
  * returns @hased_password, str, hashed input password
*/
    function createPassword($pass)
    {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        return $hashed_password;
    }
		
} // end of class
// EOF security.class.php