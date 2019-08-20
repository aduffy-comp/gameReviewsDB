//runs each time a letter is inputted into the text box
var gamelist = new Bloodhound ({
    //what are these for?
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        //run the PHP script to search the datatbase
        url: "gameajax.php?key=%QUERY",
        wildcard: "%QUERY"
    }
});

//attributes list for search box?
$('.typeahead').typeahead(null, {
    name: 'name',
    source: gamelist //data source to associate with the text box
});