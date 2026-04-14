# 🚀 Guia de Replicação Studio 4x Master Template

Siga estes passos para criar um novo site institucional em minutos utilizando a estrutura Resimetal.

## 1. Clonagem e Banco de Dados
1. Copie toda a pasta deste projeto para o novo repositório do cliente.
2. No PHPMyAdmin (ou painel Hostinger), crie um novo banco de dados.
3. Importe o arquivo `database/master_schema.sql`.
   - *Nota: O usuário padrão é `admin` e a senha inicial é `admin123`.*

## 2. Configurações Iniciais
Abra o arquivo `includes/config.php` e altere os dados na seção **1. DADOS DO CLIENTE**:
- `SITE_NAME`: Nome completo para o SEO.
- `CLIENT_NAME`: Nome curto para o painel.
- `PRIMARY_COLOR`: Defina a cor principal da nova marca.
- `SECONDARY_COLOR`: Defina a cor de destaque.
- Atualize as credenciais do Banco de Dados no item **2**.

## 3. Rebranding do Frontend
1. Substitua as imagens na pasta `assets/`:
   - `logotipo-resimetal...webp` -> Novo logo.
   - `favicon.ico` -> Novo favicon.
2. O arquivo central de cores é o `assets/css/theme-vars.css` (será criado a seguir).

## 4. Alimentação de Dados
1. Acesse `seusite.com.br/admin`.
2. Vá em **Usuários** e mude a senha do `admin` imediatamente.
3. Vá em **Conteúdo do Site** e preencha todas as novas seções.

---
*Studio 4x - Eficiência em Desenvolvimento*
