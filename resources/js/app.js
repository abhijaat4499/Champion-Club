import Swiper from 'swiper';
import { A11y, Autoplay, EffectFade, FreeMode, Keyboard, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- Header ---------- */
function initHeader() {
    const header = document.querySelector('[data-header]');
    if (!header) return;

    const update = () => header.classList.toggle('is-scrolled', window.scrollY > 24);
    update();
    window.addEventListener('scroll', update, { passive: true });
}

/* ---------- Mobile menu ---------- */
function initMenu() {
    const menu = document.querySelector('[data-menu]');
    const openers = document.querySelectorAll('[data-menu-open]');
    if (!menu || !openers.length) return;

    const closers = menu.querySelectorAll('[data-menu-close], a');
    let lastFocus = null;

    const focusables = () => menu.querySelectorAll('a[href], button:not([disabled])');

    function open() {
        lastFocus = document.activeElement;
        menu.hidden = false;
        requestAnimationFrame(() => menu.classList.add('is-open'));
        document.documentElement.classList.add('overflow-hidden');
        openers.forEach((b) => b.setAttribute('aria-expanded', 'true'));
        focusables()[0]?.focus();
    }

    function close() {
        menu.classList.remove('is-open');
        document.documentElement.classList.remove('overflow-hidden');
        openers.forEach((b) => b.setAttribute('aria-expanded', 'false'));
        setTimeout(() => { menu.hidden = true; }, reduceMotion ? 0 : 350);
        lastFocus?.focus();
    }

    openers.forEach((b) => b.addEventListener('click', open));
    closers.forEach((el) => el.addEventListener('click', close));

    menu.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
        if (e.key !== 'Tab') return;
        const items = focusables();
        const first = items[0];
        const last = items[items.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
}

/* ---------- Scroll reveal ---------- */
function initReveal() {
    const items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    items.forEach((el) => observer.observe(el));
}

/* ---------- Counters ---------- */
function initCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const format = (n) => n.toLocaleString('en-IN');

    function run(el) {
        const target = Number(el.dataset.count);
        if (reduceMotion) { el.textContent = format(target); return; }
        el.style.minWidth = el.getBoundingClientRect().width + 'px';
        const start = performance.now();
        const duration = 1600;
        const tick = (now) => {
            const p = Math.min((now - start) / duration, 1);
            el.textContent = format(Math.round(target * (1 - Math.pow(1 - p, 3))));
            if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            run(entry.target);
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.6 });

    counters.forEach((el) => observer.observe(el));
}

/* ---------- Hero slider ---------- */
function initHero() {
    const el = document.querySelector('.hero-slider');
    if (!el) return;

    const delay = 6500;
    const goButtons = el.querySelectorAll('[data-hero-go]');
    const bullets = el.querySelectorAll('.hero-bullet');
    const counter = el.querySelector('[data-hero-current]');
    const toggle = el.querySelector('[data-hero-toggle]');
    const total = el.querySelectorAll('.swiper-slide').length;
    el.style.setProperty('--hero-delay', delay + 'ms');

    const warm = () => el.querySelectorAll('img[loading="lazy"]').forEach((img) => { img.loading = 'eager'; });
    if (document.readyState === 'complete') warm();
    else window.addEventListener('load', warm, { once: true });

    const swiper = new Swiper(el.querySelector('.swiper'), {
        modules: [EffectFade, Autoplay, Navigation, Keyboard, A11y],
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: reduceMotion ? 0 : 1100,
        loop: total > 2,
        rewind: total === 2,
        allowTouchMove: total > 1,
        keyboard: { enabled: total > 1 },
        autoplay: reduceMotion || total < 2 ? false : { delay, disableOnInteraction: false, pauseOnMouseEnter: false },
        navigation: { prevEl: el.querySelector('[data-hero-prev]'), nextEl: el.querySelector('[data-hero-next]') },
        a11y: { prevSlideMessage: 'Previous slide', nextSlideMessage: 'Next slide' },
        on: {
            init(s) { sync(s); },
            slideChange(s) { sync(s); },
        },
    });

    function sync(s) {
        const i = s.realIndex;
        bullets.forEach((b, n) => {
            b.classList.toggle('is-active', n === i);
            b.classList.toggle('is-done', n < i);
            const bar = b.querySelector('span');
            if (n === i && bar) { bar.style.animation = 'none'; void bar.offsetWidth; bar.style.animation = ''; }
        });
        goButtons.forEach((b, n) => b.setAttribute('aria-current', n === i ? 'true' : 'false'));
        if (counter) counter.textContent = String(i + 1).padStart(2, '0');
    }

    goButtons.forEach((b, n) => b.addEventListener('click', () => (total > 2 ? swiper.slideToLoop(n) : swiper.slideTo(n))));

    function setPaused(paused) {
        el.classList.toggle('is-paused', paused);
        if (toggle) {
            toggle.setAttribute('aria-pressed', String(paused));
            toggle.setAttribute('aria-label', paused ? 'Play slideshow' : 'Pause slideshow');
            toggle.querySelector('[data-icon-pause]')?.toggleAttribute('hidden', paused);
            toggle.querySelector('[data-icon-play]')?.toggleAttribute('hidden', !paused);
        }
        if (!swiper.autoplay) return;
        paused ? swiper.autoplay.pause() : swiper.autoplay.resume();
    }

    if (reduceMotion) {
        setPaused(true);
    }

    toggle?.addEventListener('click', () => setPaused(!el.classList.contains('is-paused')));
    el.addEventListener('focusin', () => setPaused(true));
    el.addEventListener('touchstart', () => setPaused(true), { passive: true, once: true });
}

/* ---------- Carousels ---------- */
function initCarousels() {
    document.querySelectorAll('[data-carousel]').forEach((wrap) => {
        const type = wrap.dataset.carousel;
        const container = wrap.querySelector('.swiper');
        if (!container) return;

        const common = {
            modules: [Navigation, Pagination, A11y, Keyboard, FreeMode, Autoplay],
            speed: reduceMotion ? 0 : 700,
            keyboard: { enabled: true, onlyInViewport: true },
            watchOverflow: true,
            navigation: { prevEl: wrap.querySelector('[data-prev]'), nextEl: wrap.querySelector('[data-next]') },
            pagination: { el: wrap.querySelector('[data-dots]'), clickable: true },
        };

        const presets = {
            trainers: {
                slidesPerView: 1.08,
                spaceBetween: 16,
                breakpoints: { 640: { slidesPerView: 2, spaceBetween: 20 }, 1024: { slidesPerView: 3, spaceBetween: 24 } },
            },
            plans: {
                centeredSlides: true,
                slidesPerView: 1.2,
                spaceBetween: 0,
                initialSlide: Number(wrap.dataset.initial || 0),
                breakpoints: {
                    640: { slidesPerView: 1.8, spaceBetween: 0 },
                    1024: { slidesPerView: 3, centeredSlides: false, spaceBetween: 24, initialSlide: 0 },
                },
            },
            reviews: {
                slidesPerView: 1.08,
                slidesPerGroup: 1,
                spaceBetween: 16,
                breakpoints: { 640: { slidesPerView: 2, spaceBetween: 20 }, 1024: { slidesPerView: 3, spaceBetween: 24 } },
            },
            videos: {
                slidesPerView: 1.08,
                slidesPerGroup: 1,
                spaceBetween: 16,
                breakpoints: { 640: { slidesPerView: 2, spaceBetween: 20 }, 1024: { slidesPerView: 3, spaceBetween: 24 } },
            },
            stats: {
                slidesPerView: 2.15,
                spaceBetween: 0,
                breakpoints: { 640: { slidesPerView: 4 } },
            },
        };

        new Swiper(container, { ...common, ...(presets[type] || {}) });
    });
}

/* ---------- Phone inputs ---------- */
function initPhoneInputs() {
    document.querySelectorAll('input[data-phone]').forEach((input) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
    });
}

