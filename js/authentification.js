   // Navbar Menu Toggle
   document.getElementById("menuIcon").addEventListener("click", function () {
    var menu = document.querySelector(".nav-links");
    menu.classList.toggle("active");
});

document.addEventListener("click", function (event) {
    var menu = document.querySelector(".nav-links");
    var icon = document.getElementById("menuIcon");
    if (!menu.contains(event.target) && event.target !== icon) {
        menu.classList.remove("active");
    }
});

// Popup Handling
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

function showPopup(message) {
    if (message) {
        document.getElementById("popupMessage").textContent = message;
        document.getElementById("errorPopup").style.display = "flex";
        document.getElementById("closeButton").style.display = "inline-block";
    }
}

function closePopup() {
    document.getElementById("errorPopup").style.display = "none";
    window.location.href = "authentification.html";
}

// Check for error message in URL
const errorMessage = getQueryParam("error");
showPopup(errorMessage);