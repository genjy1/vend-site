/**
 * UI/UX Enhancements JavaScript
 * Improves user experience with smooth interactions
 */

(function() {
    'use strict';

    /**
     * Enhanced Lazy Loading with fade-in effect
     */
    function initLazyLoading() {
        var lazyImages = document.querySelectorAll('img.lazy');

        if ('IntersectionObserver' in window) {
            var imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var img = entry.target;
                        loadImage(img);
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for older browsers
            lazyImages.forEach(function(img) {
                loadImage(img);
            });
        }
    }

    function loadImage(img) {
        var src = img.getAttribute('data-src');
        if (src) {
            img.src = src;
            img.addEventListener('load', function() {
                img.classList.add('loaded');
                img.classList.remove('lazy');
            });
            img.addEventListener('error', function() {
                img.classList.add('error');
            });
        }
    }

    /**
     * Smooth scroll to anchors
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                var targetId = this.getAttribute('href');
                if (targetId === '#' || targetId.length <= 1) return;

                var target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Product card hover effects enhancement
     */
    function initProductCardEffects() {
        var productCards = document.querySelectorAll('.grid .product');

        productCards.forEach(function(card) {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });

            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '';
            });
        });
    }

    /**
     * Form validation enhancement
     */
    function initFormEnhancements() {
        var forms = document.querySelectorAll('form');

        forms.forEach(function(form) {
            var inputs = form.querySelectorAll('input[required], textarea[required]');

            inputs.forEach(function(input) {
                // Add visual feedback on blur
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });

                // Remove invalid state on focus
                input.addEventListener('focus', function() {
                    this.classList.remove('is-invalid');
                });
            });
        });
    }

    /**
     * Image gallery keyboard navigation
     */
    function initGalleryKeyboard() {
        var gallery = document.querySelector('.images');
        if (!gallery) return;

        document.addEventListener('keydown', function(e) {
            var thumbnails = gallery.querySelectorAll('.itm img');
            if (thumbnails.length === 0) return;

            var activeIndex = -1;
            thumbnails.forEach(function(thumb, index) {
                if (thumb.classList.contains('active')) {
                    activeIndex = index;
                }
            });

            var newIndex = activeIndex;

            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                newIndex = activeIndex > 0 ? activeIndex - 1 : thumbnails.length - 1;
            } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                newIndex = activeIndex < thumbnails.length - 1 ? activeIndex + 1 : 0;
            } else {
                return;
            }

            if (newIndex !== activeIndex && thumbnails[newIndex]) {
                thumbnails[newIndex].click();
            }
        });
    }

    /**
     * Button click feedback
     */
    function initButtonFeedback() {
        var buttons = document.querySelectorAll('button, .submit, .getoffer, .fastorder');

        buttons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                // Create ripple effect
                var ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');

                var rect = this.getBoundingClientRect();
                var size = Math.max(rect.width, rect.height);

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(function() {
                    ripple.remove();
                }, 600);
            });
        });
    }

    /**
     * Scroll to top button
     */
    function initScrollToTop() {
        var scrollBtn = document.createElement('button');
        scrollBtn.className = 'scroll-to-top';
        scrollBtn.innerHTML = '&uarr;';
        scrollBtn.setAttribute('aria-label', 'Scroll to top');
        scrollBtn.style.cssText = 'position:fixed;bottom:20px;right:20px;width:44px;height:44px;border-radius:50%;background:#e80780;color:#fff;border:none;cursor:pointer;opacity:0;visibility:hidden;transition:all 0.3s ease;z-index:999;font-size:20px;';

        document.body.appendChild(scrollBtn);

        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollBtn.style.opacity = '1';
                scrollBtn.style.visibility = 'visible';
            } else {
                scrollBtn.style.opacity = '0';
                scrollBtn.style.visibility = 'hidden';
            }
        });

        scrollBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Add loading state to buttons on form submit
     */
    function initSubmitLoading() {
        var forms = document.querySelectorAll('form');

        forms.forEach(function(form) {
            form.addEventListener('submit', function() {
                var submitBtn = this.querySelector('button[type="submit"], .submit, #submit');
                if (submitBtn) {
                    submitBtn.classList.add('is-loading');
                    submitBtn.disabled = true;

                    var originalText = submitBtn.textContent;
                    submitBtn.setAttribute('data-original-text', originalText);
                    submitBtn.textContent = 'Отправка...';

                    // Reset after 5 seconds if not handled
                    setTimeout(function() {
                        submitBtn.classList.remove('is-loading');
                        submitBtn.disabled = false;
                        submitBtn.textContent = submitBtn.getAttribute('data-original-text') || originalText;
                    }, 5000);
                }
            });
        });
    }

    /**
     * Price animation on special offers
     */
    function initPriceAnimation() {
        var specialPrices = document.querySelectorAll('.newprice, .newpr');

        if ('IntersectionObserver' in window) {
            var priceObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('price-animated');
                        priceObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            specialPrices.forEach(function(price) {
                priceObserver.observe(price);
            });
        }
    }

    /**
     * Initialize all enhancements
     */
    function init() {
        initLazyLoading();
        initSmoothScroll();
        initProductCardEffects();
        initFormEnhancements();
        initGalleryKeyboard();
        initButtonFeedback();
        initScrollToTop();
        initSubmitLoading();
        initPriceAnimation();
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Add ripple effect styles dynamically
    var rippleStyles = document.createElement('style');
    rippleStyles.textContent = '.ripple-effect{position:absolute;border-radius:50%;background:rgba(255,255,255,0.3);transform:scale(0);animation:ripple 0.6s linear;pointer-events:none}@keyframes ripple{to{transform:scale(4);opacity:0}}.is-loading{opacity:0.7;cursor:wait}.price-animated{animation:priceHighlight 0.5s ease}.is-invalid{border-color:#dc3545!important;box-shadow:0 0 0 3px rgba(220,53,69,0.1)!important}.is-valid{border-color:#28a745!important}@keyframes priceHighlight{0%,100%{transform:scale(1)}50%{transform:scale(1.05)}}';
    document.head.appendChild(rippleStyles);

})();
