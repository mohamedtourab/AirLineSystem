function showLoginForm() {
    document.getElementById("formSignUp").style.display="none";
    document.getElementById("formLogin").style.display="block";
}
function showSignUpForm() {
    document.getElementById("formSignUp").style.display="block";
    document.getElementById("formLogin").style.display="none";
}

function sendSignUpForm(){
    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {
            signUpRequest: 'yes',
            userName: document.getElementById("signUpUserNameID").value,
            password: document.getElementById("signUpPasswordID").value
        },
        success: function (response) {
            //
        },
        error: function (xhr) {
            //Do Something to handle error
        }
    });


}
function sendLoginForm() {
    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {
            loginRequest: 'yes',
            userName: document.getElementById("loginUserNameID").value,
            password: document.getElementById("loginPasswordID").value
        },
        success: function (response) {

        },
        error: function (xhr) {
            //Do Something to handle error
        }
    });
}


function initSeat(){
    var numberOfColumns = 6;
    var numberOfRows = 10;
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
            inputElement.setAttribute("onclick","selectSeat(this)");
            innerItemList.appendChild(inputElement);
            var labelElement = document.createElement("label");
            labelElement.setAttribute("for",currentId);
            var textElement = document.createTextNode(currentId.toString());
            labelElement.appendChild(textElement);
            innerItemList.appendChild(labelElement);
        }
    }

}
var selectedSeats;
function selectSeat(seat) {
    var seatID = seat.id;
    var seatRow = Number(seatID.charAt(0));
    var seatColumn = seatID.charAt(1);
    //TODO Create ajaxRequest here;
    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {
            checkSeatState: 'yes',
            row: seatRow,
            column: seatColumn
        },
        success: function (response) {
            if (response.toString() === 'free') {
                selectedSeats.push(seat);
            } else if (response === 'selected') {

            } else if (response.toString() === 'purchased') {
                seat.disabled = true;
            }
        },
        error: function (xhr) {
            //Do Something to handle error
        }
    });


}