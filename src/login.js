function validateSignupForm() {


    const fullName = document.getElementById("fullName").value.trim();
    const userName = document.getElementById("userName").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("pwd").value.trim();
    const address1 = document.getElementById("address1").value.trim();
    const phonenumber = document.getElementById("phonenumber").value.trim();
    const city = document.getElementById("city").value.trim();
    const province = document.getElementById("prov").value.trim();
    const postalCode = document.getElementById("postalCode").value.trim();

    if (!isValidName(fullName) || !userName || !isValidName(city) || !email || !password || !address1 || !phonenumber || !province || !postalCode) {
        alert('Please fill in all required fields.');
        return false;
    }

    var phoneRegex = /^[0-9]{3} [0-9]{3} [0-9]{4}$/;
    if (!phoneRegex.test(phonenumber)) {
        alert('Please enter a valid phone number (### ### ####).');
        return false;
    }

    // Validate Canadian postal code format
    var postalCodeRegex = /^[A-Za-z]\d[A-Za-z] \d[A-Za-z]\d$/;
    if (!postalCodeRegex.test(postalCode)) {
        alert("Please enter a valid Canadian postal code (e.g., A1A 1A1)!");
        return false;
    }

    return true;

}


function isValidName(fullName) {
    if (!/^[A-Za-z\s]+$/.test(fullName)) {
        alert("Please enter a valid name with only letters!");
        return false;
    }
    return true;
}



document.getElementById('user-registration-form').addEventListener('submit', function (event) {
    event.preventDefault();
   
    var userName = document.getElementById('userName').value;
    var isValid = validateSignupForm();

    if (isValid) {
        document.getElementById('form-container').style.display = 'none';
        
        document.getElementById('userNameDisplay').innerText = "Congratulations "+ userName + "!  You are now a of FOODI FOODI family. " ;
        var confirmationMessage = document.getElementById('confirmation-message');
        confirmationMessage.style.display = 'block';
    }
});




function login() {
    var userName = document.getElementById('userName').value;
    var password = document.getElementById('login-pwd').value;

    document.getElementById("login-form").style.display = 'none';
    document.getElementById('userNameDisplay').innerText = "Welcome back " + userName;
    document.getElementById('welcomeMessage').style.display = 'block';

    if (userName && password) {
        document.getElementById('userNameDisplay').innerText = "Welcome back " + userName;
        document.getElementById('welcomeMessage').style.display = 'block';
        headerLoginText.innerText = userName; // Update header login text to display username
    }
}

function login() {
    var userNameInput = document.getElementById('userName');
    var passwordInput = document.getElementById('login-pwd');

    if (userNameInput.value.trim() === "") {
        alert("Please enter a username.");
        return;
    }

    if (passwordInput.value.trim() === "") {
        alert("Please enter a password.");
        return;
    }

    if (passwordInput.value.length < 8) {
        alert("Password must be at least 8 characters.");
        return;
    }

    document.getElementById("login-form").style.display = 'none';
    document.getElementById('userNameDisplay').innerText = "Welcome back " + userNameInput.value;
    document.getElementById('welcomeMessage').style.display = 'block';
    headerLoginText.innerText = userNameInput.value; // Update header login text to display username
}


document.getElementById('login-form').addEventListener('button', function (event) {
    event.preventDefault();

    var userName = document.getElementById('userName').value;

    if (typeof (Storage) !== "undefined") {
        alert('Reservation information saved successfully!');
        localStorage.setItem("userName", userName);
    }
    else {
    alert('Sorry, your browser does not support local storage.');
}
});

// Retriving data
// Save data to local storage
var dataToSave = "userName";
localStorage.setItem("myDataKey", dataToSave);

// Retrieve data from local storage
var retrievedData = localStorage.getItem("myDataKey");

// Update content of an HTML element with the retrieved data
var myElement = document.getElementById("myElementId");
myElement.textContent = retrievedData;

