document.addEventListener('DOMContentLoaded', () => {

    /* --- MENU MOBILE --- */
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const header = document.querySelector('.header');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (navMenu.classList.contains('active')) {
                icon.classList.replace('ph-list', 'ph-x');
            } else {
                icon.classList.replace('ph-x', 'ph-list');
            }
        });
    }

    // Fechar menu ao clicar em um link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (navMenu && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                if (menuToggle) menuToggle.querySelector('i').classList.replace('ph-x', 'ph-list');
            }
        });
    });

    /* --- HEADER STICKY --- */
    window.addEventListener('scroll', () => {
        if (header) {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    });

    /* --- SCROLL REVEAL (ANIMAÇÕES) --- */
    const revealElements = document.querySelectorAll('.reveal-up');
    
    const revealOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    revealElements.forEach(el => {
        revealObserver.observe(el);
    });

    /* --- ACCORDION FAQ --- */
    const accordionHeaders = document.querySelectorAll('.accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const content = header.nextElementSibling;
            const isActive = header.classList.contains('active');

            // Fechar todos
            document.querySelectorAll('.accordion-header').forEach(h => {
                h.classList.remove('active');
                if (h.nextElementSibling) h.nextElementSibling.style.maxHeight = null;
            });

            // Se não estava ativo, abre.
            if (!isActive && content) {
                header.classList.add('active');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });

    /* --- ENVIO DO FORMULÁRIO PARA WHATSAPP --- */
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Note: The form in HTML was simplified, so I'll use generic selectors or update the HTML
            const nomeInput = contactForm.querySelector('input[type="text"]');
            const assuntoSelect = contactForm.querySelector('select');
            const mensagemTextarea = contactForm.querySelector('textarea');

            const nome = nomeInput ? nomeInput.value : '';
            const assunto = assuntoSelect ? assuntoSelect.value : '';
            const mensagem = mensagemTextarea ? mensagemTextarea.value : '';

            // Formata a mensagem para o URL do WhatsApp
            let zapText = `Olá, gostaria de informações sobre beneficiamento.\n\n`;
            zapText += `*Nome/Empresa:* ${nome}\n`;
            zapText += `*Assunto:* ${assunto}\n`;
            
            if (mensagem.trim() !== '') {
                zapText += `*Mensagem:* ${mensagem}`;
            }

            const encodedText = encodeURIComponent(zapText);
            const wppNumber = '5511947132326';
            const wppUrl = `https://wa.me/${wppNumber}?text=${encodedText}`;

            window.open(wppUrl, '_blank');
        });
    }

    /* --- UPDATE YEAR IN FOOTER --- */
    const yearSpan = document.getElementById('year');
    if (yearSpan) {
        yearSpan.textContent = new Date().getFullYear();
    }
    /* --- CARROSSEL INFINITO (3 COLUNAS) --- */
    const track = document.querySelector('.carousel-track');
    const originalSlides = Array.from(document.querySelectorAll('.carousel-slide') || []);
    const nextButton = document.querySelector('.carousel-button.next');
    const prevButton = document.querySelector('.carousel-button.prev');
    const indicatorsContainer = document.querySelector('.carousel-indicators');
    
    if (track && originalSlides.length > 0) {
        // Quantidade de slides dependendo do responsivo (apenas para fallback visual, o CSS que controla o tamanho)
        const slideCount = originalSlides.length;
        
        // Vamos clonar todos os originais para colocar antes e depois
        originalSlides.forEach(s => {
            const clone = s.cloneNode(true);
            track.appendChild(clone);
        });
        
        [...originalSlides].reverse().forEach(s => {
            const clone = s.cloneNode(true);
            track.insertBefore(clone, track.firstChild);
        });

        // currentIndex começa nos originais (apos os clones de início)
        let currentIndex = slideCount; 
        let isTransitioning = false;
        
        const finalizeTransition = () => {
            isTransitioning = false;
            if (currentIndex >= slideCount * 2) {
                currentIndex -= slideCount;
                updatePosition(false);
            } else if (currentIndex < slideCount) {
                currentIndex += slideCount;
                updatePosition(false);
            }
        };

        track.addEventListener('transitionend', finalizeTransition);

        const updatePosition = (withTransition = true) => {
            if (!originalSlides[0]) return;
            const slideWidth = track.firstElementChild.getBoundingClientRect().width;
            track.style.transition = withTransition ? 'transform 0.5s ease-in-out' : 'none';
            track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
            
            if (withTransition) {
                clearTimeout(track.transitionTimeout);
                track.transitionTimeout = setTimeout(finalizeTransition, 550);
            } else {
                isTransitioning = false;
            }
        };

        // Setup indicators
        originalSlides.forEach((_, index) => {
            const indicator = document.createElement('button');
            indicator.classList.add('indicator');
            if (index === 0) indicator.classList.add('active');
            indicator.addEventListener('click', () => {
                if (isTransitioning) return;
                currentIndex = index + slideCount;
                updatePosition();
                updateIndicators();
            });
            indicatorsContainer.appendChild(indicator);
        });

        const indicators = Array.from(document.querySelectorAll('.indicator'));
        
        const updateIndicators = () => {
            let visualIndex = (currentIndex - slideCount) % slideCount;
            if (visualIndex < 0) visualIndex += slideCount;
            indicators.forEach(ind => ind.classList.remove('active'));
            if (indicators[visualIndex]) indicators[visualIndex].classList.add('active');
        };

        // transitionend ja lidado no finalizeTransition

        nextButton.addEventListener('click', () => {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex++;
            updatePosition();
            updateIndicators();
        });

        prevButton.addEventListener('click', () => {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex--;
            updatePosition();
            updateIndicators();
        });

        // Suporte Mobile Touch (Swipe)
        let touchStartX = 0;
        let touchEndX = 0;

        track.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});

        track.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchEndX < touchStartX - 40) {
                if (isTransitioning) return;
                isTransitioning = true;
                currentIndex++;
                updatePosition();
                updateIndicators();
            }
            if (touchEndX > touchStartX + 40) {
                if (isTransitioning) return;
                isTransitioning = true;
                currentIndex--;
                updatePosition();
                updateIndicators();
            }
        }, {passive: true});

        window.addEventListener('resize', () => {
            updatePosition(false);
        });
        
        // Posição Inicial
        updatePosition(false);

        // Auto-play
        setInterval(() => {
            if (!isTransitioning) {
                isTransitioning = true;
                currentIndex++;
                updatePosition();
                updateIndicators();
            }
        }, 5000);
    }

    /* --- SCROLL TO TOP --- */
    const scrollTopBtn = document.getElementById('scrollTop');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.classList.add('active');
        } else {
            scrollTopBtn.classList.remove('active');
        }
    });

    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

