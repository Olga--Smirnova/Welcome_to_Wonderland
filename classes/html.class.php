<?php
/**
  * html.class. php
  * Author: Olga Smirnova
  * November 2014
  * writes HTML structure elements
*/

class makeHTML extends Security
{   
    protected $formArray;

    function __construct($aFormFields='')
    {
        $this->formArray = $aFormFields;
    }

/** makeHeader - writes HTML header
  * @title, str, title from DB
  * @description, str, description from DB
  * $headline,str, h1 from DB
  * @subline, str, h2 from DB
*/   
    function makeHeader($title, $description, $headline, $subline)
    {
        return '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <title>'. $title. '</title>
                    <meta name="description" content="'. $description.'">
    
                    <link rel="stylesheet" type="text/css" href="assets/font-awesome-4.2.0/css/font-awesome.min.css">
                    <link href=\'http://fonts.googleapis.com/css?family=Open+Sans:400,600,300\' rel=\'stylesheet\' type=\'text/css\'>
                    <link rel="stylesheet" type="text/css" href="'.  CSS_PATH.'/styles.css'. '">
                </head>
                <body id="top">'. "\n".
                '<!--******************************************************************************************************************-->'. "\n".
                        '<!-- HEADER + NAVIGATION -->'. "\n".
                '<!--******************************************************************************************************************-->'. "\n". "\t".
                    '<header>
                        <div class="gradient"></div>
                            <h1>'. $headline. '</h1>
                            <h2>'. $subline. '</h2>';
    }

/** makeNavigation - writes HTML li for nav
  * @arr, array , array of ids page_names(example.php) and menu_names from DB
  * 	make this array numeric to operate easely: [0]=>id from DB, [1]=>menu_name, [2]=>page_name from DB
  * @active, str, menu_name of this page, need it to change the style of nav li, when li:active
*/
    function makeNavigation($arr, $active='')
    {
        $li ='';
        foreach($arr as $key=>$aData)
        { 
            if($active==$aData[1])
            {
                $li .= '<li class="active"><a class="active" href="'. $aData[2]
                . '">'. $aData[1]. '</a></li>'. "\n". "\t". "\t". "\t";
            }else{
                $li .= '<li><a href="'. $aData[2]
                . '">'. $aData[1]. '</a></li>'. "\n". "\t". "\t". "\t";
            }
        }
        return $li. '<li><a href="logout.php">Log out</a></li>
                <li id="searchbox">
                    <form id="search_site">
                        <input type="search" name="search" placeholder="Search" id="whatToSearch" size="23" results="4">
                        <button type="submit" id="search_btn" name="search_btn" value="search_me"><i class="fa fa-search"></i></button>
                    </form>
                </li>';
    }

/** makeNavigationHidden - hides navigation (need it for pages, for wich $_SESSION['logged']=F: landing page, sign in page, goodbye page etc. )
  * @arr, array , array of ids and menu_names from DB
  * make this array numeric to operate easely: [0]=>id from DB, [1]=>menu_name from DB
*/
    function makeNavigationHidden($arr)
    {
        $li ='';
        foreach($arr as $key=>$aData)
        {
            $li .= "\t". "\t". '<li style="visibility: hidden"><a href="'. $aData[2]
                . '">'. $aData[1]. '</a></li>'. "\n";
        }
        return $li. '<li style="visibility: hidden"><a href="logout.php">Log out</a></li>
                <li style="visibility: hidden" id="searchbox">
                    <form>
                        <input type="search" name="search" placeholder="Search" size="23" results="4">
                        <button type="submit" id="search_btn" name="subm_search"><i class="fa fa-search"></i></button>
                    </form>
                </li>';
    }

/** makeNavigationAdm - writes HTML li for admin options ul
  * @arr, array , array of ids page_names(example.php) and menu_names from DB
  * @active, str, script path (= $_SERVER['PHP_SELF'] for each particular page)
*/
    function makeNavigationAdm($arr, $active)
    {
        $li = "\n". "\t";
        foreach($arr as $index=>$name)
        {   
        	//get the script name of page
            $aScript_name = explode('/', $active);
            $Script_name = substr($aScript_name[4], 0, -4);

            //if that script name of page = $name, than change the style of li to "active"
            if($Script_name==$name)
            {   
               $li .= '<li class="active"><a class="active" href="'. $name. '.php">'. $name. '</a></li>'. "\n". "\t";
            }else{
               $li .= '<li><a href="'. $name. '.php">'. $name. '</a></li>'. "\n". "\t";
            }
        }
        return $li;
    }

/** adminoptList - writes <li> for list of posts/riddles/users for admin to update
  * @num, str, id of post/riddle/user as it is in DB
  * @title, str, title of that post/riddle/first 50 letters of comment
*/
    function adminoptList($num, $title='')
    {
        $li = '<li><a href="'. $_SERVER['SCRIPT_NAME']. '?update='. $num. '"><h4>Update-> '. $title. '</h4></a></li>'. "\n". "\t";
        return $li;
    }  
  

/** drawRibbon - writes HTML for 4-color ribbon
*/
    function drawRibbon()
    {
        return '<div id="ribbon" class="cf"> <!-- the striped ribbon -->
                        <div class="one"></div>
                        <div class="two"></div>
                        <div class="three"></div>
                        <div class="four"></div>
        
                        <div class="one"></div>
                        <div class="two"></div>
                        <div class="three"></div>
                        <div class="four"></div>
        
                        <div class="one"></div>
                        <div class="two"></div>
                        <div class="three"></div>
                        <div class="four"></div>
        
                        <div class="one"></div>
                        <div class="two"></div>
                        <div class="three"></div>
                        <div class="four"></div>
        
                        <div class="one"></div>
                        <div class="two"></div>
                        <div class="three"></div>
                        <div class="four"></div>
                    </div>';
    }   
    
/** defaultPost - writes default post from admin for new users
  * @adminname, str, admin's username
  * @admin_statement, str, admin's statement
  * @username, str, username
  * @time, str, date_entered the default post
*/
    function defaultPost($adminname, $username, $time, $admin_statement)
    {
        return "\n". "\t"
                .'<section class="basic default_post">
                    <div class="grey_bcgr">
                        <div class="avatar_img"><img src="assets/images/thumbs/rabbit.jpg"/></div>
                        <div class="top_post">
                            <i class="fa fa-user-md"></i><h4>'. $adminname. '</h4>
                            <h4>'. $admin_statement. '</h4>
                        </div>
                        <p class="timestamp">'. $time. '</p>
                    </div>
                
                    <h3>HELLO, '. $username.  '</h3>
                    <p class="post">Congratulations! You\'ve created an account successfully. Now you can read the feed or write your very first post. Please, be polite and remember: begin at the beginning, and go on till you come to the end, then stop.</p>
                
                </section>'. "\n";
    }

/** post - writes users' posts for profile page (no user information - such as avatar and statement - displaying)
  * @username, str, username
  * @timestamp, str, date_entered the default post
  * @title, str, post title
  * @post, str, post
  * @comment_num, int, number of comments (default = 0)
  * @picture, str, name of an image in the post (opt, default = NULL)
  * @post_id, str, id of the post as it is in DB
*/
    function post($username, $timestamp, $title, $post, $comment_num, $picture, $post_id)
    {
        $user_post = '<section class="basic">'. "\n"
                    .'<div class="grey_bcgr">'. "\n". "\t"
                    .'<i class="fa fa-user"></i><h4>'. $username. '</h4>'. "\n"
                    .'<p class="timestamp">'. $timestamp. '</p>'. "\n"
                    .'</div>'. "\n";
        if($picture!= NULL )
        {
            $user_post .= '<img src="'. IMG_DISPLAY_PATH. $picture. '"/ class="img" alt="image">';
        }
        $user_post .= '<h3>'. $title. '</h3>'
                    .'<p class="post">'. nl2br($post). '</p>'. "\n"
                    .'<div class="grey_bcgr">'. "\n". "\t"
                    .'<ul>'. "\n". "\t"
                    .'<li><a href="leavecomment.php?id='. $post_id.'">Leave a comment</a></li>'. "\n"
                    .'<li><a href="leavecomment.php?id='. $post_id. '">'.  $comment_num. ' comments</a></li>'. "\n"
                    .'</ul>'. "\n"
                    .'</div>'. "\n"
                    .'</section>';
        return $user_post;
    }


/** posts - writes users' posts (for friends page, leavecomment page: user information - such as avatar and statement - displaying)
  * @post_id, str, post of id as it is in tbl_posts
  * @username, str, username
  * @statement, str, user's statement
  * @timestamp, str, date_entered the default post
  * @avatar, str, user's avatar
  * @picture, str, name of an image in the post (opt, default = NULL)
  * @post_title, str, post title
  * @post, str, post
  * @comment_num, int, number of comments (default = 0)
  * @user_id, str, user's id as it is in tbl_users
  * @answer, $deadline strs, answer and deadline for admin's riddles
  * @nolink - either '' (=> creates normal "Leave comment" link) or 1 (=> creates not clickable "Leave comment" link, need that for the situation when user is alredy pressed "Leave comment" link)
*/
    function posts($post_id, $username, $statement, $timestamp, $avatar, $picture, $post_title, $post, $comment_num, $user_id, $answer='', $deadline='', $nolink='')
    {
        $user_post = '<section class="basic freindspage_post">'. "\n"
                    .'<div class="grey_bcgr">'. "\n". "\t"
                    .'<div class="avatar_img"><img src="'. IMG_THUMBS_PATH. $avatar. '" alt="image_userpic"/>'
                    .'</div>'. "\n". "\t";

        //if it's an admin's post, we need to color username and statement differently
        if($user_id==1)
        {
            $user_post .= '<div class="wr top_post">'
                        .'<i class="fa fa-user-md"></i><h4>'. $username. '</h4>'. "\n";
        }else{
            $user_post .= '<div class="top_post">'
                        .'<i class="fa fa-user"></i><h4>'. $username. '</h4>'. "\n";
        }            
         
        $user_post .= '<h4>'.  $statement. '</h4>'. "\n"
                    .'</div>'. "\n"
                    .'<p class="timestamp">'. $timestamp. '</p>'. "\n"
                    .'</div>'. "\n";
                                        
        if($picture!= NULL )
        {
            $user_post .= '<img src="'. IMG_DISPLAY_PATH. $picture. '"/ class="img" alt="image">';
        }
        
        $user_post .= '<h3>'. $post_title. '</h3>'. "\n"
                    .'<p class="post">'. nl2br($post). '</p>'. "\n";
        
        //if it's an admin's post, we need to write the Answer section (=riddle)                
        if($user_id==1)
        {   
            $user_post .= '<h3>ANSWER</h3>'. "\n";
                
            $current_timestamp = time(); //current timestamp
            $deadline_timestamp = strtotime($deadline); //Unix timestamp for a deadline

            //if deadline passed, display the answer, otherwise - display the deadline
            if($current_timestamp >= $deadline_timestamp)
            {
                $user_post .= '<p class="post">'. nl2br($answer). '</p>'. "\n";
            }else{
                $user_post .= '<p class="post">is coming on '. $deadline .'</p>'. "\n";
            }
        }
                        
        $user_post .= '<div class="grey_bcgr">'. "\n". "\t"
                    .'<ul>'. "\n". "\t";
        
        if($nolink==1)
        {
            $user_post .= '<li><a href="#" style="cursor: default; color: rgb(1, 181, 154);">Leave a comment</a></li>'. "\n"
                        .'<li><a href="#" style="cursor: default; color: rgb(1, 181, 154);">'. $comment_num. ' comments</a></li>'. "\n";
        }else{
            $user_post .= '<li><a href="leavecomment.php?id='. $post_id. '">Leave a comment</a></li>'. "\n"
                    .'<li><a href="leavecomment.php?id='. $post_id. '">'. $comment_num. ' comments</a></li>'. "\n";
        }

        $user_post .='</ul>'. "\n"
                    .'</div>'. "\n"
                    .'</section>'. "\n";

        return $user_post;
    }

/** postRiddle - writes admin's riddles
  * @username, str, username
  * @timestamp, str, date_entered the default post
  * @title, str, post title
  * @post, str, post
  * @comment_num, int, number of comments (default = 0)
  * @picture, str, name of an image in the post (opt, default = NULL)
  * @answer, str, an answer to the riddle
  * @deadline, str, deadline, when answer will bw displayed in the post
*/
    function postRiddle($username, $timestamp, $title, $post, $comment_num, $picture, $answer, $deadline, $post_id='')
    {
        $current_timestamp = time(); //current timestamp
        $deadline_timestamp = strtotime($deadline); //Unix timestamp for a deadline
        $user_post = '<section class="basic">'. "\n"
                    .'<div class="grey_bcgr">'. "\n". "\t"
                    .'<i class="fa fa-user-md" style="color: rgb(1, 181, 154);"></i><h4 style="color: rgb(1, 181, 154);">'. $username. '</h4>'. "\n"
                    .'<p class="timestamp">'. $timestamp. '</p>'. "\n"
                    .'</div>'. "\n";
       
        if($picture!= NULL )
        {
            $user_post .= '<img src="'. IMG_DISPLAY_PATH. $picture. '"/ class="img" alt="image">';
        }
        $user_post .= '<h3>'. $title. '</h3>'
                    .'<p class="post">'. nl2br($post). '</p>'. "\n"
                    .'<h3>ANSWER</h3>';
        
        if($current_timestamp >= $deadline_timestamp)
        {
            $user_post .= '<p class="post">'. nl2br($answer). '</p>'. "\n";
        }else{
            $user_post .='<p class="post">is coming on '. $deadline .'</p>'. "\n";
        }
        
        $user_post .='<div class="grey_bcgr">'. "\n". "\t"
                    .'<ul>'. "\n". "\t"
                    .'<li><a href="leavecomment.php?id='. $post_id.'">Leave a comment</a></li>'. "\n"
                    .'<li><a href="leavecomment.php?id='. $post_id. '">'.  $comment_num. ' comments</a></li>'. "\n"
                    .'</ul>'. "\n"
                    .'</div>'. "\n"
                    .'</section>';
        return $user_post;
    }

/** commentRecieved - writes comments
  * @username_com_id, str, username id (need it to change username color if it's an admin)
  * @$username_com, str, username
  * @$timestamp_com, str, date_entered of the comment
  * @post_com, str, comment itself
*/
    function commentRecieved($username_com_id, $username_com, $timestamp_com, $post_com)
    {
        $comment = '<section class="basic">'. "\n". "\t"
                    .'<div class="grey_bcgr">'. "\n". "\t";
        if($username_com_id==1)
        {
            $comment .= '<i class="fa fa-user-md" style="color: rgb(1, 181, 154);"></i><h4 style="color: rgb(1, 181, 154);">'. $username_com.'</h4>'. "\n";
        }else{
            $comment .= '<i class="fa fa-user"></i><h4>'. $username_com.'</h4>'. "\n";
        }
        $comment .= '<p class="timestamp">'. $timestamp_com. '</p>'. "\n"
                    .'</div>'. "\n"
                    .'<p class="post">'. nl2br($post_com). '</p>'. "\n"
                    .'</section>';
        return $comment;
    }

/** openForm - writes HTML form opening
  * @id, str, id of a form
*/
    function openForm($id = '')
    {
        if(isset($_GET['id']))
        {
            return "\n". "\t". '<form action="'. $_SERVER['PHP_SELF']. '?id='. $_GET['id']. '" method="post" id="'. $id. '">';
        }elseif(isset($_SERVER['QUERY_STRING']))
        {
            return "\n". "\t". '<form action="'. $_SERVER['PHP_SELF']. '?'. $_SERVER['QUERY_STRING']. '" method="post" id="'. $id. '">';
        }else{
            return "\n". "\t". '<form action="'. $_SERVER['PHP_SELF']. '" method="post" id="'. $id. '">';
        }
        
    }

/** openFormImg - writes HTML form opening for form, that uploads files
  * @id, str, id of a form
*/    
    function openFormImg($id = '')
    {
      return "\n". "\t". '<form action="'. $_SERVER['PHP_SELF'].'" enctype="multipart/form-data" method="post" id="'. $id. '">';
    }
    

/** makeLabel - makes a label inside <div>
  * @name, str, the name of field
  * @label, str, label inself
  * @divID, str, ex.<div id=$divID><label>
  * @nameID, str, ex. <label id=$$nameID>
*/
    function makeLabel($name, $label, $divID, $nameID)
    {
        return "\n". "\t". '<div id="'. $divID. '">'. "\n". "\t". "\t"
                .'<label for="' .$name. '" id="'. $nameID. '"><h4>'. $label. '</h4></label>'. "\n";
    }
 
