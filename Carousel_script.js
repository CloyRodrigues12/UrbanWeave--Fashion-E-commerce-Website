let currentSlide = 0; // Start with the first slide
const slides = document.querySelectorAll('.carousel-slide');
let slideInterval; // Variable to hold the interval ID

function showSlide(index) {
    if (index >= slides.length) currentSlide = 0;
    if (index < 0) currentSlide = slides.length - 1;
    const offset = -currentSlide * 100; // Correct position based on currentSlide
    document.querySelector('.carousel').style.transform = `translateX(${offset}%)`;
}

function nextSlide() {
    currentSlide++;
    showSlide(currentSlide);
}
function startAutoSlide() {
    slideInterval = setTimeout(autoSlide, 3000); // Start automatic sliding after 3 seconds
}
function autoSlide() {
    nextSlide();
    startAutoSlide(); // Keep sliding every 3 seconds
}

function pauseAutoSlide() {
    clearTimeout(slideInterval); // Stop automatic sliding
}

// Initial call to show the first slide properly on load
showSlide(currentSlide);

// Start automatic sliding after 3 seconds to ensure first image shows well
startAutoSlide();

// Add event listeners to control sliding on hover
const carousel = document.querySelector('.carousel');

carousel.addEventListener('mouseover', pauseAutoSlide);
carousel.addEventListener('mouseout', startAutoSlide);
