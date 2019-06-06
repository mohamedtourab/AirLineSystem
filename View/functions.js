function showLoginForm() {
    document.getElementById("formSignUp").style.display="none";
    document.getElementById("formLogin").style.display="block";
}
function showSignUpForm() {
    document.getElementById("formSignUp").style.display="block";
    document.getElementById("formLogin").style.display="none";
}

function initSeat(){
    var numberOfColumns = 6;
    var numberOfRows = 14;
    var orderedList = document.getElementById("cabin");

    for(var i=0;i<numberOfRows;i++){
        var string = "row row--".concat((Number(i)+Number(1)).toString());
        var rowItemList = document.createElement("li");
        rowItemList.setAttribute("class",string);
        orderedList.appendChild(rowItemList);
        var innerOrderedList = document.createElement("ol");
        innerOrderedList.setAttribute("class","seats");
        innerOrderedList.setAttribute("type","A");
        rowItemList.appendChild(innerOrderedList);

        for (var j=0;j<numberOfColumns;j++){
            var innerItemList = document.createElement("li");
            innerItemList.setAttribute("class","seat");
            innerOrderedList.appendChild(innerItemList);
            var inputElement = document.createElement("input");
            inputElement.setAttribute("type","checkbox");
            var currentChar = String.fromCharCode("A".charCodeAt(0) + Number(j));
            var currentId = ((Number(i)+Number(1))+currentChar).toString();
            inputElement.setAttribute("id",currentId);
            innerItemList.appendChild(inputElement);
            var labelElement = document.createElement("label");
            labelElement.setAttribute("for",currentId);
            var textElement = document.createTextNode(currentId.toString());
            labelElement.appendChild(textElement);
            innerItemList.appendChild(labelElement);
        }
    }

}

var req;

function ajaxRequest() {
    try { // Non IE Browser?
        var request = new XMLHttpRequest()
    } catch(e1){ // No
        try { // IE 6+?
            request = new ActiveXObject("Msxml2.XMLHTTP")
        } catch(e2){ // No
            try { // IE 5?
                request = new ActiveXObject("Microsoft.XMLHTTP")
            } catch(e3){ // No AJAX Support
                request = false
            }
        }
    }
    return request
}

// Handler definition
function f(){
    if (req.readyState==4 &&
        (req.status== 0 || req.status==200)) {
        document.getElementById("tochange").
            innerHTML=req.responseText;
    };
}

function startAjax() {
    req = ajaxRequest();
    req.onreadystatechange = f;
    req.open("GET","ajax.txt", true);
    req.send();
}