document.addEventListener('DOMContentLoaded', () => {

    /* --- MENU MOBILE --- */
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const header = document.querySelector('.header');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }

    /* --- SCROLL REVEAL --- */
    const revealElements = document.querySelectorAll('.reveal-up');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.15 });

    revealElements.forEach(el => revealObserver.observe(el));

    /* --- CARROSSEL INFINITO --- */
    const track = document.querySelector('.carousel-track');
    if (track) {
        // Lógica de carrossel simplificada para o modelo inicial
        console.log('Carrossel Resimetal Carregado');
    }
});
