# Blueprint Técnico: Arquitetura de CMS Dinâmico (Core)

Este documento descreve a infraestrutura técnica necessária para implementar e replicar o ecossistema de gerenciamento administrativo (CMS) desenvolvido, focado em alta performance e total autonomia via painel.

---

## 1. Tecnologias Requeridas
- **Backend**: PHP 7.4+ (Procedural).
- **Banco de Dados**: MySQL/MariaDB (Acesso via PDO).
- **Frontend**: HTML5 Semântico, CSS3 (Vanilla + Media Queries), JavaScript Vanilla.
- **Bibliotecas**: Phosphor Icons (Ícones do Sistema), SortableJS (Lógica de Drag & Drop).

---

## 2. Arquitetura de Diretórios (Padrão)
```text
/
├── admin/                  # Núcleo Administrativo
│   ├── css/                # Estilos do Painel
│   ├── includes/           # Componentes de Layout Reutilizáveis
│   ├── uploads/            # Armazenamento físico de ativos gerenciáveis
│   ├── conteudo.php        # Gerenciamento de Entidades de Texto
│   ├── galeria.php         # Gerenciador de Ativos (Geral e Galeria)
│   ├── seo.php             # Dashboard de Metadados e Scripts
│   └── api_update_order.php # Endpoint de Persistência de Ordenação
├── css/                    # Estilos da Aplicação
├── js/                     # Inteligência de Frontend
├── includes/               # Motor da Plataforma
│   ├── config.php          # Conexão de Dados e Variáveis Globais
│   └── functions.php       # Core de Funções (Conteúdo, Auth e Upload)
└── index.php               # Aplicação Principal Dinâmica
```

---

## 3. Estrutura de Dados (Database Schema)

### `admins`
Controle de acessos administrativos.
- `id`: INT (PK AI)
- `username`: VARCHAR (50)
- `password`: VARCHAR (255) (Hash seguro)

### `site_content`
Mapeamento de chaves de texto dinâmicas.
- `id`: INT (PK AI)
- `section`: VARCHAR (50) (Ex: 'global', 'home')
- `content_key`: VARCHAR (100) (Identificador Único)
- `content_value`: TEXT

### `site_settings`
Configurações de sistema e metadados de SEO.
- `name`: VARCHAR (100) (PK)
- `value`: TEXT

### `gallery_images`
Gerenciamento de coleções visuais ordenáveis.
- `id`: INT (PK AI)
- `image_path`: VARCHAR (255) (Caminho absoluto ou relativo)
- `caption`: TEXT (Metadado de Alt Text)
- `sort_order`: INT (Índice de prioridade de exibição)

---

## 4. Lógica de Integração (Functions Core)

### Recuperação de Conteúdo
```php
function getContent($section, $key, $default = "") {
    // Busca na site_content por SECTION e KEY.
    // Retorna content_value ou $default em caso de ausência.
}
```

### Recuperação de Configurações Técnicas
```php
function getSetting($name, $default = "") {
    // Busca na site_settings por NAME.
}
```

### Processador de Upload
```php
function uploadImage($file) {
    // Higieniza nomes de arquivos.
    // Move para o diretório de uploads do admin.
    // Retorna o path para persistência no banco.
}
```

---

## 5. Especificações do Painel Administrativo

### Gerenciador de Ativos e Ordenação
- **Listagem**: As imagens devem ser recuperadas utilizando `ORDER BY sort_order ASC`.
- **Drag & Drop**: Implementação via `SortableJS` no container de itens.
- **Persistência**: Envio do array de novos índices via AJAX (JSON) para processamento em massa no backend.

### Central de Metadados e SEO
- Dashboard para gestão de Tags de Redes Sociais (`og:image`, `og:title`).
- Campos de inserção de Scripts Externos (Tracking) em seções específicas da aplicação.

### Design Responsivo (Mobile Admin)
- **Menu Off-canvas**: Ocultação lateral do painel de navegação em telas < 768px.
- **Layer de Overlay**: Fechamento de menu via clique em área neutra.
- **Grids Fluidas**: Forçamento de colunas únicas em formulários complexos para garantir usabilidade em dispositivos móveis.

---

## 6. Fluxo de Instalação para Novos Projetos
1. Setup do banco de dados (4 tabelas core).
2. Configuração do `includes/config.php` com credenciais de ambiente.
3. Definição das `keys` de conteúdo no código da aplicação utilizando a função `getContent()`.
4. Mapeamento de ativos do sistema (Logos/Banners) no gerenciador de galeria.
