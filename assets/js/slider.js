document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".hero-slide");
    const dots = document.querySelectorAll(".slider-dot");

    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
        slides.forEach((slide) => {
            slide.classList.remove("opacity-100");
            slide.classList.add("opacity-0");
        });

        dots.forEach((dot) => {
            dot.classList.remove("bg-white");
            dot.classList.add("bg-white/40");
        });

        slides[index].classList.remove("opacity-0");
        slides[index].classList.add("opacity-100");

        dots[index].classList.remove("bg-white/40");
        dots[index].classList.add("bg-white");
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function startSlider() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function resetSlider() {
        clearInterval(slideInterval);
        startSlider();
    }

    startSlider();

    dots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
            currentSlide = index;
            showSlide(currentSlide);

            // reset timer
            resetSlider();
        });
    });
});