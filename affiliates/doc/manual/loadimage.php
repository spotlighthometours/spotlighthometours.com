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
    if (document.search.q.value == terms_AHV_SE