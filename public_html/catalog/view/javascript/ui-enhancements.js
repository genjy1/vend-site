/**
 * Modern UI/UX Enhancements JavaScript
 * Smooth interactions and micro-animations
 */

(function() {
    'use strict';

    /**
     * Enhanced Lazy Loading with intersection observer
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
                rootMargin: '100px 0px',
                threshold: 0.01
            });

            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        } else {
            lazyImages.forEach(loadImage);
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
        }
    }

    /**
     * Modern Scroll to Top Button
     */
    function initScrollToTop() {
        var scrollBtn = document.createElement('button');
        scrollBtn.className = 'scroll-to-top';
        scrollBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>';
        scrollBtn.setAttribute('aria-label', 'Прокрутить вверх');
        document.body.appendChild(scrollBtn);

        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    if (window.pageYOffset > 400) {
                        scrollBtn.classList.add('visible');
                    } else {
                        scrollBtn.classList.remove('visible');
                    }
                    ticking = false;
                });
                ticking = true;
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
     * Product Card Tilt Effect (subtle 3D)
     */
    function initCardTiltEffect() {
        if (window.matchMedia('(hover: hover)').matches) {
            var cards = document.querySelectorAll('.grid .product');

            cards.forEach(function(card) {
                card.addEventListener('mousemove', function(e) {
                    var rect = card.getBoundingClientRect();
                    var x = e.clientX - rect.left;
                    var y = e.clientY - rect.top;
                    var centerX = rect.width / 2;
                    var centerY = rect.height / 2;
                    var rotateX = (y - centerY) / 20;
                    var rotateY = (centerX - x) / 20;

                    card.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-8px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    card.style.transform = '';
                });
            });
        }
    }

    /**
     * Form Input Animations
     */
    function initFormAnimations() {
        var inputs = document.querySelectorAll('input[type="text"], input[type="tel"], input[type="email"], textarea');

        inputs.forEach(function(input) {
            // Floating label effect preparation
            var wrapper = input.parentElement;

            input.addEventListener('focus', function() {
                wrapper.classList.add('input-focused');
            });

            input.addEventListener('blur', function() {
                wrapper.classList.remove('input-focused');
                if (this.value.trim() !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });

            // Validation feedback
            if (input.hasAttribute('required')) {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });

                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            }
        });
    }

    /**
     * Button Ripple Effect
     */
    function initButtonRipple() {
        var buttons = document.querySelectorAll('button, .submit, .getoffer, .fastorder, #add, #add2');

        buttons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                var rect = this.getBoundingClientRect();
                var ripple = document.createElement('span');
                var size = Math.max(rect.width, rect.height);
                var x = e.clientX - rect.left - size / 2;
                var y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = 'position:absolute;border-radius:50%;background:rgba(255,255,255,0.4);pointer-events:none;width:' + size + 'px;height:' + size + 'px;left:' + x + 'px;top:' + y + 'px;transform:scale(0);animation:rippleEffect 0.6s ease-out;';

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
     * Image Gallery Keyboard Navigation
     */
    function initGalleryKeyboard() {
        var gallery = document.querySelector('.images');
        if (!gallery) return;

        var thumbnails = gallery.querySelectorAll('.itm img');
        if (thumbnails.length === 0) return;

        document.addEventListener('keydown', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

            var activeIndex = -1;
            thumbnails.forEach(function(thumb, index) {
                if (thumb.classList.contains('active')) {
                    activeIndex = index;
                }
            });

            var newIndex = activeIndex;

            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                newIndex = activeIndex > 0 ? activeIndex - 1 : thumbnails.length - 1;
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                newIndex = activeIndex < thumbnails.length - 1 ? activeIndex + 1 : 0;
            } else {
                return;
            }

            if (thumbnails[newIndex]) {
                thumbnails[newIndex].click();
            }
        });
    }

    /**
     * Smooth Reveal Animation on Scroll
     */
    function initScrollReveal() {
        var revealElements = document.querySelectorAll('.services > div, .similar .item, .examples .item, .productfeature');

        if ('IntersectionObserver' in window) {
            var revealObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            revealElements.forEach(function(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                revealObserver.observe(el);
            });
        }
    }

    /**
     * Price Counter Animation
     */
    function initPriceAnimation() {
        var priceElements = document.querySelectorAll('.newprice, .newpr, .pr');

        if ('IntersectionObserver' in window) {
            var priceObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.animation = 'pricePopIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
                        priceObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            priceElements.forEach(function(el) {
                priceObserver.observe(el);
            });
        }
    }

    /**
     * Form Submit Loading State
     */
    function initSubmitLoading() {
        var forms = document.querySelectorAll('form');

        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                var submitBtn = this.querySelector('button[type="submit"], .submit, #submit');
                if (submitBtn && !submitBtn.classList.contains('is-loading')) {
                    submitBtn.classList.add('is-loading');
                    submitBtn.setAttribute('data-original-text', submitBtn.textContent);

                    setTimeout(function() {
                        submitBtn.classList.remove('is-loading');
                    }, 5000);
                }
            });
        });
    }

    /**
     * Smooth Anchor Scrolling
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                var targetId = this.getAttribute('href');
                if (targetId === '#' || targetId.length <= 1) return;

                var target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    var headerOffset = 80;
                    var elementPosition = target.getBoundingClientRect().top;
                    var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Cursor Follower for Product Images
     */
    function initImageZoomCursor() {
        var mainImage = document.querySelector('.fullimage');
        if (!mainImage) return;

        var zoomIcon = document.createElement('div');
        zoomIcon.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/><path d="M11 8v6M8 11h6"/></svg>';
        zoomIcon.style.cssText = 'position:fixed;pointer-events:none;opacity:0;transition:opacity 0.2s;z-index:1000;color:#e80780;';
        document.body.appendChild(zoomIcon);

        mainImage.addEventListener('mouseenter', function() {
            zoomIcon.style.opacity = '1';
        });

        mainImage.addEventListener('mouseleave', function() {
            zoomIcon.style.opacity = '0';
        });

        mainImage.addEventListener('mousemove', function(e) {
            zoomIcon.style.left = (e.clientX + 15) + 'px';
            zoomIcon.style.top = (e.clientY + 15) + 'px';
        });
    }

    /**
     * Add Dynamic Styles
     */
    function addDynamicStyles() {
        var styles = document.createElement('style');
        styles.textContent = [
            '@keyframes rippleEffect {',
            '    to { transform: scale(4); opacity: 0; }',
            '}',
            '@keyframes pricePopIn {',
            '    0% { transform: scale(0.8); opacity: 0; }',
            '    50% { transform: scale(1.1); }',
            '    100% { transform: scale(1); opacity: 1; }',
            '}',
            '.input-focused { position: relative; }',
            '.input-focused::after {',
            '    content: "";',
            '    position: absolute;',
            '    bottom: 0;',
            '    left: 50%;',
            '    width: 100%;',
            '    height: 2px;',
            '    background: linear-gradient(135deg, #ff2d9b 0%, #e80780 100%);',
            '    transform: translateX(-50%) scaleX(0);',
            '    transition: transform 0.3s ease;',
            '}',
            '.input-focused:focus-within::after {',
            '    transform: translateX(-50%) scaleX(1);',
            '}'
        ].join('\n');
        document.head.appendChild(styles);
    }

    /**
     * Initialize Everything
     */
    function init() {
        addDynamicStyles();
        initLazyLoading();
        initScrollToTop();
        initCardTiltEffect();
        initFormAnimations();
        initButtonRipple();
        initGalleryKeyboard();
        initScrollReveal();
        initPriceAnimation();
        initSubmitLoading();
        initSmoothScroll();
        initImageZoomCursor();
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
