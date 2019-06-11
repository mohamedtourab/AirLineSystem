//TODO implement the statistics for the plane
var selectedSeats = [];


function showLoginForm() {
    document.getElementById("formSignUp").style.display = "none";
    document.getElementById("formLogin").style.display = "block";
    document.getElementById("welcomeParagraph").style.display = "none";

}

function showSignUpForm() {
    document.getElementById("formSignUp").style.display = "block";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("welcomeParagraph").style.display = "none";
}


function sendSignUpForm() {
    let user_name = document.getElementById("signUpUserNameID").value;
    let user_password = document.getElementById("signUpPasswordID").value;

    let passwordRegex = new RegExp("(?=.*[a-z])((?=.*\\d*[A-Z]+)|(?=.*[A-Z]*\\d+))[0-9a-zA-Z]{2,}");
    if (!passwordRegex.test(user_password)) {
        window.alert("Incorrect password please try again.\nPassword must contain at least:\n1-One lowercase letter.\n2- One uppercase letter or a number.");

    }
    if (validateEmail(user_name)) {

        $.ajax({
            url: "../Controller/controllerHandler.php",

            type: "POST", //send it through post method
            data: {signUpRequest: 'yes', userName: user_name, password: user_password},
            dataType: "text",
            success: function (response) {
                if (response == 'AlreadyTaken') {
                    duplicateEmail();
                } else {
                    loggedinResponse(response);
                    localStorage.setItem("email", response);
                    updateView();
                }
            },
            error: function (xhr) {
                document.write("Error while signup");
            }
        });
    } else {
        window.alert("Enter a valid Email");
    }
}


function sendLoginForm() {
    let user_name = document.getElementById("loginUserNameID").value;
    let user_password = document.getElementById("loginPasswordID").value;
    let passwordRegex = new RegExp("(?=.*[a-z])((?=.*\\d*[A-Z]+)|(?=.*[A-Z]*\\d+))[0-9a-zA-Z]{2,}");
    if (!passwordRegex.test(user_password)) {
        window.alert("Incorrect password please try again.\nPassword must contain at least:\n1-One lowercase letter.\n2- One uppercase letter or a number.");
    }
    if (validateEmail(user_name)) {
        $.ajax({
            url: "../Controller/controllerHandler.php",
            type: "POST", //send it through post method
            data: {loginRequest: 'yes', userName: user_name, password: user_password},
            dataType: "text",
            success: function (response) { //logged in correctly
                if (response.toString() === user_name.toString()) {
                    loggedinResponse(response);
                    localStorage.setItem("email", response);
                    updateView();
                } else { //failed to log in
                    loginFailedResponse();
                }
            },
            error: function (xhr) {
                document.write("Error while login");
            }
        });
    } else {
        window.alert("Enter a valid Email");
    }
}

function sendLogoutRequest() {

    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {logoOutRequest: 'yes'},
        dataType: "text",
        success: function (response) {
            console.log(response);
            if (response.toString() === 'Done') {
                logOutSuccessesResponse();
                updateView();
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
        dataType: 'text',
        success: function (response) {
            if (response.toString() === 'timeout') {
                timeOutRespone();
                selectedSeats = [];
                updateView();
            } else {
                if (response.toString() === 'free') {
                    document.getElementById((seatID + 'L')).style.backgroundColor = "yellow";
                    selectedSeats.push(seatID);
                    alert("You have selected a seat");
                    if (selectedSeats.length > 0) {
                        document.getElementById("buyID").style.display = "block";
                    } else {
                        document.getElementById("buyID").style.display = "none";
                    }
                } else if (response.toString() === 'selected') {//selected but not by me
                    document.getElementById((seatID + 'L')).style.backgroundColor = "yellow";
                    selectedSeats.push(seatID);
                    alert("You have selected a seat");
                    if (selectedSeats.length > 0) {
                        document.getElementById("buyID").style.display = "block";
                    } else {
                        document.getElementById("buyID").style.display = "none";
                    }
                } else if (response.toString() === 'already_selected') {//selected by me that's mean that I want to unselect the seat because I pressed twice
                    alert("You have deselected a seat");
                    seat.checked = false;
                    document.getElementById((seatID + 'L')).style.backgroundColor = "green";
                    let index = selectedSeats.indexOf(seatID);
                    if (index > -1) {
                        selectedSeats.splice(index, 1);
                    }
                    if (selectedSeats.length > 0) {
                        document.getElementById("buyID").style.display = "block";
                    } else {
                        document.getElementById("buyID").style.display = "none";
                    }

                } else if (response.toString() === 'purchased') {
                    seat.disabled = true;
                    document.getElementById((seatID + 'L')).style.backgroundColor = "red";
                    let index = selectedSeats.indexOf(seatID);
                    if (index > -1) {
                        selectedSeats.splice(index, 1);
                    }
                    if (selectedSeats.length > 0) {
                        document.getElementById("buyID").style.display = "block";
                    } else {
                        document.getElementById("buyID").style.display = "none";
                    }

                }
            }

        },
        error: function (xhr) {
            //Do Something to handle error
        }
    });
}

