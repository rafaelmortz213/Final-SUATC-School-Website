let slideIndex = 1;
        let slideInterval;

        // Function to show specific slide
        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("slide");
            let dots = document.getElementsByClassName("dot");
            
            if (n > slides.length) {slideIndex = 1}    
            if (n < 1) {slideIndex = slides.length}
            
            // Hide all slides
            for (i = 0; i < slides.length; i++) {
                slides[i].classList.remove("active");  
            }
            
            // Remove active class from all dots
            for (i = 0; i < dots.length; i++) {
                dots[i].classList.remove("active");
            }
            
            // Show current slide and activate dot
            slides[slideIndex-1].classList.add("active");  
            dots[slideIndex-1].classList.add("active");
        }

        // Manual navigation from dots
        function currentSlide(n) {
            clearInterval(slideInterval); // Stop auto-play when user clicks
            slideIndex = n;
            showSlides(slideIndex);
            startAutoSlide(); // Restart auto-play
        }

        // Auto-play functionality
        function startAutoSlide() {
            slideInterval = setInterval(() => {
                slideIndex++;
                showSlides(slideIndex);
            }, 5000); // Change slide every 5 seconds
        }

        // Initialize
        showSlides(slideIndex);
        startAutoSlide();