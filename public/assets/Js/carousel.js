class Carousel {
  constructor(element) {
    // Retrieve every elements needed by the carousel (arrows, dots, ...)
    this.content = element.querySelector(".carouselItems");
    this.arrowLeft = element.querySelector(".prev-button");
    this.arrowRight = element.querySelector(".next-button");
    this.activeElement = 0;
  }

  // This function will make the element at index n visible in the carousel
  activateElement(n) {
    this.activeElement = n;
    this.content.scrollTo(
      this.content.children[n].offsetLeft - this.content.offsetLeft,
      0
    );
  }

  // This function will register and required event listeners
  addEventListeners() {
    // To handle the click on the dots
    this.arrowLeft.addEventListener("click", () =>
      this.activateElement(
        (this.activeElement === 0
          ? this.content.children.length
          : this.activeElement) - 1
      )
    ); // If we are on the first element and we go left, then we activate the last one
    this.arrowRight.addEventListener("click", () =>
      this.activateElement(
        this.activeElement === this.content.children.length - 1
          ? 0
          : this.activeElement + 1
      )
    ); // If we are on the last element and we go right, then we activate the first one
  }
}

// Initialize the carousel for every HTML element with class "carousel"
const carousels = document.getElementsByClassName("carouselContainer");
for (const carousel of carousels) new Carousel(carousel).addEventListeners();
