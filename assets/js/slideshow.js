export function initSlideshow() {

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setupSlideshow();
        });
    } else {
        // DOM déjà chargé, exécuter directement
        setupSlideshow();
    }
}

function setupSlideshow() {
    let slideIndex = 1;

    // Vérifier que les éléments existent
    const slides = document.getElementsByClassName("mySlides");
    if (slides.length === 0) return; // Sortir si pas de diapositives

    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");

        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}

        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        slides[slideIndex-1].style.display = "block";
    }


    window.plusSlides = plusSlides;
    window.currentSlide = currentSlide;
}


initSlideshow();
