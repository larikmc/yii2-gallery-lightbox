/**
 * Yii2 Gallery Lightbox
 * Pure Vanilla JavaScript Fullscreen Image Viewer with Touch/Swipe and Keyboard support
 * Author: LarikMC
 */
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory();
    } else {
        root.GalleryLightbox = factory();
    }
}(typeof self !== 'undefined' ? self : this, function () {
    'use strict';

    const instances = {};

    class LightboxInstance {
        constructor(modalId, options = {}) {
            this.modalId = modalId;
            this.modal = document.getElementById(modalId);
            this.options = Object.assign({
                enableKeyboard: true,
                enableTouchSwipe: true,
                closeOnBackdrop: true,
                loop: true,
                showThumbStrip: true,
                preloadCount: 3, // Number of adjacent images to prefetch ahead in background
                preloadPrev: 1,  // Number of adjacent images to prefetch behind in background
            }, options);

            this.items = [];
            this.currentIndex = 0;
            this.currentTitle = '';
            this.preloadedUrls = new Set();
            this.touchStartX = 0;
            this.touchStartY = 0;
            this.touchEndX = 0;
            this.touchEndY = 0;

            if (this.modal) {
                this.cacheElements();
                this.loadInitialData();
                this.bindEvents();
            }
        }

        cacheElements() {
            const m = this.modal;
            this.titleEl = document.getElementById(`${this.modalId}_title`);
            this.counterEl = document.getElementById(`${this.modalId}_counter`);
            this.directLinkEl = document.getElementById(`${this.modalId}_directLink`);
            this.closeBtn = document.getElementById(`${this.modalId}_closeBtn`);
            this.prevBtn = document.getElementById(`${this.modalId}_prevBtn`);
            this.nextBtn = document.getElementById(`${this.modalId}_nextBtn`);
            this.mainImg = document.getElementById(`${this.modalId}_mainImg`);
            this.loaderEl = document.getElementById(`${this.modalId}_loader`);
            this.thumbStrip = document.getElementById(`${this.modalId}_thumbStrip`);
            this.stage = m.querySelector('.pl-lightbox-stage');
        }

        loadInitialData() {
            const dataScript = document.getElementById(`${this.modalId}_data`) || document.getElementById('initialScreenshotsData');
            if (dataScript && dataScript.textContent.trim()) {
                try {
                    const parsed = JSON.parse(dataScript.textContent);
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        this.items = parsed;
                    }
                } catch (e) {
                    console.error('Failed to parse lightbox data', e);
                }
            }
            if (this.titleEl && this.titleEl.textContent.trim()) {
                this.currentTitle = this.titleEl.textContent.trim();
            } else {
                const heroTitle = document.querySelector('.pl-game-hero-title');
                if (heroTitle) {
                    this.currentTitle = heroTitle.textContent.trim();
                    if (this.titleEl) this.titleEl.textContent = this.currentTitle;
                }
            }

            // Populate thumbstrip if items present and strip is empty
            if (this.thumbStrip && this.thumbStrip.children.length === 0 && this.items.length > 0) {
                this.thumbStrip.innerHTML = this.items.map((item, idx) => `
                    <img src="${item.thumbnail || item.full}" 
                         alt="Thumb ${idx + 1}" 
                         class="pl-lightbox-thumb ${idx === 0 ? 'is-active' : ''}" 
                         data-index="${idx}">
                `).join('');
            }
        }

        bindEvents() {
            if (this.closeBtn) {
                this.closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.close();
                });
            }

            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.prev();
                });
            }

            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.next();
                });
            }

            if (this.options.closeOnBackdrop && this.modal) {
                this.modal.addEventListener('click', (e) => {
                    if (e.target === this.modal || e.target === this.stage || e.target.classList.contains('pl-lightbox-image-wrap')) {
                        this.close();
                    }
                });
            }

            if (this.thumbStrip) {
                this.thumbStrip.addEventListener('click', (e) => {
                    const thumb = e.target.closest('.pl-lightbox-thumb');
                    if (thumb && thumb.dataset.index !== undefined) {
                        const idx = parseInt(thumb.dataset.index, 10);
                        this.show(idx);
                    }
                });
            }

            if (this.options.enableTouchSwipe && this.stage) {
                this.stage.addEventListener('touchstart', (e) => {
                    this.touchStartX = e.changedTouches[0].screenX;
                    this.touchStartY = e.changedTouches[0].screenY;
                }, { passive: true });

                this.stage.addEventListener('touchend', (e) => {
                    this.touchEndX = e.changedTouches[0].screenX;
                    this.touchEndY = e.changedTouches[0].screenY;
                    this.handleSwipe();
                }, { passive: true });
            }
        }

        handleSwipe() {
            const diffX = this.touchEndX - this.touchStartX;
            const diffY = this.touchEndY - this.touchStartY;
            const threshold = 40;

            if (Math.abs(diffX) > Math.abs(diffY)) {
                // Horizontal Swipe
                if (Math.abs(diffX) > threshold) {
                    if (diffX < 0) {
                        this.next();
                    } else {
                        this.prev();
                    }
                }
            } else {
                // Vertical Swipe down to close
                if (diffY > threshold * 1.5) {
                    this.close();
                }
            }
        }

        setItems(items, title = null) {
            this.items = Array.isArray(items) ? items : [];
            this.preloadedUrls.clear();
            if (title !== null) {
                this.currentTitle = title;
                if (this.titleEl) {
                    this.titleEl.textContent = title;
                }
            }
            if (this.counterEl) {
                this.counterEl.textContent = this.items.length > 0 ? `${this.currentIndex + 1} / ${this.items.length}` : '';
            }
            if (this.thumbStrip) {
                this.thumbStrip.innerHTML = this.items.map((item, idx) => `
                    <img src="${item.thumbnail || item.full}" 
                         alt="Thumb ${idx + 1}" 
                         class="pl-lightbox-thumb ${idx === this.currentIndex ? 'is-active' : ''}" 
                         data-index="${idx}">
                `).join('');
            }
        }

        open(index = 0, items = null, title = null) {
            if (!this.modal) {
                this.modal = document.getElementById(this.modalId);
                if (this.modal) {
                    this.cacheElements();
                    this.bindEvents();
                } else {
                    return;
                }
            }

            if (items !== null) {
                this.setItems(items, title);
            } else if (title !== null) {
                this.currentTitle = title;
                if (this.titleEl) {
                    this.titleEl.textContent = title;
                }
            }

            // Fallback to initial data if items not yet loaded
            if (!this.items || this.items.length === 0) {
                this.loadInitialData();
            }

            // Fallback to window.currentScreenshots if defined
            if ((!this.items || this.items.length === 0) && Array.isArray(window.currentScreenshots) && window.currentScreenshots.length > 0) {
                this.setItems(window.currentScreenshots, window.currentScreenshotsTitle || null);
            }

            if (!this.items || this.items.length === 0) {
                return;
            }

            this.modal.style.display = 'flex';
            void this.modal.offsetWidth; // Force repaint
            this.modal.classList.add('is-open');
            this.modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            this.show(index);
        }

        close() {
            if (!this.modal) return;
            this.modal.classList.remove('is-open');
            this.modal.setAttribute('aria-hidden', 'true');
            setTimeout(() => {
                if (!this.modal.classList.contains('is-open')) {
                    this.modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            }, 250);
        }

        show(index) {
            // Fallback to initial data if items not yet loaded
            if (!this.items || this.items.length === 0) {
                this.loadInitialData();
            }

            // Fallback to window.currentScreenshots if defined
            if ((!this.items || this.items.length === 0) && Array.isArray(window.currentScreenshots) && window.currentScreenshots.length > 0) {
                this.setItems(window.currentScreenshots, window.currentScreenshotsTitle || null);
            }

            if (!this.items || this.items.length === 0) return;

            if (index < 0) {
                index = this.options.loop ? this.items.length - 1 : 0;
            }
            if (index >= this.items.length) {
                index = this.options.loop ? 0 : this.items.length - 1;
            }

            this.currentIndex = index;
            const current = this.items[index];

            if (this.counterEl) {
                this.counterEl.textContent = `${index + 1} / ${this.items.length}`;
            }

            const targetUrl = current.full || current.thumbnail;
            if (this.directLinkEl) {
                this.directLinkEl.href = targetUrl;
            }

            if (this.mainImg) {
                this.mainImg.classList.remove('is-loaded');

                if (this.loaderEl) {
                    this.loaderEl.style.display = 'flex';
                }

                this.mainImg.onload = () => {
                    if (this.currentIndex !== index) return;
                    if (this.loaderEl) {
                        this.loaderEl.style.display = 'none';
                    }
                    this.mainImg.style.display = 'block';
                    requestAnimationFrame(() => {
                        this.mainImg.classList.add('is-loaded');
                    });
                };

                this.mainImg.onerror = () => {
                    if (this.currentIndex !== index) return;
                    const fallback = current.thumbnail;
                    if (fallback && fallback !== targetUrl) {
                        this.mainImg.src = fallback;
                    } else {
                        if (this.loaderEl) this.loaderEl.style.display = 'none';
                    }
                };

                this.mainImg.src = targetUrl;
                this.mainImg.style.display = 'block';

                if (this.mainImg.complete && this.mainImg.naturalWidth > 0) {
                    if (this.loaderEl) this.loaderEl.style.display = 'none';
                    this.mainImg.classList.add('is-loaded');
                }
            }

            // Update thumbnail active state
            if (this.thumbStrip) {
                const thumbs = this.thumbStrip.querySelectorAll('.pl-lightbox-thumb');
                thumbs.forEach((th, i) => {
                    if (i === index) {
                        th.classList.add('is-active');
                        th.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    } else {
                        th.classList.remove('is-active');
                    }
                });
            }

            // Background preloading of adjacent images for instant next/prev flips
            this.preloadAdjacent();
        }

        preloadAdjacent() {
            if (!this.items || this.items.length <= 1) return;
            const total = this.items.length;
            const countAhead = typeof this.options.preloadCount === 'number' ? this.options.preloadCount : 3;
            const countPrev = typeof this.options.preloadPrev === 'number' ? this.options.preloadPrev : 1;

            // Preload ahead (next N images)
            for (let i = 1; i <= countAhead; i++) {
                const targetIdx = this.options.loop ? ((this.currentIndex + i) % total) : (this.currentIndex + i);
                if (targetIdx >= 0 && targetIdx < total) {
                    this.preloadItem(this.items[targetIdx]);
                }
            }

            // Preload behind (prev N image)
            for (let j = 1; j <= countPrev; j++) {
                const prevIdx = this.options.loop ? (((this.currentIndex - j) % total) + total) % total : (this.currentIndex - j);
                if (prevIdx >= 0 && prevIdx < total) {
                    this.preloadItem(this.items[prevIdx]);
                }
            }
        }

        preloadItem(item) {
            if (!item) return;
            const url = item.full || item.thumbnail;
            if (!url || this.preloadedUrls.has(url)) return;

            this.preloadedUrls.add(url);
            const img = new Image();
            img.src = url;
        }

        next() {
            this.show(this.currentIndex + 1);
        }

        prev() {
            this.show(this.currentIndex - 1);
        }
    }

    // Global Keydown Listener for active modal
    document.addEventListener('keydown', function (e) {
        for (const id in instances) {
            const inst = instances[id];
            if (inst.modal && inst.modal.classList.contains('is-open')) {
                if (e.key === 'Escape') {
                    inst.close();
                } else if (e.key === 'ArrowRight') {
                    inst.next();
                } else if (e.key === 'ArrowLeft') {
                    inst.prev();
                }
                break;
            }
        }
    });

    // Global Click Delegation for [data-gallery-lightbox]
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-gallery-lightbox]');
        if (trigger) {
            e.preventDefault();
            const modalId = trigger.getAttribute('data-gallery-lightbox') || 'plGalleryLightbox';
            const index = parseInt(trigger.getAttribute('data-index') || '0', 10);
            GalleryLightbox.open(modalId, index);
        }
    });

    // Public Facade
    const GalleryLightbox = {
        initModal: function (modalId, options = {}) {
            if (!instances[modalId]) {
                instances[modalId] = new LightboxInstance(modalId, options);
            }
            return instances[modalId];
        },
        getInstance: function (modalId = 'plGalleryLightbox') {
            if (!instances[modalId]) {
                instances[modalId] = new LightboxInstance(modalId);
            }
            return instances[modalId];
        },
        open: function (modalId = 'plGalleryLightbox', index = 0, items = null, title = null) {
            const inst = this.getInstance(modalId);
            inst.open(index, items, title);
        },
        close: function (modalId = 'plGalleryLightbox') {
            const inst = this.getInstance(modalId);
            inst.close();
        },
        next: function (modalId = 'plGalleryLightbox') {
            const inst = this.getInstance(modalId);
            inst.next();
        },
        prev: function (modalId = 'plGalleryLightbox') {
            const inst = this.getInstance(modalId);
            inst.prev();
        },
        setItems: function (modalId = 'plGalleryLightbox', items = [], title = null) {
            const inst = this.getInstance(modalId);
            inst.setItems(items, title);
        }
    };

    return GalleryLightbox;
}));
