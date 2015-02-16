//#########################################################################################
        //FORMS VALIDATION
//#########################################################################################
// Fields to be validated
var username = ['#username'];
var password = ['#password'];

// Validation methods
validation(username, 'isEmpty', 'checkUsername', 'checkLength_16');
validation(password, 'isEmpty', 'checkUsername', 'checkLength_18');