function buySeat() {
    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {purchaseSeatRequest: 'yes'},
        dataType: "text",
        success: function (response) {
            if (response.toString() === 'timeout') {
                timeOutRespone();
                selectedSeats = [];
            } else if (response == 'Purchased Successfully') {
                document.getElementById("welcomeParagraph").innerHTML = response + ".";
                document.getElementById("welcomeParagraph").style.display = "block";
                document.getElementById("buyID").style.display = "none";
                selectedSeats = [];
            } else if (response == 'Purchase Failed') {
                document.getElementById("welcomeParagraph").innerHTML = response + " some seats are not available anymore." + "<br>" + "Please Select another seats";
                document.getElementById("welcomeParagraph").style.display = "block";
                document.getElementById("buyID").style.display = "none";
                selectedSeats = [];
            }

            updateView();
        },
        error: function (xhr) {

            //Do Something to handle error
        }
    });
}

function updateView() {

    let timeOutR;
    let seatID;
    let color;
    let freeNumber = 0;
    let selectedNumber = 0;
    let purchasedNumber = 0;
    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {updateView: 'yes'},
        dataType: 'json',
        success: function (JSONObject) {

            for (let key in JSONObject) {
                if (JSONObject.hasOwnProperty(key)) {
                    timeOutR = JSONObject[key]['timeoutRespone'];
                    if (timeOutR == 'timeout') {
                        timeOutRespone();
                        selectedSeats = [];
                        break;
                    }
                    color = JSONObject[key]['color'];
                    seatID = JSONObject[key]['seatID'];
                    if (color == 'Green') {
                        document.getElementById(seatID).checked = false;
                        document.getElementById((seatID + 'L')).style.backgroundColor = "green";
                        freeNumber++;
                    } else if (color == 'Red') {
                        document.getElementById(seatID).disabled = true;
                        document.getElementById((seatID + 'L')).style.backgroundColor = "red";
                        purchasedNumber++;
                    } else if (color == 'Yellow') {
                        document.getElementById(seatID).checked = true;
                        document.getElementById((seatID + 'L')).style.backgroundColor = "yellow";
                        selectedNumber++;
                    } else if (color == 'Orange') {
                        document.getElementById(seatID).checked = true;
                        document.getElementById((seatID + 'L')).style.backgroundColor = "orange";
                        selectedNumber++;
                    }
                }
            }
            document.getElementById("totalSeats").innerHTML = "Total: " + (Number(freeNumber) + Number(purchasedNumber) + Number(selectedNumber));
            document.getElementById("freeSeats").innerHTML = "Free: " + freeNumber;
            document.getElementById("selectedSeats").innerHTML = "Selected: " + selectedNumber;
            document.getElementById("purchasedSeats").innerHTML = "Purchased: " + purchasedNumber;
        },
        error: function (xhr) {

            //Do Something to handle error
        }
    });
}

