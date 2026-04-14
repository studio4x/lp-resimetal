<?php
include('includes/header.php');

$msg = "";

// Processar salvamento
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_content'])) {
    foreach ($_POST['content'] as $key => $value) {
        $section = $_POST['section'];
        $stmt = $conn->prepare("INSERT INTO site_content (section, content_key, content_value) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE content_value = ?");
        $stmt->execute([$section, $key, $value, $value]);
    }
    $msg = "Conteúdo de '" . ucwords(str_replace('_', ' ', $_POST['section'])) . "' atualizado!";
}

// Helper para buscar valor atual
function getVal($section, $key) {
    global $conn;
    $stmt = $conn->prepare("SELECT content_value FROM site_content WHERE section = ? AND content_key = ?");
    $stmt->execute([$section, $key]);
    $row = $stmt->fetch();
    return $row ? $row['content_value'] : "";
}
?>

<div class="header-page">
    <h1>Gerenciamento Total de Conteúdo</h1>
    <p>Edite cada texto do site seção por seção. Sem exceções.</p>
</div>

<?php if($msg): ?>
    <div style="background: #dcfce7; color: #15803d; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="ph ph-check-circle"></i> <?php echo $msg; ?>
    </div>
<?php endif; ?>

<!-- 1. GERAL & CONTATO -->
<div class="card-admin" style="margin-bottom: 30px;">
    <h2>1. Geral, Contatos e Header</h2>
    <form method="POST">
        <input type="hidden" name="section" value="global">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>WhatsApp Oficial (Número com DDD)</label>
                <input type="text" name="content[whatsapp_text]" value="<?php echo htmlspecialchars(getVal('global', 'whatsapp_text')); ?>" placeholder="Ex: (11) 94713-2326">
                <small style="color: #6B7280;">Este número será usado tanto para exibição quanto para o link do botão flutuante.</small>
            </div>
            <div class="form-group">
                <label>Mensagem Pré-definida (Botão Flutuante)</label>
                <input type="text" name="content[whatsapp_float_msg]" value="<?php echo htmlspecialchars(getVal('global', 'whatsapp_float_msg')); ?>" placeholder="Ex: Olá! Gostaria de um orçamento.">
            </div>
            <div class="form-group">
                <label>E-mail Comercial</label>
                <input type="text" name="content[email]" value="<?php echo htmlspecialchars(getVal('global', 'email')); ?>">
            </div>
            <div class="form-group">
                <label>Texto do Botão Header (CTA)</label>
                <input type="text" name="content[header_cta]" value="<?php echo htmlspecialchars(getVal('global', 'header_cta')); ?>">
            </div>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Configurações Globais</button>
    </form>
</div>

<!-- 2. HERO -->
<div class="card-admin" style="margin-bottom: 30px;">
    <h2>2. Seção Hero (Início)</h2>
    <form method="POST">
        <input type="hidden" name="section" value="hero">
        <div class="form-group">
            <label>Badge Superior (Pequeno selo)</label>
            <input type="text" name="content[badge]" value="<?php echo htmlspecialchars(getVal('hero', 'badge')); ?>">
        </div>
        <div class="form-group">
            <label>Título Principal (H1)</label>
            <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('hero', 'title')); ?>">
        </div>
        <div class="form-group">
            <label>Subtítulo</label>
            <textarea name="content[subtitle]" rows="2"><?php echo htmlspecialchars(getVal('hero', 'subtitle')); ?></textarea>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Botão Principal (Texto)</label>
                <input type="text" name="content[btn_primary]" value="<?php echo htmlspecialchars(getVal('hero', 'btn_primary')); ?>">
            </div>
            <div class="form-group">
                <label>Botão Secundário (Texto)</label>
                <input type="text" name="content[btn_secondary]" value="<?php echo htmlspecialchars(getVal('hero', 'btn_secondary')); ?>">
            </div>
        </div>
        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
        <h3>Diferenciais (Símbolos abaixo do CTA)</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Diferencial 1</label>
                <input type="text" name="content[feat1]" value="<?php echo htmlspecialchars(getVal('hero', 'feat1')); ?>">
            </div>
            <div class="form-group">
                <label>Diferencial 2</label>
                <input type="text" name="content[feat2]" value="<?php echo htmlspecialchars(getVal('hero', 'feat2')); ?>">
            </div>
            <div class="form-group">
                <label>Diferencial 3</label>
                <input type="text" name="content[feat3]" value="<?php echo htmlspecialchars(getVal('hero', 'feat3')); ?>">
            </div>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Seção Hero</button>
    </form>
</div>

