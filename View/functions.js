function showLoginForm() {
    document.getElementById("formSignUp").style.display="none";
    document.getElementById("formLogin").style.display="block";
    document.getElementById("welcomeParagraph").style.display="none";

}
function showSignUpForm() {
    document.getElementById("formSignUp").style.display="block";
    document.getElementById("formLogin").style.display="none";
    document.getElementById("welcomeParagraph").style.display="none";
}

var selectedSeats =[];


function sendSignUpForm(){
    let user_name = document.getElementById("signUpUserNameID").value;
    let user_password = document.getElementById("signUpPasswordID").value;
    if(validateEmail(user_name)){
        $.ajax({
            url: "../Controller/controllerHandler.php",

            type: "POST", //send it through post method
            data: {signUpRequest: 'yes', userName: user_name, password: user_password},
            dataType:"text",
            success: function (response) {
                loggedinResponse(response);
            },
            error: function (xhr) {
                document.write("Error while signup");
            }
        });
    }
    else {
        window.alert("Enter a valid Email");
    }
}

function sendLoginForm() {
    let user_name = document.getElementById("loginUserNameID").value;
    let user_password = document.getElementById("loginPasswordID").value;

    if(validateEmail(user_name)){
        $.ajax({
            url: "../Controller/controllerHandler.php",
            type: "POST", //send it through post method
            data: {loginRequest: 'yes', userName: user_name, password: user_password},
            dataType:"text",
            success: function (response) { //logged in correctly
                if(response.toString()=== user_name.toString()){
                    loggedinResponse(response);
                }
                else { //failed to log in
                    loginFailedResponse();
                }
            },
            error: function (xhr) {
                document.write("Error while login");
            }
        });
    }
    else {
        window.alert("Enter a valid Email");
    }
}

function sendLogoutRequest() {
    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {logoOutRequest: 'yes'},
        dataType:"text",
        success: function (response) {
            console.log(response);
            if(response.toString() === 'Done'){
                logOutSuccessesResponse();
            }
        },
        error: function (xhr) {

            //Do Something to handle error
        }
    });
}


function selectSeat(seat) {
    let seatID = seat.id;
    let regexStr = seatID.match(/[a-z]+|[^a-z]+/gi);
    let seatRow = Number(regexStr[0]);
    let seatColumn = regexStr[1];
    let indexOfElementToRemove;
    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {checkSeatState: 'yes', row: seatRow, column: seatColumn},
        dataType:'text',
        success: function (response) {
            if(response.toString() === 'timeout'){
                logOutSuccessesResponse();
            }
            else {
                if (response.toString() === 'free') {
                    selectedSeats.push(seatID);
                    console.log(selectedSeats);
                    reserveSeatRequest(seat);

                } else if (response.toString() === 'selected') {//selected but not by me
                    selectedSeats.push(seatID);
                    reserveSeatRequest(seat);

                } else if (response.toString() === 'already_selected'){//selected by me that's mean that I want to unselect the seat because I pressed twice
                    seat.checked=false;
                    cancelSeatReservation(seat);
                    indexOfElementToRemove = selectedSeats.indexOf(seatID);
                    if (indexOfElementToRemove > -1) {
                        selectedSeats.splice(indexOfElementToRemove, 1);
                    }
                } else if (response.toString() === 'purchased') {
                    seat.disabled = true;
                }
            }

        },
        error: function (xhr) {
            //Do Something to handle error
        }
    });
}
function cancelSeatReservation(seat) {
    let seatID = seat.id;
    let regexStr = seatID.match(/[a-z]+|[^a-z]+/gi);
    let seatRow = Number(regexStr[0]);
    let seatColumn = regexStr[1];

    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {cancelSeatReservation: 'yes',row:seatRow,column:seatColumn},
        dataType:"text",
        success: function (response) {

        },
        error: function (xhr) {

            //Do Something to handle error
        }
    });
}
function reserveSeatRequest(seat) {
    let seatID = seat.id;
    let regexStr = seatID.match(/[a-z]+|[^a-z]+/gi);
    let seatRow = Number(regexStr[0]);
    let seatColumn = regexStr[1];

    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {reserveSeatRequest: 'yes',row:seatRow,column:seatColumn},
        dataType:"text",
        success: function (response) {

        },
        error: function (xhr) {

            //Do Something to handle error
        }
    });

}
function buySeat() {
    for(var i=0;i<selectedSeats.length;i++){
        let regexStr = selectedSeats[i].match(/[a-z]+|[^a-z]+/gi);
        let seatRow = Number(regexStr[0]);
        let seatColumn = regexStr[1];
        $.ajax({
            url: "../Controller/controllerHandler.php",
            type: "POST", //send it through post method
            data: {purchaseSeatRequest: 'yes',row:seatRow,column:seatColumn},
            dataType:"text",
            success: function (response) {
                document.getElementById("welcomeParagraph").innerHTML= response;
                document.getElementById("welcomeParagraph").style.display = "block";
            },
            error: function (xhr) {

                //Do Something to handle error
            }
        });
    }
}