function initSeat() {

    //This part to handle the web browser refresh to keep the welcome message appear to the user
    const userId = localStorage.getItem("email");
    if (userId && userId !== undefined) {
        loggedinResponse(userId);
    } else {
        //TODO this may make problem when you refresh the page
        welcomingRespone();
    }
    $.ajax({
        url: "../Controller/controllerHandler.php",
        type: "POST", //send it through post method
        data: {getPlaneInfo: 'yes'},
        dataType: "text",
        success: function (response) {
            let numberOfRows;
            let numberOfColumns;
            let Arr = response.toString().split("_");
            numberOfRows = Arr[0];
            numberOfColumns = Arr[1];
            let orderedList = document.getElementById("cabin");

            for (let i = 0; i < numberOfRows; i++) {
                let string = "row row--".concat((Number(i) + Number(1)).toString());
                let rowItemList = document.createElement("li");
                rowItemList.setAttribute("class", string);
                orderedList.appendChild(rowItemList);
                let innerOrderedList = document.createElement("ol");
                innerOrderedList.setAttribute("class", "seats");
                innerOrderedList.setAttribute("type", "A");
                rowItemList.appendChild(innerOrderedList);

                for (let j = 0; j < numberOfColumns; j++) {
                    let innerItemList = document.createElement("li");
                    innerItemList.setAttribute("class", "seat");
                    innerOrderedList.appendChild(innerItemList);
                    let inputElement = document.createElement("input");
                    inputElement.setAttribute("type", "checkbox");
                    let currentChar = String.fromCharCode("A".charCodeAt(0) + Number(j));
                    let currentId = ((Number(i) + Number(1)) + currentChar).toString();
                    inputElement.setAttribute("id", currentId);
                    inputElement.setAttribute("onclick", "selectSeat(this)");
                    innerItemList.appendChild(inputElement);
                    let labelElement = document.createElement("label");
                    labelElement.setAttribute("for", currentId);
                    labelElement.setAttribute("id", currentId + 'L');
                    let textElement = document.createTextNode(currentId.toString());
                    labelElement.appendChild(textElement);
                    innerItemList.appendChild(labelElement);
                }
            }

            updateView();
        },
        error: function (xhr) {

            //Do Something to handle error
        }
    });

}


function validateEmail(email) {
    let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function logOutSuccessesResponse() {
    document.getElementById("formSignUp").style.display = "none";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("signUpA").style.display = "block";
    document.getElementById("loginA").style.display = "block";
    document.getElementById("logoutID").style.display = "none";
    document.getElementById("buyID").style.display = "none";
    document.getElementById("updateID").style.display = "none";
    document.getElementById("welcomeParagraph").innerHTML = "You are logged out !";
    document.getElementById("welcomeParagraph").style.display = "block";
    console.log(localStorage.getItem("email"));
    localStorage.clear();
}

function loggedinResponse(response) {
    document.getElementById("formSignUp").style.display = "none";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("signUpA").style.display = "none";
    document.getElementById("loginA").style.display = "none";
    document.getElementById("logoutID").style.display = "block";
    document.getElementById("updateID").style.display = "block";
    document.getElementById("welcomeParagraph").innerHTML = "Welcome " + response + " to our Airline company";
    document.getElementById("welcomeParagraph").style.display = "block";
}

function loginFailedResponse() {
    document.getElementById("formSignUp").style.display = "none";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("signUpA").style.display = "block";
    document.getElementById("loginA").style.display = "block";
    document.getElementById("logoutID").style.display = "none";
    document.getElementById("buyID").style.display = "none";
    document.getElementById("updateID").style.display = "none";
    document.getElementById("welcomeParagraph").innerHTML = "Wrong username or password please try again. ";
    document.getElementById("welcomeParagraph").style.display = "block";
}

function welcomingRespone() {
    document.getElementById("formSignUp").style.display = "none";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("signUpA").style.display = "block";
    document.getElementById("loginA").style.display = "block";
    document.getElementById("logoutID").style.display = "none";
    document.getElementById("updateID").style.display = "none";
    document.getElementById("welcomeParagraph").innerHTML = "Please Login or Sign up to start buying seats.";
    document.getElementById("welcomeParagraph").style.display = "block";
    console.log(localStorage.getItem("email"));
}

function timeOutRespone() {
    document.getElementById("formSignUp").style.display = "none";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("signUpA").style.display = "block";
    document.getElementById("loginA").style.display = "block";
    document.getElementById("logoutID").style.display = "none";
    document.getElementById("buyID").style.display = "none";
    document.getElementById("updateID").style.display = "none";
    document.getElementById("welcomeParagraph").innerHTML = "You are logged out for timeout.";
    document.getElementById("welcomeParagraph").style.display = "block";
    console.log(localStorage.getItem("email"));
    localStorage.clear();
}


function duplicateEmail() {
    document.getElementById("formSignUp").style.display = "block";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("signUpA").style.display = "block";
    document.getElementById("loginA").style.display = "block";
    document.getElementById("logoutID").style.display = "none";
    document.getElementById("buyID").style.display = "none";
    document.getElementById("updateID").style.display = "none";
    document.getElementById("welcomeParagraph").innerHTML = "Email is already taken.";
    document.getElementById("welcomeParagraph").style.display = "block";
    localStorage.clear();
}