	function makeLabelNaked($name, $label)
    {
        return "\n". "\t"
                .'<label for="' .$name. '" id="'. $name. '"><h4>'. $label. '</h4></label>'. "\n";
    }

/** makeMessage - writes a validation_msg
  * @name, str, the name of field
  * @additional_class, str, additional class for styling
*/
    function makeMessage($name, $additional_class)
    {
        return "\t". "\t". '<p class="validation_msg'. $additional_class. '">'. $this->formArray[$name]['msg']. '</p>'. "\n". "\t". '</div>';
    }
  
    
/** makeInput - creates <input> tag
  * @name, str, name of input field
  * @type, str, type of input field
*/  
    function makeInput($name,  $type)
    {
        return "\t". "\t". '<input type="'. $type. '" name="'. $name.'"  id="'. $name. '" required value="'. $this->aFiltered[$name]. '">'. "\n";
    }

    
/** makeInputRow - creates input with label
  * @name, str, name of input field
  * @label, str, label name
  * @type, str, type of input field
  * @divID, str, ex.<div id=$divID><label>
  * @nameID, str, ex. <label id=$$nameID>
*/
    function makeInputRow($name, $label, $type, $divID = '', $nameID = '')
    {
        $a = ''
            .$this->makeLabel($name, $label, $divID, $nameID)
            .$this->makeInput($name,  $type);
        return $a. "\t". '</div>';
    }

/** makeInputRowVal - creates input with label and validation_msg
  * @name, str, name of input field
  * @label, str, label name
  * @type, str, type of input field
  * @divID, str, ex.<div id=$divID><label>
  * @nameID, str, ex. <label id=$$nameID>
  * @additional_class, str, optional additional class for validation_msg
*/
    function makeInputRowVal($name, $label, $type, $divID = '', $nameID = '', $additional_class='')
    {
        $a = ''
            . $this->makeLabel($name, $label, $divID, $nameID)
            . $this->makeInput($name, $type)
            . $this->makeMessage($name, $additional_class);
        return $a;
    }
    
/** makeRadio - preparation for making the whole radio input field without validation_msg after each option
  * @name, str, the name of radio input
  * @value, str, value for radio option
  * @option, str, the option to choose for user (if differs from value for for convenience of putting it to DB)
*/
    function makeRadio($name, $value, $option)
    {   
        $a = "\n". "\t". '<div class="row">'
        	. "\n". "\t". "\t". '<input type="radio" name="'. $name. '" required value="'. $value. '" id="'. $value. '"';
        $a .= ($value == $this->aFiltered[$name])?' checked="true" ':''; // check, if $value is the one that exists in input value (to make this field sticky)
        if($option=='')
        {
        	$a .= '>'. "\n". "\t".'<h4>'. $value. '</h4>';
        }else{
        	$a .= '>'. "\n". "\t". '<h4>'. $option. '</h4>';
        }
        
        return $a. '</div>';
    }

/** makeRadioVal - preparation for making the whole radio input field with validation_msg after each option
  * @name, str, the name of radio input
  * @value, str, value for radio option
  * @option, str, the option to choose for user (if differs from value for for convenience of putting it to DB)
*/    
    function makeRadioVal($name, $value, $option='')
    {   
            $a .= "\n". "\t". '<div class="row">'. "\n". "\t". "\t". '<input type="radio" name="'. $name. '" required value="'. $value. '" id="'. $value. '"';
            $a .= ($value == $this->aFiltered[$name])?' checked="true" ':''; // check, if $value is the one that exists in input value (to make this field sticky)
            if($option!='')
            {
                $a .= '> <h4>'. $option. '</h4>'. "\n". "\t". "\t". '<p class="validation_msg_grey"></p>'. "\n". "\t". '</div>'. "\n";
            }else{
                $a .= '> <h4>'. $value. '</h4>'. "\n". "\t". "\t". '<p class="validation_msg_grey"></p>'. "\n". "\t". '</div>'. "\n";
            }
        return $a;
    }

/** makeRadioRow - makes a whole textarea with label with one validation_msg after the whole input options
  * @name, str, the name of radio input
  * @label, str, label for radio input
  * @value, str, value for radio option
  * @divID, str, ex.<div id=$divID><label>
  * @nameID, str, ex. <label id=$$nameID>
  * @option, str, the option to choose for user (if differs from value for for convenience of putting it to DB)
  * @additional_class, str, optional additional class for validation_msg (for styling)
*/
    function makeRadioRow($name, $label, $divID = '', $nameID = '', $value='',  $option='', $additional_class='')
    {
        $a .= $this->makeLabel($name, $label, $divID, $nameID)
            . $this->makeRadio($name, $value, $option)
            . $this->makeMessage($name, $additional_class);
        return $a;
    }
    
/** makeRadioRowVal - makes a whole textarea with label with validation_msg after each input row, without label for each input option
  * @name, str, the name of radio input
  * @label, str, label for radio input
  * @value, str, value for radio option
  * @divID, str, ex.<div id=$divID><label>
  * @nameID, str, ex. <label id=$$nameID>
  * @option, str, the option to choose for user (if differs from value for for convenience of putting it to DB)
*/    
     function makeRadioRowVal($name, $label, $value, $divID = '', $nameID = '', $option='')
    {
        $a .= $this->makeLabel($name, $label, $divID, $nameID)
            . $this->makeRadioVal($name, $value, $option);
        return $a;
    }


/** makeTextareaRowVal - preparation for making the whole of textarea
  * @name, str, the name of textarea
  * @placeholder, str, placeholder for textarea
*/ 
 function makeTextarea($name, $placeholder)
 {
    $a = "\n". "\t". "\t". '<textarea placeholder="'. $placeholder. '" name="'. $name. '" id="'. $name. '" required value="';
    if(isset($this->aFiltered[$name]))
    {
        $a .= '">'. $this->aFiltered[$name]. '</textarea>'. "\n";
        return $a;
    }else{
        $a .= ''. '"></textarea>'. "\n";
        return $a;
    }
 }
 
/** makeTextareaRowVal - makes a whole textarea with label and validation_msg
  * @name, str, the name of textarea
  * @label, str, label for textarea
  * @placeholder, str, placeholder for textarea
  * @divID, str, ex.<div id=$divID><textarea>
  * @nameID, str, ex. <textarea id=$$nameID>
  * @additional_class, str, optional additional class for validation_msg
*/
 function makeTextareaRowVal($name, $label, $placeholder='', $divID='', $nameID='', $additional_class='')
    {
        $a = ''
            . $this->makeLabel($name, $label, $divID, $nameID)
            . $this->makeTextarea($name,  $placeholder)
            . $this->makeMessage($name, $additional_class);
        return $a;
    }


/** submitButton - writes HTML submit_btn
  * @id, str, id of submit_btn
  * @value, str, value of submit_btn
*/
    function submitButton($id, $value)
    {
        return "\n". "\t". '<input type="submit" name="'. $id. '" id="'. $id. '" value="'. $value. '">';
    }        

/** closeForm - writes HTML form closing
*/     
    function closeForm()
    {
        return "\n". '</form>';
    }

    
/** makeFooter - writes HTML footer
  * @script1, str, script addres that set on config file
  * @script2, str, script addres that set on config file
  * @script3, str, script addres that set on config file
  * @script4, str, script addres that set on config file
*/
    function makeFooter($script1, $script2, $script3 = '', $script4= '')
    {
        $a= "\n".
                '<!--******************************************************************************************************************-->'. "\n".
                        '<!-- FOOTER -->'. "\n".
                '<!--******************************************************************************************************************-->'. "\n".
                '<footer>';
        $a .= $this-> drawRibbon();
                    
    
        $a .= '<div>
                        <a href="http://www.olgasmirnova.co.nz/">&copy2014 by OS</a>
                    </div>
                </footer>
            
            </div> <!-- #container ends -->'. "\n".
            $script1. "\n".
            $script2. "\n".
            $script3. "\n".
            $script4. "\n".
        '</body>
    </html>';
    
    	return $a;
    }


} // end of class
//EOF html.class.php