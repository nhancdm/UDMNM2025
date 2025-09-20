
            document.addEventListener('DOMContentLoaded', function () {
                const arrowButton = document.querySelector('.quick-chat-arrow');

                // Show/hide arrow button based on scroll
                window.addEventListener('scroll', function () {
                    if (window.scrollY > 100) {
                        arrowButton.style.display = 'flex';
                    } else {
                        arrowButton.style.display = 'none';
                    }
                });

                // Scroll to top when arrow button is clicked
                arrowButton.addEventListener('click', function () {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        