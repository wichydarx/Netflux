console.log('Hello World from app.js');

async function fetchVideos (){
    await fetch('/api/videos')
    .then(response => response.json())
    .then(data => console.log(data));
}

fetchVideos();

// ----------------------------- Modal Video
var modal = document.getElementById("videoModal");
var openButton = document.getElementById("openModalButton");
var closeButton = document.getElementsByClassName("close")[0];

openButton.addEventListener("click", function() {
    modal.style.display = "block";
});

closeButton.addEventListener("click", function() {
    modal.style.display = "none";
});

window.addEventListener("click", function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
});

// --------------------------------

// ---------------------------------- Display des episodes

let saisonButtons = document.querySelectorAll(".saison-button");
let episodesSections = document.querySelectorAll(".episodes-section > div");

saisonButtons.forEach((button, index) => {
    button.addEventListener("click", function() {

        episodesSections.forEach(section => section.classList.add("d-none"));
        
        episodesSections[index].classList.remove("d-none");
    });
});

// ---------------------------------------------
