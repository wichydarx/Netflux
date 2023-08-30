const player = new Plyr("#player");

const carousel = document.querySelector(".carouselItems");
const prevButton = document.querySelector(".prev-button");
const nextButton = document.querySelector(".next-button");
let currentIndex = 0;

function showSlide(index) {
  const items = carousel.querySelectorAll(".items");
  items.forEach((item, i) => {
    item.style.display = i >= index && i < index + 4 ? "block" : "none";
  });
}

function scrollToSlide(index) {
  currentIndex = index;
  showSlide(currentIndex);
  carousel.style.transform = `translateX(-${
    currentIndex * 20
  }%)`; /* 20% pour 5 éléments */
}

function nextSlide() {
  currentIndex = Math.min(currentIndex + 1, carousel.children.length - 5);
  scrollToSlide(currentIndex);
}

function prevSlide() {
  currentIndex = Math.max(currentIndex - 1, 0);
  scrollToSlide(currentIndex);
}

prevButton.addEventListener("click", prevSlide);
nextButton.addEventListener("click", nextSlide);

showSlide(currentIndex);
