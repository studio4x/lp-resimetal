# Especificação Técnica: Landing Page Modelo Premium

Este documento serve como especificação total para o projeto **Metal Duro Sucata**, detalhando a estrutura, design e funcionalidades para servir de modelo em futuros desenvolvimentos.

## 1. Stack Tecnológica
- **HTML5**: Uso de tags semânticas (`header`, `main`, `section`, `footer`).
- **CSS3 (Vanilla)**: Sistema de design baseado em variáveis CSS (CSS Variables) para fácil manutenção de cores e tipografia.
- **JavaScript (Vanilla)**: Lógica customizada sem dependências de frameworks externos para garantir velocidade e leveza.
- **Ícones**: [Phosphor Icons](https://phosphoricons.com/) via CDN.
- **Tipografia**: [Google Fonts](https://fonts.google.com/) (Fontes: *Outfit* para títulos e *Inter* para corpo de texto).

## 2. Sistema de Design (Design Tokens)
O projeto utiliza um paleta premium voltada para o setor industrial/logístico:
- **Cores Principais**:
  - `--bg-main`: `#111827` (Grafite Escuro / Carvão)
  - `--accent-gold`: `#C89B3C` (Dourado Tungstênio - utilizado para CTAs principais)
  - `--accent-blue`: `#0F4C5C` (Azul Petróleo - utilizado para destaques e confiança)
- **Efeitos**:
  - **Glassmorphism**: Cabeçalho fixo com `backdrop-filter: blur(10px)`.
  - **Sombras**: Camadas de `box-shadow` suaves para profundidade.
  - **Bordas**: Raio de borda padrão de `8px` (`--border-radius`).

## 3. Estrutura de Conteúdo (Seções)
1.  **Top Bar (Mobile)**: Barra fixa no topo para celulares com link direto de WhatsApp.
2.  **Header**: Logotipo em formato "stacked type" feito em CSS puro e menu de navegação ancorado.
3.  **Hero Section**: Título forte (H1), Proposta Única de Valor e botões de ação (Venda via WA).
4.  **Benefits (Diferenciais)**: Grid de 4 colunas com ícones e descrições curtas.
5.  **How It Works (Timeline)**: Fluxo passo-a-passo numerado.
6.  **Materials Selection**: Grid de cards com imagens e CTAs individuais.
7.  **Logistic Detail (Correios/Retirada)**: Seção explicativa com detalhes de envio e coleta.
8.  **Gallery (Carrossel)**: Carrossel de 3 colunas com rolagem infinita automática.
9.  **Testimonials**: Grid de depoimentos reais com sistema de estrelas.
10. **FAQ**: Acordeão de perguntas frequentes para SEO.
11. **Footer**: Mapa do site, contatos e créditos.

## 4. Funcionalidades de JavaScript
- **Infinite Carousel**: Lógica de clonagem de slides para loop contínuo sem "pulos".
- **Intersection Observer**: Animações de "reveal" (os elementos surgem suavemente conforme o scroll do usuário).
- **Sticky Header**: O cabeçalho reduz de tamanho ao rolar a página para otimizar espaço.
- **Dynamic Year**: Atualização automática do ano de copyright no rodapé.

## 5. SEO & Web Vitals
- **Semântica**: Tags H1 a H6 em hierarquia lógica.
- **Metadados**: Tags OpenGraph (Facebook/WhatsApp), Twitter Cards e Canonical Tags.
- **Dados Estruturados**: JSON-LD para `WebSite` e `Organization` (ajuda a aparecer o nome da marca na busca do Google).
- **Sitemap & Robots**: Arquivos `sitemap.xml` e `robots.txt` configurados na raiz.
- **IA Ready**: Arquivo `llms.txt` para otimizar a leitura por ChatGPT e outros modelos de IA.

## 6. Lógica de Arquivos
- `/index.html`: Arquivo principal.
- `/css/style.css`: Contém reset, variáveis, layouts globais e responsividade.
- `/js/script.js`: Controla carrossel, menu mobile e animações de scroll.
- `/assets/`:
  - `favicon.png`: Ícone do site.
  - `carrossel-imagens/`: Imagens renomeadas com palavras-chave (ex: `sucata-de-metal-duro-X.jpg`).

## 7. Melhores Práticas Implementadas
- **Mobile-First**: Design pensado primeiro para celulares e adaptado para desktop.
- **Logotipo CSS**: Evita o uso de imagens para o logo, garantindo nitidez máxima em qualquer resolução.
- **Acessibilidade**: Uso de atributos `aria-label` em botões interativos e contrastes de cores adequados.
