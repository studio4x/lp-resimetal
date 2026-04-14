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
                <a href="https://wa.me/5511947132326" target="_blank" class="btn btn-primary btn-sm">Falar conosco</a>
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
                    </div>
                </div>
            </div>
        </section>

        <!-- Quem Somos -->
        <section id="quem-somos" class="section-padding bg-light">
            <div class="container text-center">
                <div class="badge">Institucional</div>
                <h2 class="section-title"><?php echo getContent('quem_somos', 'title', 'Quem Somos'); ?></h2>
                <p class="section-subtitle" style="max-width: 800px; margin: 0 auto 20px;"><?php echo getContent('quem_somos', 'subtitle', 'Há mais de 20 anos no mercado...'); ?></p>
                <div style="max-width: 800px; margin: 0 auto;">
                    <?php echo nl2br(htmlspecialchars(getContent('quem_somos', 'body', 'Somos especialistas na moagem...'))); ?>
                </div>
            </div>
        </section>

        <!-- Serviços -->
        <section id="servicos" class="materials section-padding">
            <div class="container">
                <div class="section-header reveal-up">
                    <h2 class="section-title"><?php echo getContent('servicos', 'title', 'Nossos Serviços'); ?></h2>
                    <p class="section-subtitle"><?php echo getContent('servicos', 'subtitle', 'Soluções completas...'); ?></p>
                </div>

                <div class="materials-grid">
                    <div class="benefit-card reveal-up">
                        <div class="icon-wrapper"><i class="ph ph-magnifying-glass"></i></div>
                        <h3>Avaliação Técnica</h3>
                        <p>Nossa equipe especializada define a melhor rota de processamento.</p>
                    </div>
                    <div class="benefit-card reveal-up">
                        <div class="icon-wrapper"><i class="ph ph-selection-all"></i></div>
                        <h3>Moagem e Peneiramento</h3>
                        <p>Processo de separação mecânica das terras e pingos.</p>
                    </div>
                    <div class="benefit-card reveal-up">
                        <div class="icon-wrapper"><i class="ph ph-fire"></i></div>
                        <h3>Fundição</h3>
                        <p>Transformação dos pingos em lingotes de zinco.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Galeria Dinâmica -->
        <section id="galeria" class="gallery section-padding bg-light">
            <div class="container">
                <div class="section-header reveal-up">
                    <div class="badge">Galeria</div>
                    <h2 class="section-title">Nossa Operação</h2>
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

        <!-- Contato -->
        <section id="contato" class="contact section-padding">
            <div class="container text-center">
                <h2 class="section-title">Entre em contato</h2>
                <p>Telefone: <strong>(11) 94713-2326</strong></p>
                <div style="margin-top: 30px;">
                    <a href="https://wa.me/5511947132326" class="btn btn-primary">Falar no WhatsApp</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container text-center" style="color: white; padding: 20px 0;">
            <p>&copy; <?php echo date('Y'); ?> Resimetal. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Scripts de Marketing (Rodapé) -->
    <?php echo getSetting('footer_scripts'); ?>

    <script src="js/script.js?v=2.3"></script>
</body>
</html>
