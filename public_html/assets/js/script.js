//#########################################################################################
        //FORMS VALIDATION
//#########################################################################################

var isEmpty_msg = "*field can't be empty";

var checkLength_16_msg = "*3-16 characters required"; // for creating username on sign-in procedure
var checkLength_18_msg = "*8-18 characters required"; // for creating password on sign-in procedure
var checkLength_60_msg = "*60 characters at most"; // for creating titles
var checkLength_100_msg = "*100 characters at most"; // for statements
var checkLength_2000_msg = "*2000 characters at most"; // for posts, comments

var checkUsername_msg = "*A-z/0-9/hyphen/underscore"; // check valid username, password
var checkMail_msg = "*please, enter a valid email address"; // check valid email address

var checkTextarea_msg = "*A-z/0-9/,.!?()+=-_ only";

var radioY_msg = "*don't worry, the best people usualy are"; // for radio_btns, option 'yey'
var radioN_msg = "*don't worry, soon you'll be"; // for radio_btns, option 'nay'
var oops_msg = "*Stop! You, probably, chose delete option by mistake" // for last reason of deleting an account


// functions, that turns on/off validation *************************************************************************************
// 1) check if user did fill the field
	function isEmpty(t)
	{    
	     var str = t.value;
	     return str.length>0;
	}

// 2) check the lengh of username
	function checkLength_16(t)
	{
		var str_size = t.value.length;
		return (str_size<=16 && str_size>=3);
	}

// 3) check the lengh of password
	function checkLength_18(t)
	{
		var str_size = t.value.length;
		return (str_size<=18 && str_size>=8);
	}

// 4) check valid username
	function checkUsername(t)
	{
		var regex_username = /^([a-zA-Z0-9-_])+$/;
		return (regex_username.test(t.value));
	}

// 5) check valid email
	function checkMail(t)
	{
	    var regex_mail = /^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/;
	    return (regex_mail.test(t.value));
	}

// 6) check the lengh of title
	function checkLength_60(t)
	{
		var str_size = t.value.length;
		return (str_size>=1 && str_size<=60);
	}

// 7) check the lengh of new user statement
	function checkLength_100(t)
	{
		var str_size = t.value.length;
		return (str_size>=1 && str_size<=100);
	}

// 8) check the lengh of textareas
	function checkLength_2000(t)
	{
		var str_size = t.value.length;
		return (str_size>=1 && str_size<=2000);
	}

// 9) check valid textarea
	function checkTextarea(t)
	{
		var regex_text = /^[a-zA-Z0-9?$@#()'!,+\-=_:.&€£*%\s]+$/;
		return regex_text.test(t.value);
	}

/* countChars
 * @max, number, max length of textarae
 * @area, object, textarea
 * counts how many charactars remain, when user types
*/
	function countChars(max, area)
	{	
		if(area.value.length != 0)
		{
			console.log( area.value );
			if(area.value.length < max)
			{
				console.log( area.nextElementSibling );
				area.nextElementSibling.innerHTML = (max - area.value.length) + " characters remain";
			}
		}
	}

/* checkedRadio
 * this function is not universal
 * @t, object, input field of checked radio_btn (ex. <input type="radio" name="answer" required="" value="yey">)
 * @val, string, the value of checked radio_btn (ex. yey)
 * writes validation_msg according to @t and @val
*/
	function checkedRadio(t, val)
	{
		if(val=='yey')
		{
			var checkedRadio_msg = radioY_msg;
			t.nextElementSibling.nextElementSibling.innerHTML = checkedRadio_msg;
			window['nay'].nextElementSibling.nextElementSibling.innerHTML = '';
		}else if(val=='nay')
		{
			var checkedRadio_msg = radioN_msg;
			t.nextElementSibling.nextElementSibling.innerHTML = checkedRadio_msg;
			window['yey'].nextElementSibling.nextElementSibling.innerHTML = '';
		}else if(val==4)
		{
			var checkedRadio_msg = oops_msg;
			document.querySelector('.top_validation').innerHTML = checkedRadio_msg;
		}else if(val==1 || val==2 || val==3)
		{
			document.querySelector('.top_validation').innerHTML = '';
		}
	}

/* success - when nput field passed validation procedure
 * returns grey-color style to validation_msg, makes validation_msg empty
*/
function success(curr_obj)
{
	curr_obj.className = curr_obj.className.replace('fail', '');
    if(curr_obj.nextElementSibling != null)
	{	
		curr_obj.nextElementSibling.innerHTML = '';
		curr_obj.nextElementSibling.className = curr_obj.nextElementSibling.className + ' validation_msg_grey';
	}else{
		document.querySelector('.top_validation').innerHTML = '';
	}
}

/* fail - when nput field failed validation procedure
 * makes validation_msg red-color, writes validation_msg
*/
function fail(curr_obj, msg)
{
    curr_obj.className = 'fail';
    if(curr_obj.nextElementSibling != null)
	{	
		curr_obj.nextElementSibling.innerHTML = msg;
		curr_obj.nextElementSibling.className = curr_obj.nextElementSibling.className.replace('validation_msg_grey', '');
			
	}else{
		document.querySelector('.top_validation').innerHTML = msg;
	}   		
}

/* validation - runs particular list of validation functions for particular array of fields (that need to be checked in the similar way)
 * makes validation_msg red-color, writes validation_msg
*/
function validation(aName, func, func2, func3)
{	
	for(var n=0; n<aName.length; n++)
	{
		document.querySelector(aName[n]).addEventListener('blur', function()
		{
			if(window[func](this))
			{	
				if(window[func2](this))
				{	
					if(window[func3](this))
					{	
						success(this);
					}else{
						fail(this, window[func3 + '_msg']);
					}
				}else{
					fail(this, window[func2 + '_msg']);
				}
			}else{
				fail(this, window[func + '_msg']);
			}
		});
	}

}

/* validationRadio - validation for radio input field
*/
function validationRadio (radioName)
{	
	for(var i = 0, max = radioName.length; i < max; i++)
	{
    	radioName[i].onclick = function()
    	{
        	checkedRadio(this, this.value);
    	}
    }
}