<!-- 3. QUEM SOMOS -->
<div class="card-admin" style="margin-bottom: 30px;">
    <h2>3. Seção Quem Somos</h2>
    <form method="POST">
        <input type="hidden" name="section" value="quem_somos">
        <div class="form-group">
            <label>Badge Superior</label>
            <input type="text" name="content[badge]" value="<?php echo htmlspecialchars(getVal('quem_somos', 'badge')); ?>">
        </div>
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('quem_somos', 'title')); ?>">
        </div>
        <div class="form-group">
            <label>Subtítulo (Destaque)</label>
            <input type="text" name="content[subtitle]" value="<?php echo htmlspecialchars(getVal('quem_somos', 'subtitle')); ?>">
        </div>
        <div class="form-group">
            <label>Conteúdo Institucional</label>
            <textarea name="content[body]" rows="5"><?php echo htmlspecialchars(getVal('quem_somos', 'body')); ?></textarea>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Quem Somos</button>
    </form>
</div>

<!-- 4. SERVIÇOS -->
<div class="card-admin" style="margin-bottom: 30px;">
    <h2>4. Nossos Serviços (3 Cards)</h2>
    <form method="POST">
        <input type="hidden" name="section" value="servicos">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Título da Seção</label>
                <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('servicos', 'title')); ?>">
            </div>
            <div class="form-group">
                <label>Subtítulo da Seção</label>
                <input type="text" name="content[subtitle]" value="<?php echo htmlspecialchars(getVal('servicos', 'subtitle')); ?>">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <h4>Card 1</h4>
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="content[c1_title]" value="<?php echo htmlspecialchars(getVal('servicos', 'c1_title')); ?>">
                </div>
                <div class="form-group">
                    <label>Texto</label>
                    <textarea name="content[c1_text]" rows="3"><?php echo htmlspecialchars(getVal('servicos', 'c1_text')); ?></textarea>
                </div>
            </div>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <h4>Card 2</h4>
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="content[c2_title]" value="<?php echo htmlspecialchars(getVal('servicos', 'c2_title')); ?>">
                </div>
                <div class="form-group">
                    <label>Texto</label>
                    <textarea name="content[c2_text]" rows="3"><?php echo htmlspecialchars(getVal('servicos', 'c2_text')); ?></textarea>
                </div>
            </div>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <h4>Card 3</h4>
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="content[c3_title]" value="<?php echo htmlspecialchars(getVal('servicos', 'c3_title')); ?>">
                </div>
                <div class="form-group">
                    <label>Texto</label>
                    <textarea name="content[c3_text]" rows="3"><?php echo htmlspecialchars(getVal('servicos', 'c3_text')); ?></textarea>
                </div>
            </div>
        </div>
        <button type="submit" name="save_content" class="btn-save" style="margin-top: 20px;">Salvar Serviços</button>
    </form>
</div>

<!-- 5. IMPORTÂNCIA -->
<div class="card-admin" style="margin-bottom: 30px;">
    <h2>5. Seção Importância (Diferenciais Estratégicos)</h2>
    <form method="POST">
        <input type="hidden" name="section" value="importancia">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
             <div class="form-group">
                <label>Badge Superior</label>
                <input type="text" name="content[badge]" value="<?php echo htmlspecialchars(getVal('importancia', 'badge')); ?>">
            </div>
            <div class="form-group">
                <label>Título da Seção</label>
                <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('importancia', 'title')); ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Subtítulo da Seção</label>
            <textarea name="content[subtitle]" rows="2"><?php echo htmlspecialchars(getVal('importancia', 'subtitle')); ?></textarea>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <h4>Feature 1</h4>
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="content[f1_title]" value="<?php echo htmlspecialchars(getVal('importancia', 'f1_title')); ?>">
                </div>
                <div class="form-group">
                    <label>Texto</label>
                    <textarea name="content[f1_text]" rows="4"><?php echo htmlspecialchars(getVal('importancia', 'f1_text')); ?></textarea>
                </div>
            </div>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <h4>Feature 2</h4>
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="content[f2_title]" value="<?php echo htmlspecialchars(getVal('importancia', 'f2_title')); ?>">
                </div>
                <div class="form-group">
                    <label>Texto</label>
                    <textarea name="content[f2_text]" rows="4"><?php echo htmlspecialchars(getVal('importancia', 'f2_text')); ?></textarea>
                </div>
            </div>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <h4>Feature 3</h4>
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="content[f3_title]" value="<?php echo htmlspecialchars(getVal('importancia', 'f3_title')); ?>">
                </div>
                <div class="form-group">
                    <label>Texto</label>
                    <textarea name="content[f3_text]" rows="4"><?php echo htmlspecialchars(getVal('importancia', 'f3_text')); ?></textarea>
                </div>
            </div>
        </div>
        <button type="submit" name="save_content" class="btn-save" style="margin-top: 20px;">Salvar Importância</button>
    </form>
</div>

