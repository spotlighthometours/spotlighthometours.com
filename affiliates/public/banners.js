      class="gsa" \
            name="gsa" \
            id="gsa" \
            type="checkbox" \
            checked="checked" \
            value="1" ><\/input>'); 
            
    // Leave the "Search this help system only" checkbox unckecked
    }else{    
        document.write('<div id="searchscope"> \
            <input onchange="setAHSearchPref();" \
            class="gsa" \
            name="gsa" \
            id="gsa" \
            type="checkbox" \
            value="1" ><\/input>'); 
    }
    document.write('<span class="gsalabel">' + terms_AHV_SEARCH_CONSTRAINT + '<\/span><\/div>'); 
}
document.write('<input class="searchinput" \
    name="q" \
    id="q" \
    type="text" \
    maxlength="256" \
    value="' + terms_AHV_SEARCH_BUTTON + '" \
    onclick="clearSearch()"><\/input><input \
    type="button" \
    name="searchbutton" \
    class="searchbutton" \
    onclick="submit()" />'); 
 
 
/* 
 * Start Functions 
 */
function clearSearch(){
    if (document.search.q.value == terms_AHV_SEARCH_BUTTON){document.search.q.value = ""}; 
}

// set search preferences
function setAHSearchPref(){
    if (document.search.gsa.checked == 1){
        setAHSearchCookie( dirname );
    }else{
        setAHSearchCookie( "community" );
    }
}

// Set search preferences cookie
function setAHSearchCookie( p ){
    // set cookie ah_searchpref with a value of the document path
    var expire=new 