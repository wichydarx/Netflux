const player = new Plyr("#player");

const carousel = document.querySelector(".carouselItems");
const prevButton = document.querySelector(".prev-button");
const nextButton = document.querySelector(".next-button");
let currentIndex = 0;

function showSlide(index) {
  const items = carousel.querySelectorAll(".items");
  items.forEach((item, i) => {
    item.style.display = i >= index && i < index + 5 ? "block" : "none";
  });
}

function nextSlide() {
  currentIndex = Math.min(currentIndex + 1, carousel.children.length - 5);
  showSlide(currentIndex);
}

function prevSlide() {
  currentIndex = Math.max(currentIndex - 1, 0);
  showSlide(currentIndex);
}

prevButton.addEventListener("click", prevSlide);
nextButton.addEventListener("click", nextSlide);

showSlide(currentIndex);