<!-- 6. SUSTENTABILIDADE -->
<div class="card-admin" style="margin-bottom: 30px;">
    <h2>6. Seção Sustentabilidade</h2>
    <form method="POST">
        <input type="hidden" name="section" value="sustentabilidade">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Badge Superior</label>
                <input type="text" name="content[badge]" value="<?php echo htmlspecialchars(getVal('sustentabilidade', 'badge')); ?>">
            </div>
            <div class="form-group">
                <label>Título da Seção</label>
                <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('sustentabilidade', 'title')); ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Texto de Chamada (Lead)</label>
            <input type="text" name="content[lead]" value="<?php echo htmlspecialchars(getVal('sustentabilidade', 'lead')); ?>">
        </div>
        <div class="form-group">
            <label>Corpo do Texto</label>
            <textarea name="content[body]" rows="4"><?php echo htmlspecialchars(getVal('sustentabilidade', 'body')); ?></textarea>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
             <div class="form-group">
                <label>Pilar 1 (Texto)</label>
                <input type="text" name="content[pilar1]" value="<?php echo htmlspecialchars(getVal('sustentabilidade', 'pilar1')); ?>">
            </div>
            <div class="form-group">
                <label>Pilar 2 (Texto)</label>
                <input type="text" name="content[pilar2]" value="<?php echo htmlspecialchars(getVal('sustentabilidade', 'pilar2')); ?>">
            </div>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Sustentabilidade</button>
    </form>
</div>

<!-- 7. GALERIA & FAQ -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
    <div class="card-admin">
        <h2>7. Seção Galeria</h2>
        <form method="POST">
            <input type="hidden" name="section" value="galeria">
            <div class="form-group">
                <label>Badge Superior</label>
                <input type="text" name="content[badge]" value="<?php echo htmlspecialchars(getVal('galeria', 'badge')); ?>">
            </div>
            <div class="form-group">
                <label>Título da Seção</label>
                <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('galeria', 'title')); ?>">
            </div>
            <div class="form-group">
                <label>Subtítulo</label>
                <input type="text" name="content[subtitle]" value="<?php echo htmlspecialchars(getVal('galeria', 'subtitle')); ?>">
            </div>
            <button type="submit" name="save_content" class="btn-save">Salvar Galeria</button>
        </form>
    </div>

    <div class="card-admin">
        <h2>8. Seção FAQ (Perguntas)</h2>
        <form method="POST">
            <input type="hidden" name="section" value="faq">
            <div class="form-group">
                <label>Título da Seção</label>
                <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('faq', 'title')); ?>">
            </div>
            <hr>
            <div class="form-group">
                <label>P1: Pergunta</label>
                <input type="text" name="content[p1_q]" value="<?php echo htmlspecialchars(getVal('faq', 'p1_q')); ?>">
            </div>
            <div class="form-group">
                <label>P1: Resposta</label>
                <textarea name="content[p1_a]" rows="2"><?php echo htmlspecialchars(getVal('faq', 'p1_a')); ?></textarea>
            </div>
            <hr>
            <div class="form-group">
                <label>P2: Pergunta</label>
                <input type="text" name="content[p2_q]" value="<?php echo htmlspecialchars(getVal('faq', 'p2_q')); ?>">
            </div>
            <div class="form-group">
                <label>P2: Resposta</label>
                <textarea name="content[p2_a]" rows="2"><?php echo htmlspecialchars(getVal('faq', 'p2_a')); ?></textarea>
            </div>
            <hr>
            <div class="form-group">
                <label>P3: Pergunta</label>
                <input type="text" name="content[p3_q]" value="<?php echo htmlspecialchars(getVal('faq', 'p3_q')); ?>">
            </div>
            <div class="form-group">
                <label>P3: Resposta</label>
                <textarea name="content[p3_a]" rows="2"><?php echo htmlspecialchars(getVal('faq', 'p3_a')); ?></textarea>
            </div>
            <button type="submit" name="save_content" class="btn-save">Salvar FAQ</button>
        </form>
    </div>
</div>

<!-- 8. FOOTER & CONTATO FINAL -->
<div class="card-admin" style="margin-bottom: 30px;">
    <h2>9. Rodapé & Contato Detalhado</h2>
    <form method="POST">
        <input type="hidden" name="section" value="contato">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Título da Seção Contato</label>
                <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('contato', 'title')); ?>">
            </div>
            <div class="form-group">
                <label>Subtítulo Seção Contato</label>
                <input type="text" name="content[subtitle]" value="<?php echo htmlspecialchars(getVal('contato', 'subtitle')); ?>">
            </div>
            <div class="form-group">
                <label>Endereço Completo (Texto)</label>
                <textarea name="content[address_text]" rows="2"><?php echo htmlspecialchars(getVal('contato', 'address_text')); ?></textarea>
            </div>
            <div class="form-group">
                <label>Texto do Botão de Envio</label>
                <input type="text" name="content[btn_text]" value="<?php echo htmlspecialchars(getVal('contato', 'btn_text')); ?>">
            </div>
            <div class="form-group">
                <label>Texto Institucional Rodapé (Marca)</label>
                <textarea name="content[footer_brand_text]" rows="2"><?php echo htmlspecialchars(getVal('contato', 'footer_brand_text')); ?></textarea>
            </div>
            <div class="form-group">
                <label>Copyright (Rodapé)</label>
                <input type="text" name="content[copyright]" value="<?php echo htmlspecialchars(getVal('contato', 'copyright')); ?>">
            </div>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Rodapé e Contatos</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
