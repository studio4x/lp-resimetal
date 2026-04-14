<?php
require_once('includes/config.php');
require_once('includes/functions.php');

// Registrar visita (Métricas)
logVisitor();

// Buscar Galeria
$stmt = $conn->query("SELECT * FROM gallery_images ORDER BY id DESC");
$gallery = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(getSetting('site_title', 'Resimetal | Moagem e Fundição de Resíduos de Zinco')); ?></title>
    <link rel="canonical" href="https://resimetalbeneficiamentos.com.br/" />
    <meta name="description" content="<?php echo htmlspecialchars(getSetting('site_description', 'Especialistas em moagem, peneiramento e fundição de resíduos de zinco há mais de 20 anos.')); ?>">
    <meta name="google-site-verification" content="HSfLrgBat8BteoEb4mX7tRDnE2SwyJ9XvcZi96ebkik" />
    
    <!-- SEO / Google Search -->
    <meta property="og:site_name" content="Resimetal">
    <meta property="og:title" content="Moagem e Fundição de Resíduos de Zinco">
    <meta property="og:url" content="https://resimetalbeneficiamentos.com.br/">
    <meta property="og:type" content="website">
    
    <!-- Scripts de Marketing (Cabeçalho) -->
    <?php echo getSetting('head_scripts'); ?>

    <!-- Fontes e Ícones -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <link rel="stylesheet" href="css/style.css?v=2.6">
    <style>
        .header .logo-img { height: 65px; width: auto; }
        @media (max-width: 768px) { .header .logo-img { height: 50px; } }
    </style>