/* ---------- Gallery lightbox ---------- */
function initGallery() {
    const dialog = document.querySelector('[data-lightbox]');
    const items = [...document.querySelectorAll('[data-gallery-item]')];
    if (!dialog || !items.length || typeof dialog.showModal !== 'function') return;

    const img = dialog.querySelector('[data-lightbox-img]');
    const caption = dialog.querySelector('[data-lightbox-caption]');
    const count = dialog.querySelector('[data-lightbox-count]');
    let current = 0;

    const show = (index) => {
        current = (index + items.length) % items.length;
        const item = items[current];
        img.src = item.dataset.src;
        img.alt = item.dataset.alt || '';
        caption.textContent = item.dataset.caption || '';
        count.textContent = `${current + 1} / ${items.length}`;
    };

    items.forEach((item, index) => item.addEventListener('click', () => {
        show(index);
        dialog.showModal();
        document.documentElement.style.overflow = 'hidden';
    }));

    dialog.addEventListener('close', () => {
        document.documentElement.style.overflow = '';
        items[current].focus();
    });

    dialog.querySelector('[data-lightbox-close]').addEventListener('click', () => dialog.close());
    dialog.querySelector('[data-lightbox-prev]').addEventListener('click', () => show(current - 1));
    dialog.querySelector('[data-lightbox-next]').addEventListener('click', () => show(current + 1));
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog || event.target.classList.contains('gallery-lightbox-inner')) dialog.close();
    });
    dialog.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') show(current - 1);
        if (event.key === 'ArrowRight') show(current + 1);
    });

    let startX = null;
    dialog.addEventListener('touchstart', (event) => { startX = event.touches[0].clientX; }, { passive: true });
    dialog.addEventListener('touchend', (event) => {
        if (startX === null) return;
        const delta = event.changedTouches[0].clientX - startX;
        if (Math.abs(delta) > 50) show(current + (delta < 0 ? 1 : -1));
        startX = null;
    });
}

