//runs each time a letter is inputted into the text box
var modlist = new Bloodhound ({
    //what are these for?
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        //run the PHP script to search the datatbase
        url: "modajax.php?key=%QUERY",
        wildcard: "%QUERY"
    }
});

//attributes list for search box?
$('.typeahead').typeahead(null, {
    name: 'name',
    source: modlist //data source to associate with the text box
});