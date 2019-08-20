//return the user to the home page if the previous page
//was the review poster
function backToPrev() {
    var referrer = document.referrer;
    var pageregex = /post.php/;
	//compare the document file name with the post.php regular expression
	//if referrers are disabled, assume the user was on the poster
    if (pageregex.test(referrer) || referrer == null) { 
        //return to home
		//./ specifies a location in the current directory
        window.location.assign("./index.php");

    } else {
        //go back a page
        history.back();
    }
}