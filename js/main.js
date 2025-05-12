var swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    pagination: { el: ".swiper-pagination", clickable: true },
    navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
});

document.getElementById("menuIcon").addEventListener("click", function () {
    var menu = document.getElementById("dropdownMenu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
});

document.addEventListener("click", function (event) {
    var menu = document.getElementById("dropdownMenu");
    var icon = document.getElementById("menuIcon");
    if (!menu.contains(event.target) && event.target !== icon) {
        menu.style.display = "none";
    }
});

// Cookie Popup
window.onload = function() {
    if (!localStorage.getItem('cookieConsent')) {
        setTimeout(() => {
            document.getElementById('cookie-popup').classList.add('active');
        }, 1000);
    }
};

document.getElementById('accept-cookies').addEventListener('click', () => {
    localStorage.setItem('cookieConsent', 'accepted');
    document.getElementById('cookie-popup').classList.remove('active');
});

document.getElementById('refuser-cookies').addEventListener('click', () => {
    localStorage.setItem('cookieConsent', 'refused');
    document.getElementById('cookie-popup').classList.remove('active');
});

// Mobile Menu Toggle
document.getElementById('menuIcon').addEventListener('click', () => {
    document.querySelector('.link1').classList.toggle('active');
});