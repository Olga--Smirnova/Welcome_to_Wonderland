//#########################################################################################
        //FORMS VALIDATION
//#########################################################################################
// Fields to be validated
var username = ['#new_username'];
var password = ['#new_password'];
var email = ['#mail'];
var statement = ['#statement'];
var radio = document.forms["signin_proc"].elements["answer"];

// Validation methods
validation(username, 'isEmpty', 'checkUsername', 'checkLength_16');
validation(password, 'isEmpty', 'checkUsername', 'checkLength_18');
validation(email, 'isEmpty', 'checkMail', 'isEmpty');
validation(statement, 'isEmpty', 'checkLength_100', 'checkTextarea');
validationRadio(radio);



//#########################################################################################
        //CHARACTERS COUNTER FOR TEXTAREA
//#########################################################################################
var maxLength = 100;
var area = document.querySelector('#statement');

area.addEventListener('keyup', function()
{
	countChars(maxLength, area);
})