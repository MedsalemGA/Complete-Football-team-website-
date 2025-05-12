 // Navbar Menu Toggle
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

document.getElementById('menuIcon').addEventListener('click', () => {
    document.querySelector('.link1').classList.toggle('active');
});

// Form Submission with Loading Spinner
document.getElementById('update-form').addEventListener('submit', function(e) {
    var loading = document.getElementById('loading');
    loading.style.display = 'block';
    setTimeout(() => {
        loading.style.display = 'none';
    }, 1000); // Simulate async submission
});