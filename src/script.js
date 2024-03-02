document.addEventListener('DOMContentLoaded', function () {
    var fromInput = document.getElementById('from');
    var toInput = document.getElementById('to');
    var errorMessage = document.getElementById('error-message');

    fromInput.addEventListener('input', function () {
        var selectedTime = fromInput.value;

        var maxTime = addHours(selectedTime, 2);

        toInput.max = maxTime;

        errorMessage.textContent = '';
    });

    toInput.addEventListener('input', function () {
        var toTime = toInput.value;

        var maxTime = toInput.max;

        if (toTime > maxTime) {
            errorMessage.textContent = 'The maximum reservation time is 2 hours.';
        } else {
            errorMessage.textContent = '';
        }
    });

    // Function to add hours to a given time
    function addHours(time, hours) {
        var [hour, minute] = time.split(':').map(Number);
        var date = new Date(0, 0, 0, hour, minute);
        date.setHours(date.getHours() + hours);
        return `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
    }

    function updateToMax() {
        var fromTime = document.getElementById("from").value;
        document.getElementById("to").min = fromTime;
        document.getElementById("to").value = fromTime;
    }

    var reservationForm = document.getElementById('reservationForm');
    var confirmationMessage = document.getElementById('confirmationMessage');

    reservationForm.addEventListener('submit', function (event) {
        event.preventDefault();

        var isValid = validateForm();

        if (isValid) {
            confirmationMessage.innerHTML = 'Your reservation is succssfully completed!';
            confirmationMessage.style.display = 'block';
        }
    });

    function validateForm() {
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var phone = document.getElementById('phone').value;
        var date = document.getElementById('date').value;
        var from = document.getElementById('from').value;
        var to = document.getElementById('to').value;
        var guests = document.getElementById('guests').value;

        if (name.trim() === '' || email.trim() === '' || phone.trim() === '' || date === '' || from === '' || to === '' || guests.trim() === '') {
            alert('Please fill in all required fields.');
            return false;
        }

        var phoneRegex = /^[0-9]{3} [0-9]{3} [0-9]{4}$/;
        if (!phoneRegex.test(phone)) {
            alert('Please enter a valid phone number (### ### ####).');
            return false;
        }

        var today = new Date();
        var selectedDate = new Date(date);
        if (selectedDate < today) {
            alert('Please select a date in the future.');
            return false;
        }

        return true;
    }
});

document.getElementById('reservationForm').addEventListener('submit', function (event) {
    event.preventDefault();

    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var date = document.getElementById('date').value;
    var startingTime = document.getElementById('from').value;
    var endingTime = document.getElementById('to').value;
    var numberOfGuests = document.getElementById('guests').value;
    var message = document.getElementById('message').value;

    if (typeof (Storage) !== "undefined") {
        alert('Reservation information saved successfully!');
        localStorage.setItem("name", name);
        localStorage.setItem('email', email);
        localStorage.setItem('date', date);
        localStorage.setItem('from', startingTime);
        localStorage.setItem('to', endingTime);
        localStorage.setItem('guests', numberOfGuests); // corrected variable name
        localStorage.setItem('message', message);
    } else {
        alert('Sorry, your browser does not support local storage.');
    }
});

//Retriving data
// Save data to local storage
//var dataToSave = "name";
//localStorage.setItem("myDataKey", dataToSave);

// Retrieve data from local storage
//var retrievedData = localStorage.getItem("myDataKey");

// Update content of an HTML element with the retrieved data
//var myElement = document.getElementById("myElementId");
//myElement.textContent = retrievedData;

//Map section
var map = L.map('map');
var marker, circle, zoomed;
 
map.setView([45.405591, -73.941507], 13);
 
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
 
// Create a marker and circle at the fixed position
marker = L.marker([45.405591, -73.941507]).addTo(map);
circle = L.circle([45.405591, -73.941507], { radius: 100 }).addTo(map);
 
// Set the view and fit bounds initially
map.setView([45.405591, -73.941507]);
zoomed = map.fitBounds(circle.getBounds());
 
// Disable geolocation functionality
navigator.geolocation.watchPosition = null;
 
// Alert the user that geolocation is disabled
function error() {
    alert("Geolocation is disabled. The fixed position will be used.");
}