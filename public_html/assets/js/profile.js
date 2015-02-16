//#########################################################################################
        //SMOOTH SCROLLING
//#########################################################################################
document.querySelector('.scrollToTop').style.display = 'none';

window.onscroll=function()
{	
	if(window.pageYOffset >= 400) //if window was scrolles down to >=400px, show Back-to-top_btn
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
        //TAGS FOR USER'S OPTIONS
//#########################################################################################
// source of code: 'JavaScript for Web Designers' with Joe Chellman
// link: http://www.lynda.com/JavaScript-tutorials/JavaScript-Web-Designers/144203-2.html

document.addEventListener('DOMContentLoaded', function(){
	document.getElementById('tab-group').className = 'ready';
	
	var headerClass = 'tab-header',
		contentClass = 'tab-content';
	
	document.getElementById('tab-group').addEventListener('click', function(event) {
		
		var myHeader = event.target;
		
		if (myHeader.className !== headerClass) return;
		
		var myID = myHeader.id, // e.g. tab-header-1
			contentID = myID.replace('header', 'content'); // result: tab-content-1
		
		deactivateAllTabs();
		
		myHeader.className = headerClass + ' active';
		document.getElementById(contentID).className = contentClass + ' active';
	});
	
	function deactivateAllTabs()
	{
		var tabHeaders = document.getElementsByClassName(headerClass),
			tabContents = document.getElementsByClassName(contentClass);
		
		for (var i = 0; i < tabHeaders.length; i++)
		{
			tabHeaders[i].className = headerClass;
			tabContents[i].className = contentClass;
		}
	}
});


//#########################################################################################
        //FORMS VALIDATION
//#########################################################################################
// Fields to be validated
var title = ['#mypost_title', '#img_title'];
var post = ['#mypost', '#capture'];
var radio = document.forms["kill_me"].elements["suicide"];

// Validation methods
validation(title, 'isEmpty', 'checkLength_60', 'checkTextarea');
validation(post, 'isEmpty', 'checkLength_2000', 'checkTextarea');
validationRadio(radio);


//#########################################################################################
        //CHARACTERS COUNTER FOR TEXTAREA
//#########################################################################################
var maxLength = 2000;
var area1 = document.querySelector('#mypost');
var area2 = document.querySelector('#capture');


area1.addEventListener('keyup', function()
{
	countChars(maxLength, area1);
})

area2.addEventListener('keyup', function()
{
	countChars(maxLength, area2);
})