</head>
<body>

    <!-- Header / Nav -->
    <header id="header" class="header">
        <div class="top-bar">
            <div class="container top-bar-link">
                <i class="ph-fill ph-whatsapp-logo"></i> (11) 94713-2326 | Entre em contato
            </div>
        </div>
        <div class="container header-container">
            <a href="#" class="logo">
                <img src="assets/logotipo-resimetal-transparente-otimizado.webp" alt="Logotipo Resimetal" class="logo-img">
            </a>
            
            <button class="mobile-menu-toggle" aria-label="Abrir menu">
                <i class="ph ph-list"></i>
            </button>

            <nav class="nav-menu">
                <ul>
                    <li><a href="#quem-somos" class="nav-link">Quem Somos</a></li>
                    <li><a href="#servicos" class="nav-link">Serviços</a></li>
                    <li><a href="#importancia" class="nav-link">Importância</a></li>
                    <li><a href="#sustentabilidade" class="nav-link">Sustentabilidade</a></li>
                    <li><a href="#galeria" class="nav-link">Galeria</a></li>
                    <li><a href="#contato" class="nav-link">Contato</a></li>
                </ul>
            </nav>
            <div class="header-cta">
                <a href="https://wa.me/5511947132326" target="_blank" class="btn btn-primary btn-sm">
                    <i class="ph-fill ph-whatsapp-logo"></i> Falar conosco
                </a>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="inicio" class="hero">
            <div class="container hero-container">
                <div class="hero-content reveal-up">
                    <div class="badge"><i class="ph ph-factory"></i> Beneficiamento de Resíduos</div>
                    <h1><?php echo getContent('hero', 'title', 'Excelência em moagem e fundição de resíduos industriais'); ?></h1>
                    <p><?php echo getContent('hero', 'subtitle', 'Atuamos na moagem e beneficiamento de terras e borras de zinco...'); ?></p>
                    <div class="hero-actions">
                        <a href="https://wa.me/5511947132326" target="_blank" class="btn btn-primary">Solicitar Orçamento</a>
                        <a href="#servicos" class="btn btn-outline">Conheça nossos serviços</a>
                    </div>

                    <div class="hero-features">
                        <div class="feature-item">
                            <i class="ph ph-shield-check"></i>
                            <span>+ de 20 anos de mercado</span>
                        </div>
                        <div class="feature-item">
                            <i class="ph ph-recycle"></i>
                            <span>Redução de desperdícios</span>
                        </div>
                        <div class="feature-item">
                            <i class="ph ph-trend-up"></i>
                            <span>Valorização de resíduos</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quem Somos -->
        <section id="quem-somos" class="section-padding bg-light">
            <div class="container text-center" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="badge">Institucional</div>
                <h2 class="section-title"><?php echo getContent('quem_somos', 'title', 'Quem Somos'); ?></h2>
                <p class="section-subtitle" style="margin: 0 auto 20px auto; max-width: 800px;"><?php echo getContent('quem_somos', 'subtitle', 'Há mais de 20 anos no mercado, a Resimetal é referência no beneficiamento de zinco.'); ?></p>
                <div style="max-width: 800px; margin: 0 auto;">
                    <p>Somos especialistas na moagem, peneiramento e fundição de resíduos de zinco, como terras e borras geradas da galvanização a fogo. Nossa trajetória é marcada pela eficiência, transparência e pela parceria duradoura com nossos clientes.</p>
                </div>
            </div>
        </section>

        <!-- Serviços -->
        <section id="servicos" class="materials section-padding">
            <div class="container">
                <div class="section-header reveal-up">
                    <h2 class="section-title"><?php echo getContent('servicos', 'title', 'Nossos Serviços'); ?></h2>
                    <p class="section-subtitle"><?php echo getContent('servicos', 'subtitle', 'Soluções completas para o reaproveitamento de materiais gerados na indústria.'); ?></p>
                </div>

                <div class="materials-grid">
                    <div class="benefit-card reveal-up">
                        <div class="icon-wrapper">
                            <i class="ph ph-magnifying-glass"></i>
                        </div>
                        <h3>Avaliação Técnica</h3>
                        <p>Nossa equipe especializada, após avaliação e testes, define a melhor rota de processamento para cada material.</p>
                    </div>
                    <div class="benefit-card reveal-up" style="--delay: 0.1s">
                        <div class="icon-wrapper">
                            <i class="ph ph-selection-all"></i>
                        </div>
                        <h3>Moagem e Peneiramento</h3>
                        <p>Nessa etapa, realizamos o processo de separação mecânica das terras e pingos por meio dos nossos moinhos e peneiras.</p>
                    </div>
                    <div class="benefit-card reveal-up" style="--delay: 0.2s">
                        <div class="icon-wrapper">
                            <i class="ph ph-fire"></i>
                        </div>
                        <h3>Fundição</h3>
                        <p>Em seguida, realizamos a fundição desses pingos, transformando-os em lingotes de zinco, produto pronto para as mais diversas aplicações no mercado.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Importância -->
        <section id="importancia" class="correios section-padding bg-dark light-text">
            <div class="container">
                <div class="section-header reveal-up" style="text-align: center; margin-bottom: 50px;">
                    <div class="badge">Estratégico</div>
                    <h2 class="section-title"><?php echo getContent('importancia', 'title', 'A importância do beneficiamento desses materiais'); ?></h2>
                    <p style="max-width: 750px; margin: 0 auto; font-size: 1.05rem;"><?php echo getContent('importancia', 'subtitle', 'A moagem e a fundição têm como objetivo adequar o produto...'); ?></p>
                </div>

                <div class="reveal-up" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; max-width: 1000px; margin: 0 auto;">
                    <div class="c-feature">
                        <i class="ph ph-checks"></i>
                        <div>
                            <h5>Redução de desperdícios</h5>
                            <span>Processo fundamental para valorizar o seu resíduo e oferecer preço competitivo.</span>
                        </div>
                    </div>
                    <div class="c-feature">
                        <i class="ph ph-currency-circle-dollar"></i>
                        <div>
                            <h5>Valorização de Mercado</h5>
                            <span>Materiais antes descartados passam a ter alto valor na agricultura e outros setores.</span>
                        </div>
                    </div>
                    <div class="c-feature">
                        <i class="ph ph-leaf"></i>
                        <div>
                            <h5>Indispensável na Agricultura</h5>
                            <span>Essencial para a fabricação de fertilizantes e insumos.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sustentabilidade -->
        <section id="sustentabilidade" class="section-padding">
            <div class="container text-center">
                <div class="badge">Meio Ambiente</div>
                <h2 class="section-title"><?php echo getContent('sustentabilidade', 'title', 'Sustentabilidade e Compromisso Comercial'); ?></h2>
                <p style="max-width: 750px; margin: 0 auto 20px; font-size: 1.05rem;"><?php echo getContent('sustentabilidade', 'body', 'Nosso processo segue todas as normas vigentes...'); ?></p>
                <div class="reveal-up" style="display: flex; justify-content: center; gap: 32px; flex-wrap: wrap; margin-top: 30px;">
                    <div class="feature-item"><i class="ph ph-recycle"></i> <span>Economia Circular</span></div>
                    <div class="feature-item"><i class="ph ph-shield-check"></i> <span>Normas Ambientais</span></div>
                </div>
            </div>
        </section>

        <!-- Galeria Dinâmica -->
        <section id="galeria" class="gallery section-padding bg-light">
            <div class="container">
                <div class="section-header reveal-up">
                    <div class="badge">Galeria</div>
                    <h2 class="section-title">Nossa Operação</h2>
                    <p class="section-subtitle">Confira fotos reais do nosso processo de beneficiamento e dos materiais processados.</p>
                </div>

                <div class="carousel-container reveal-up">
                    <div class="carousel-track">
                        <?php if(!empty($gallery)): ?>
                            <?php foreach($gallery as $img): ?>
                            <div class="carousel-slide">
                                <img src="<?php echo $img['image_path']; ?>" alt="<?php echo htmlspecialchars($img['caption']); ?>">
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align: center; width: 100%;">Nenhuma imagem cadastrada.</p>
                        <?php endif; ?>
                    </div>
                    <button class="carousel-button prev"><i class="ph ph-caret-left"></i></button>
                    <button class="carousel-button next"><i class="ph ph-caret-right"></i></button>
                    <div class="carousel-indicators"></div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="faq section-padding">
            <div class="container faq-container">
                <div class="faq-content reveal-up text-center">
                    <h2 class="section-title">Dúvidas Frequentes</h2>
                </div>
                <div class="faq-accordion reveal-up">
                    <div class="accordion-item">
                        <button class="accordion-header">Quais tipos de resíduos vocês processam? <i class="ph ph-caret-down"></i></button>
                        <div class="accordion-content"><p>Processamos principalmente terras e borras de zinco provenientes de galvanização.</p></div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-header">Onde a Resimetal está localizada? <i class="ph ph-caret-down"></i></button>
                        <div class="accordion-content"><p>Estamos em Mogi das Cruzes - SP, no bairro Taboão. Estrada Eng. Abilio Gondim Pereira, 480.</p></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contato -->
        <section id="contato" class="contact section-padding bg-light">
            <div class="container">
                <div class="section-header reveal-up">
                    <h2 class="section-title">Entre em contato</h2>
                    <p class="section-subtitle">Estamos prontos para atender as necessidades da sua empresa.</p>
                </div>
                
                <div class="row" style="justify-content: center; align-items: flex-start;">
                    <div class="col-text reveal-up">
                        <ul class="contact-info">
                        <li>
                            <i class="ph-fill ph-phone text-primary"></i>
                            <div>
                                <span>Telefone</span>
                                <strong><a href="tel:+5511947132326">(11) 94713-2326</a></strong>
                            </div>
                        </li>
                        <li>
                            <i class="ph-fill ph-envelope-simple text-primary"></i>
                            <div>
                                <span>E-mail comercial</span>
                                <strong><a href="mailto:comercial@resimetalbeneficiamentos.com.br">comercial@resimetalbeneficiamentos.com.br</a></strong>
                            </div>
                        </li>
                        <li>
                            <i class="ph-fill ph-map-pin text-primary"></i>
                            <div>
                                <span>Endereço</span>
                                <strong>
                                    <a href="https://share.google/7v2LdyNmZ40iY5HFu" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">
                                        Estrada Eng. Abilio Gondim Pereira, 480<br>Taboão - Mogi das Cruzes - SP | 08771-111
                                    </a>
                                </strong>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="col-form reveal-up" style="--delay: 0.2s">
                    <form id="contactForm" class="quote-form">
                        <div class="form-group">
                            <label>Nome / Empresa</label>
                            <input type="text" placeholder="Seu nome" required>
                        </div>
                        <div class="form-group">
                            <label>Assunto</label>
                            <select required>
                                <option value="" disabled selected>Selecione um assunto</option>
                                <option value="Beneficiamento">Beneficiamento de Resíduos</option>
                                <option value="Compra/Venda">Compra/Venda de Lingotes</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mensagem</label>
                            <textarea rows="3" placeholder="Como podemos ajudar?"></textarea>
                        </div>
                        <button class="btn btn-primary btn-block" type="submit">
                            <i class="ph-fill ph-paper-plane-right"></i> Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-brand">
                <img src="assets/logotipo-resimetal-transparente-otimizado.webp" alt="Resimetal" class="footer-logo-img">
                <p>Referência em processos de zinco há mais de 20 anos.</p>
            </div>
            <div class="footer-links">
                <h4>Navegação</h4>
                <ul>
                    <li><a href="#quem-somos">Quem Somos</a></li>
                    <li><a href="#servicos">Serviços</a></li>
                    <li><a href="#galeria">Galeria</a></li>
                    <li><a href="#contato">Contato</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Atendimento</h4>
                <ul>
                    <li><i class="ph-fill ph-phone"></i> (11) 94713-2326</li>
                    <li><i class="ph-fill ph-whatsapp-logo"></i> (11) 94713-2326</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container text-center">
                <p>&copy; <?php echo date('Y'); ?> Resimetal. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts de Marketing (Rodapé) -->
    <?php echo getSetting('footer_scripts'); ?>

    <script src="js/script.js?v=2.3"></script>
</body>
</html>
