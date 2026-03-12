// ========================================
// HERO SLIDER FUNCTIONALITY
// ========================================

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
    if (slides[slideIndex-1] && dots[slideIndex-1]) {
        slides[slideIndex-1].classList.add("active");  
        dots[slideIndex-1].classList.add("active");
    }
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

// ========================================
// FAQ ACCORDION FUNCTIONALITY
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Slider (only if slider exists)
    const slides = document.getElementsByClassName("slide");
    if (slides.length > 0) {
        showSlides(slideIndex);
        startAutoSlide();
    }

    // Initialize FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');
    
    // Initialize: Close all FAQs on page load
    faqItems.forEach(item => {
        const answer = item.querySelector('.faq-answer');
        if (answer) {
            answer.style.maxHeight = '0';
        }
    });

    // Add click event to each FAQ question
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        if (question) {
            question.addEventListener('click', function() {
                const isActive = this.classList.contains('active');
                
                // Close all other FAQs
                faqItems.forEach(otherItem => {
                    const otherQuestion = otherItem.querySelector('.faq-question');
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    
                    if (otherQuestion && otherAnswer) {
                        otherQuestion.classList.remove('active');
                        otherAnswer.style.maxHeight = '0';
                    }
                });
                
                // Toggle current FAQ
                if (!isActive) {
                    this.classList.add('active');
                    const answer = this.nextElementSibling;
                    if (answer) {
                        // Calculate exact height needed
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                    }
                }
            });
        }
    });
});

// ========================================
// ACADEMIC CALENDAR FUNCTIONALITY
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    const monthYear = document.getElementById('monthYear');
    const calendarDays = document.getElementById('calendarDays');
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    // Important dates for the calendar (Example Data)
    const importantDates = {
        '0-10': 'event', // June 10 (Opening)
        '7-15': 'event', // August 15 (Midterms)
        '9-30': 'holiday', // October 30 (Holiday)
        '11-10': 'event', // December 10 (Finals)
        '11-20': 'event'  // December 20 (End)
    };

    function renderCalendar() {
        calendarDays.innerHTML = '';
        
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        // Update Month/Year Header
        const monthNames = ["January", "February", "March", "April", "May", "June", 
                            "July", "August", "September", "October", "November", "December"];
        monthYear.textContent = `${monthNames[currentMonth]} ${currentYear}`;

        // Empty slots for days before the 1st
        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.classList.add('calendar-day', 'empty');
            calendarDays.appendChild(emptyDiv);
        }

        // Days of the month
        for (let i = 1; i <= daysInMonth; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.classList.add('calendar-day');
            dayDiv.textContent = i;

            // Check for Today
            if (i === currentDate.getDate() && currentMonth === currentDate.getMonth() && currentYear === currentDate.getFullYear()) {
                dayDiv.classList.add('today');
            }

            // Check for Important Dates (Simple example logic)
            const dateKey = `${currentMonth}-${i}`;
            if (importantDates[dateKey]) {
                dayDiv.classList.add(importantDates[dateKey]);
            }

            calendarDays.appendChild(dayDiv);
        }
    }

    // Navigation Buttons
    prevBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });

    nextBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });

    // Initial Render
    renderCalendar();
});

// ========================================
// NEWS PAGE - FEATURED SLIDER
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // --- SLIDER VARIABLES ---
    let slideIndex = 1;
    let slideInterval;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');

    // Function to show specific slide
    function showSlides(n) {
        let i;
        
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
        if (slides[slideIndex-1] && dots[slideIndex-1]) {
            slides[slideIndex-1].classList.add("active");  
            dots[slideIndex-1].classList.add("active");
        }
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

    // Initialize Slider
    if (slides.length > 0) {
        showSlides(slideIndex);
        startAutoSlide();

        // Event Listeners for Navigation
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                clearInterval(slideInterval);
                slideIndex--;
                showSlides(slideIndex);
                startAutoSlide();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                clearInterval(slideInterval);
                slideIndex++;
                showSlides(slideIndex);
                startAutoSlide();
            });
        }

        // Event Listeners for Dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                currentSlide(index + 1);
            });
        });

        // Pause on Hover
        const sliderContainer = document.querySelector('.slider-container');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', function() {
                clearInterval(slideInterval);
            });
            sliderContainer.addEventListener('mouseleave', function() {
                startAutoSlide();
            });
        }
    }

    // ========================================
    // NEWS FILTER FUNCTIONALITY
    // ========================================
    
    const filterBtns = document.querySelectorAll('.filter-btn');
    const newsCards = document.querySelectorAll('.news-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            newsCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                
                if (filterValue === 'all' || cardCategory === filterValue) {
                    card.style.display = 'block';
                    // Add fade-in animation
                    card.style.opacity = '0';
                    setTimeout(() => card.style.opacity = '1', 50);
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // ========================================
    // NEWS SEARCH FUNCTIONALITY
    // ========================================

    const searchInput = document.getElementById('searchNews');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            
            newsCards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const summary = card.querySelector('.card-summary').textContent.toLowerCase();
                const category = card.querySelector('.card-category').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || summary.includes(searchTerm) || category.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // ========================================
    // SMOOTH SCROLLING FOR ANCHOR LINKS
    // ========================================

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // ========================================
    // SUBSCRIBE FORM HANDLING (Optional)
    // ========================================

    const subscribeForm = document.querySelector('.subscribe-form');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                alert('Thank you for subscribing to our newsletter!');
                this.reset();
            }
        });
    }
});

// ========================================
// MOBILE MENU TOGGLE
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('mobile-menu');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function() {
            // Toggle Active Class
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close menu when clicking a link (optional but recommended)
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    menuToggle.classList.remove('active');
                    navMenu.classList.remove('active');
                }
            });
        });
    }
});