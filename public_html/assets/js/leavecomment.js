//#########################################################################################
        //SMOOTH SCROLLING
//#########################################################################################
document.querySelector('.scrollToTop').style.display = 'none';

window.onscroll=function()
{	
	if(window.pageYOffset >= 400) //if user scrolled the page down to 400px, show "Back to top"_btn, otherwise - hide it
	{
		document.querySelector('.scrollToTop').style.display = 'block';
	}else{
		document.querySelector('.scrollToTop').style.display = 'none';
	}
}

//https://github.com/cferdinandi/smooth-scroll#how-to-contribute - here you may find compiled and production-ready code.
window.onload=function()
{
    smoothScroll.init({
        speed: 500, // How fast to complete the scroll in milliseconds
        easing: 'easeInOutQuint', // Easing pattern to use
    });
};


//#########################################################################################
        //FORMS VALIDATION
//#########################################################################################
// Fields to be validated
var comment = ['#comment'];


// Validation methods
validation(comment, 'isEmpty', 'checkLength_2000', 'checkTextarea');



//#########################################################################################
        //CHARACTERS COUNTER FOR TEXTAREA
//#########################################################################################
var maxLength = 2000;
var area = document.querySelector('#comment');

area.addEventListener('keyup', function()
{
	countChars(maxLength, area);
})