/* ---------- Video reviews ---------- */
function initVideos() {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-video-src]');
        if (!button) return;

        document.querySelectorAll('[data-video-embed] iframe').forEach((frame) => {
            const box = frame.closest('[data-video-embed]');
            frame.remove();
            box.querySelector('[data-video-src]').hidden = false;
        });

        const frame = document.createElement('iframe');
        frame.src = button.dataset.videoSrc;
        frame.title = button.getAttribute('aria-label') || 'Video review';
        frame.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        frame.allowFullscreen = true;
        frame.referrerPolicy = 'strict-origin-when-cross-origin';
        frame.className = 'absolute inset-0 size-full border-0';
        button.hidden = true;
        button.closest('[data-video-embed]').appendChild(frame);
    });
}

/* ---------- Maps ---------- */
function initMaps() {
    const maps = document.querySelectorAll('[data-map]');
    if (!maps.length) return;

    const mount = (box) => {
        if (box.dataset.mapLoaded) return;
        box.dataset.mapLoaded = '1';
        const frame = document.createElement('iframe');
        frame.src = box.dataset.map;
        frame.title = 'Map showing ChampionClub location';
        frame.referrerPolicy = 'no-referrer-when-downgrade';
        frame.className = 'map-frame absolute inset-0 size-full border-0 grayscale-[35%]';
        frame.addEventListener('load', () => frame.classList.add('is-loaded'));
        box.appendChild(frame);
    };

    const start = () => {
        if (!('IntersectionObserver' in window)) return maps.forEach(mount);
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                io.unobserve(entry.target);
                mount(entry.target);
            });
        }, { rootMargin: '400px 0px' });
        maps.forEach((box) => io.observe(box));
    };

    if (document.readyState === 'complete') start();
    else window.addEventListener('load', start, { once: true });
}

window.__appReady = true;

initHeader();
initMenu();
initReveal();
initCounters();
initHero();
initCarousels();
initPhoneInputs();
initMaps();
initGallery();
initVideos();

const year = document.querySelector('[data-year]');
if (year) year.textContent = new Date().getFullYear();
