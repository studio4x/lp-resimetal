<?php
require_once('includes/config.php');

$data = [
    // GERAL
    ['global', 'whatsapp_text', '(11) 94713-2326'],
    ['global', 'whatsapp_link', 'https://wa.me/5511947132326'],
    ['global', 'email', 'comercial@resimetalbeneficiamentos.com.br'],
    ['global', 'header_cta', 'Falar conosco'],

    // HERO
    ['hero', 'badge', 'Beneficiamento de Resíduos de Zinco'],
    ['hero', 'title', 'Excelência em moagem e fundição de resíduos industriais'],
    ['hero', 'subtitle', 'Atuamos na moagem e beneficiamento de terras e borras de zinco provenientes de galvanização a fogo, transformando resíduos em recursos de alto valor.'],
    ['hero', 'btn_primary', 'Solicitar Orçamento'],
    ['hero', 'btn_secondary', 'Conheça nossos serviços'],
    ['hero', 'feat1', '+ de 20 anos de mercado'],
    ['hero', 'feat2', 'Redução de desperdícios'],
    ['hero', 'feat3', 'Valorização de resíduos'],

    // QUEM SOMOS
    ['quem_somos', 'badge', 'Institucional'],
    ['quem_somos', 'title', 'Quem Somos'],
    ['quem_somos', 'subtitle', 'Há mais de 20 anos no mercado, a Resimetal é referência no beneficiamento de zinco.'],
    ['quem_somos', 'body', "Somos especialistas na moagem, peneiramento e fundição de resíduos de zinco, como terras e borras geradas da galvanização a fogo. Nossa trajetória é marcada pela eficiência, transparência e pela parceria duradoura com nossos clientes."],

    // SERVIÇOS
    ['servicos', 'title', 'Nossos Serviços'],
    ['servicos', 'subtitle', 'Soluções completas para o reaproveitamento de materiais gerados na indústria.'],
    ['servicos', 'c1_title', 'Avaliação Técnica'],
    ['servicos', 'c1_text', 'Nossa equipe especializada, após avaliação e testes, define a melhor rota de processamento para cada material.'],
    ['servicos', 'c2_title', 'Moagem e Peneiramento'],
    ['servicos', 'c2_text', 'Nessa etapa, realizamos o processo de separação mecânica das terras e pingos por meio dos nossos moinhos e peneiras.'],
    ['servicos', 'c3_title', 'Fundição'],
    ['servicos', 'c3_text', 'Em seguida, realizamos a fundição desses pingos, transformando-os em lingotes de zinco, produto pronto para as mais diversas aplicações no mercado.'],

    // IMPORTÂNCIA
    ['importancia', 'badge', 'Estratégico'],
    ['importancia', 'title', 'A importância do beneficiamento desses materiais'],
    ['importancia', 'subtitle', 'A moagem e a fundição de materiais têm como objetivo adequar o produto à concentração e ao formato ideal, atendendo às exigências técnicas do mercado global.'],
    ['importancia', 'f1_title', 'Redução de desperdícios na sua empresa'],
    ['importancia', 'f1_text', 'Todo esse processo é fundamental para reduzir desperdícios, valorizar o seu resíduo e oferecer um preço muito mais competitivo. Além disso, permite a diversificação de aplicações no setor, gerando novas e melhores oportunidades de negócio.'],
    ['importancia', 'f2_title', 'Valorização de Mercado'],
    ['importancia', 'f2_text', 'Materiais antes descartados passam a ter alto valor na agricultura e outros setores.'],
    ['importancia', 'f3_title', 'Indispensável na Agricultura'],
    ['importancia', 'f3_text', 'O produto beneficiado é essencial para a fabricação de fertilizantes e insumos.'],

    // SUSTENTABILIDADE
    ['sustentabilidade', 'badge', 'Meio Ambiente'],
    ['sustentabilidade', 'title', 'Sustentabilidade e Compromisso Ambiental'],
    ['sustentabilidade', 'lead', 'Nosso processo segue todas as normas vigentes, com total compromisso ambiental e sustentabilidade.'],
    ['sustentabilidade', 'body', "A Resimetal tem como pilar fundamental a preservação do meio ambiente através da reciclagem consciente. Transformamos resíduos industriais em novos recursos, promovendo a economia circular e reduzindo drasticamente o impacto ambiental na região de Mogi das Cruzes e em toda a cadeia produtiva."],
    ['sustentabilidade', 'pilar1', 'Economia Circular'],
    ['sustentabilidade', 'pilar2', 'Normas Ambientais'],

    // GALERIA
    ['galeria', 'badge', 'Galeria'],
    ['galeria', 'title', 'Nossa Operação'],
    ['galeria', 'subtitle', 'Confira fotos reais do nosso processo de beneficiamento e dos materiais processados.'],

    // FAQ
    ['faq', 'title', 'Dúvidas Frequentes'],
    ['faq', 'p1_q', 'Quais tipos de resíduos vocês processam?'],
    ['faq', 'p1_a', 'Processamos principalmente terras e borras de zinco provenientes de galvanização.'],
    ['faq', 'p2_q', 'Onde a Resimetal está localizada?'],
    ['faq', 'p2_a', 'Estamos em Mogi das Cruzes - SP, no bairro Taboão. Estrada Eng. Abilio Gondim Pereira, 480.'],
    ['faq', 'p3_q', 'Como os lingotes são utilizados?'],
    ['faq', 'p3_a', 'Os lingotes de zinco tem diversas aplicações entre elas: Retorna para os banhos na galvanização a fogo ou para produção de óxidos de zinco.'],

    // CONTATO/FOOTER
    ['contato', 'title', 'Entre em contato'],
    ['contato', 'subtitle', 'Estamos prontos para atender as necessidades da sua empresa.'],
    ['contato', 'address_text', "Estrada Eng. Abilio Gondim Pereira, 480\nTaboão - Mogi das Cruzes - SP | 08771-111"],
    ['contato', 'btn_text', 'Enviar Mensagem'],
    ['contato', 'footer_brand_text', 'Referência em processos de moagem, peneiramento e fundição de resíduos de zinco há mais de 20 anos.'],
    ['contato', 'copyright', '&copy; ' . date('Y') . ' Resimetal. Todos os direitos reservados.'],
];

try {
    $stmt = $conn->prepare("INSERT INTO site_content (section, content_key, content_value) 
                            VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE content_value = ?");
                            
    $count = 0;
    foreach ($data as $item) {
        $stmt->execute([$item[0], $item[1], $item[2], $item[2]]);
        $count++;
    }
    
    echo "<h1>Sucesso!</h1>";
    echo "<p>$count campos foram populados no banco de dados.</p>";
    echo "<p><a href='admin/conteudo.php'>Ir para o Painel</a></p>";
    
} catch (PDOException $e) {
    echo "Erro ao popular banco: " . $e->getMessage();
}
?>