function initSeat(){
    let numberOfColumns = 6;
    let numberOfRows = 10;
    let orderedList = document.getElementById("cabin");

    for(let i=0;i<numberOfRows;i++){
        let string = "row row--".concat((Number(i)+Number(1)).toString());
        let rowItemList = document.createElement("li");
        rowItemList.setAttribute("class",string);
        orderedList.appendChild(rowItemList);
        let innerOrderedList = document.createElement("ol");
        innerOrderedList.setAttribute("class","seats");
        innerOrderedList.setAttribute("type","A");
        rowItemList.appendChild(innerOrderedList);

        for (let j=0;j<numberOfColumns;j++){
            let innerItemList = document.createElement("li");
            innerItemList.setAttribute("class","seat");
            innerOrderedList.appendChild(innerItemList);
            let inputElement = document.createElement("input");
            inputElement.setAttribute("type","checkbox");
            let currentChar = String.fromCharCode("A".charCodeAt(0) + Number(j));
            let currentId = ((Number(i)+Number(1))+currentChar).toString();
            inputElement.setAttribute("id",currentId);
            inputElement.setAttribute("onclick","selectSeat(this)");
            innerItemList.appendChild(inputElement);
            let labelElement = document.createElement("label");
            labelElement.setAttribute("for",currentId);
            let textElement = document.createTextNode(currentId.toString());
            labelElement.appendChild(textElement);
            innerItemList.appendChild(labelElement);
        }
    }

}


function validateEmail(email) {
    let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
function logOutSuccessesResponse() {
    document.getElementById("formSignUp").style.display="none";
    document.getElementById("formLogin").style.display="none";
    document.getElementById("signUpA").style.display="block";
    document.getElementById("loginA").style.display="block";
    document.getElementById("logoutID").style.display = "none";
    document.getElementById("buyID").style.display="none";
    document.getElementById("updateID").style.display="none";
    document.getElementById("welcomeParagraph").innerHTML = "You are logged out !";
    document.getElementById("welcomeParagraph").style.display = "block";
}

function loggedinResponse(response){
    document.getElementById("formSignUp").style.display="none";
    document.getElementById("formLogin").style.display="none";
    document.getElementById("signUpA").style.display="none";
    document.getElementById("loginA").style.display="none";
    document.getElementById("logoutID").style.display = "block";
    document.getElementById("buyID").style.display="block";
    document.getElementById("updateID").style.display="block";
    document.getElementById("welcomeParagraph").innerHTML="Welcome "+response+" to our Airline company";
    document.getElementById("welcomeParagraph").style.display = "block";
}
function loginFailedResponse(){
    document.getElementById("formSignUp").style.display="none";
    document.getElementById("formLogin").style.display="none";
    document.getElementById("signUpA").style.display="block";
    document.getElementById("loginA").style.display="block";
    document.getElementById("logoutID").style.display = "none";
    document.getElementById("buyID").style.display="none";
    document.getElementById("updateID").style.display="none";
    document.getElementById("welcomeParagraph").innerHTML = "Wrong username or password please try again. ";
    document.getElementById("welcomeParagraph").style.display = "block";
}