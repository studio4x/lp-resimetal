# Blueprint Técnico: Estrutura CMS Dinâmico Resimetal

Este documento serve como guia para replicar a arquitetura deste site em outros projetos, garantindo um painel administrativo robusto, 100% dinâmico e focado em SEO.

## 1. Tecnologias Core
- **Backend**: PHP 7.4+ (Procedural para simplicidade e performance).
- **Banco de Dados**: MySQL/MariaDB (PDO para segurança).
- **Frontend**: HTML5, CSS3, JavaScript Vanilla.
- **Bibliotecas**: Phosphor Icons (Ícones), SortableJS (Ordenação).

---

## 2. Arquitetura de Diretórios
```text
/
├── admin/                  # Painel Administrativo
│   ├── css/                # Estilos do Admin
│   ├── includes/           # Header/Footer do Admin (Layout)
│   ├── uploads/            # Pasta física das imagens (CHMOD 755/777)
│   ├── conteudo.php        # Gerenciador de textos (Site Content)
│   ├── galeria.php         # Gerenciador de fotos + Ativos do Sistema
│   ├── seo.php             # Central de SEO & Marketing
│   └── api_update_order.php # Endpoint AJAX para ordenação
├── css/                    # Estilos do Site
├── js/                     # Scripts do Site
├── includes/               # Lógica Principal do Sistema
│   ├── config.php          # Conexão PDO e Constantes (BASE_URL)
│   └── functions.php       # Funções de Auth, Conteúdo e Upload
└── index.php               # Front-end Dinâmico
```

---

## 3. Esquema do Banco de Dados (Schema)

### Tabela: `admins`
Armazena os usuários do painel.
- `id`: INT PK AI
- `username`: VARCHAR(50) UNIQUE
- `password`: VARCHAR(255) (Hash BCrypt)

### Tabela: `site_content`
Coração do dinamismo. Mapeia textos por seções.
- `id`: INT PK AI
- `section`: VARCHAR(50) (Ex: 'hero', 'quem_somos', 'global')
- `content_key`: VARCHAR(100) (Ex: 'title', 'whatsapp_number')
- `content_value`: TEXT

### Tabela: `site_settings`
Configurações globais e SEO (Usa par Nome-Valor).
- `name`: VARCHAR(100) PRIMARY KEY
- `value`: TEXT

### Tabela: `gallery_images`
Imagens do carrossel com suporte a ordenação.
- `id`: INT PK AI
- `image_path`: VARCHAR(255)
- `caption`: TEXT (Usado como Alt Text)
- `sort_order`: INT (Padrão: 0)

---

## 4. Lógicas Fundamentais (`functions.php`)

### Busca de Conteúdo Dinâmico
```php
function getContent($section, $key, $default = "") {
    // Busca na tabela site_content. 
    // Se não existir, retorna o $default (fallback) e cria o registro.
}
```

### Configurações de SEO
```php
function getSetting($name, $default = "") {
    // Busca na tabela site_settings.
}
```

### Processamento de Imagens
```php
function uploadImage($file) {
    // 1. Valida extensão (webp, jpg, png).
    // 2. Renomeia com time() + nome original.
    // 3. Move para admin/uploads/.
    // 4. Retorna o caminho relativo (admin/uploads/nome.webp).
}
```

---

## 5. Implementações Especiais

### Galeria (Drag & Drop)
1. Listar imagens ordenadas por `sort_order ASC`.
2. Usar `SortableJS` no container da lista.
3. No evento `onEnd`, capturar os IDs e enviar via `fetch()` para `admin/api_update_order.php`.

### Central de SEO
1. Integrar campos para `og:title`, `og_description` e `og_image_path`.
2. No `index.php`, concatenar o `BASE_URL` com o caminho da imagem para garantir que o WhatsApp carregue a miniatura.

### Cache Busting
Sempre chamar o CSS passando uma versão: `<link rel="stylesheet" href="css/style.css?v=2.8">`. Incrementar no `index.php` a cada alteração visual.

---

## 6. Checklist para Novos Projetos
- [ ] Criar as 4 tabelas no banco de dados.
- [ ] Configurar `includes/config.php` (DB_NAME, DB_HOST, DB_USER, DB_PASS).
- [ ] Copiar pasta `admin/` e `includes/`.
- [ ] Mapear seções do novo site e preencher fallbacks em cada `getContent()`.
- [ ] Registrar o primeiro usuário admin via script ou SQL seguro.
