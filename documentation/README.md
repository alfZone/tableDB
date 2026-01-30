# **Manual da Classe FPDF (Versão 1.86)**

## **Índice**

- Introdução
- Instanciação e Configuração Inicial
- Métodos Principais de Trabalho
- Manipulação de Texto e Fontes
- Cores e Linhas
- Imagens
- Links
- Configurações de Página
- Saída do Documento
- Métodos Protegidos (Internos)

## **Introdução**

FPDF é uma classe PHP que permite a geração de arquivos PDF de forma programática. Esta versão (1.86) oferece funcionalidades completas para criação de documentos PDF com suporte a texto, imagens, links, cores e muito mais.

Características principais:

- Geração de PDF sem dependências externas
- Suporte a UTF-8
- Suporte a imagens JPEG, PNG e GIF
- Compressão automática
- Links internos e externos
- Cabeçalhos e rodapés personalizáveis

## **Instanciação e Configuração Inicial**

### **Construtor**

php

\$pdf = new FPDF(\$orientation, \$unit, \$size);

Parâmetros:

- \$orientation (string): Orientação da página
  - 'P' ou 'Portrait' - Retrato (padrão)
  - 'L' ou 'Landscape' - Paisagem
- \$unit (string): Unidade de medida
  - 'pt' - Pontos
  - 'mm' - Milímetros (padrão)
  - 'cm' - Centímetros
  - 'in' - Polegadas
- \$size (string/array): Tamanho da página
  - Valores pré-definidos: 'A3', 'A4' (padrão), 'A5', 'Letter', 'Legal'
  - Array com largura e altura: array(210, 297) para A4 em mm

Exemplo:

php

\$pdf = new FPDF('P', 'mm', 'A4');

\$pdf = new FPDF('L', 'in', 'Letter');

\$pdf = new FPDF('P', 'mm', array(150, 200));

### **Configuração de Margens**

php

_// Definir todas as margens_

\$pdf->SetMargins(\$left, \$top, \$right = null);

_// Definir margens individuais_

\$pdf->SetLeftMargin(\$margin);

\$pdf->SetTopMargin(\$margin);

\$pdf->SetRightMargin(\$margin);

## **Métodos Principais de Trabalho**

### **Adicionar Página**

php

\$pdf->AddPage(\$orientation, \$size, \$rotation);

- Cria uma nova página
- Parâmetros são opcionais e herdados do construtor se não especificados
- \$rotation: Rotação em graus (0, 90, 180, 270)

### **Cabeçalho e Rodapé**

php

_// Métodos a serem sobrescritos na classe estendida_

function Header() {

_// Código do cabeçalho_

}

function Footer() {

_// Código do rodapé_

}

### **Linha (Line)**

php

\$pdf->Line(\$x1, \$y1, \$x2, \$y2);

- Desenha uma linha entre dois pontos
- Coordenadas em unidades definidas no construtor

### **Retângulo (Rect)**

php

\$pdf->Rect(\$x, \$y, \$width, \$height, \$style);

- Desenha um retângulo
- \$style: '' (borda), 'F' (preenchido), 'FD' ou 'DF' (ambos)

### **Posicionamento**

php

_// Obter posição atual_

\$x = \$pdf->GetX();

\$y = \$pdf->GetY();

_// Definir posição_

\$pdf->SetX(\$x);

\$pdf->SetY(\$y, \$resetX = true);

\$pdf->SetXY(\$x, \$y);

## **Manipulação de Texto e Fontes**

### **Configurar Fonte**

php

\$pdf->SetFont(\$family, \$style, \$size);

- \$family: Nome da família (ex: 'Arial', 'Times', 'Courier')
- \$style: Estilo ('', 'B', 'I', 'BI', 'U')
- \$size: Tamanho em pontos

Fontes padrão suportadas:

- Courier
- Helvetica
- Times
- Symbol
- ZapfDingbats

### **Adicionar Fontes Personalizadas**

php

\$pdf->AddFont(\$family, \$style, \$file, \$dir);

- Suporta TrueType, OpenType e Type1

### **Escrever Texto**

#### **Cell (Célula)**

php

\$pdf->Cell(\$width, \$height, \$text, \$border, \$ln, \$align, \$fill, \$link);

- \$border: 0 (sem), 1 (com), 'L', 'T', 'R', 'B' ou combinações
- \$ln: 0 (direita), 1 (próxima linha), 2 (abaixo)
- \$align: 'L' (esquerda), 'C' (centro), 'R' (direita)
- \$fill: true/false para preencher fundo

#### **MultiCell (Célula Multilinha)**

php

\$pdf->MultiCell(\$width, \$height, \$text, \$border, \$align, \$fill);

- Quebra texto automaticamente

#### **Write (Escrita Contínua)**

php

\$pdf->Write(\$height, \$text, \$link);

- Texto em modo fluxo com quebra automática

#### **Text (Texto Simples)**

php

\$pdf->Text(\$x, \$y, \$text);

- Texto em posição específica

### **Medidas de Texto**

php

\$width = \$pdf->GetStringWidth(\$text);

### **Quebra de Linha**

php

\$pdf->Ln(\$height = null);

- Se \$height for null, usa altura da última célula

## **Cores e Linhas**

### **Cores de Desenho (Contornos)**

php

_// RGB_

\$pdf->SetDrawColor(\$red, \$green, \$blue);

_// Tons de cinza_

\$pdf->SetDrawColor(\$gray);

### **Cores de Preenchimento**

php

_// RGB_

\$pdf->SetFillColor(\$red, \$green, \$blue);

_// Tons de cinza_

\$pdf->SetFillColor(\$gray);

### **Cores de Texto**

php

_// RGB_

\$pdf->SetTextColor(\$red, \$green, \$blue);

_// Tons de cinza_

\$pdf->SetTextColor(\$gray);

### **Largura da Linha**

php

\$pdf->SetLineWidth(\$width);

## **Imagens**

### **Adicionar Imagem**

php

\$pdf->Image(\$file, \$x, \$y, \$width, \$height, \$type, \$link);

- \$x, \$y: Posição (null para usar posição atual)
- \$width, \$height: Dimensões (0 para automático)
- \$type: Formato ('JPG', 'PNG', 'GIF') - detectado automaticamente
- Formatos suportados: JPEG, PNG, GIF

### **Dimensões da Página**

php

\$width = \$pdf->GetPageWidth();

\$height = \$pdf->GetPageHeight();

## **Links**

### **Links Internos**

php

_// Criar um link_

\$link = \$pdf->AddLink();

_// Definir destino_

\$pdf->SetLink(\$link, \$y = 0, \$page = -1);

_// Criar área clicável_

\$pdf->Link(\$x, \$y, \$width, \$height, \$link);

### **Links em Texto/Imagens**

php

_// Em Cell_

\$pdf->Cell(\$w, \$h, \$text, \$border, \$ln, \$align, \$fill, \$link);

_// Em Image_

\$pdf->Image(\$file, \$x, \$y, \$w, \$h, \$type, \$link);

## **Configurações de Página**

### **Quebra de Página Automática**

php

\$pdf->SetAutoPageBreak(\$auto, \$margin = 0);

- \$auto: true/false
- \$margin: Margem inferior para ativação

### **Modo de Exibição no Visualizador**

php

\$pdf->SetDisplayMode(\$zoom, \$layout);

- \$zoom: 'default', 'fullpage', 'fullwidth', 'real', ou fator de zoom
- \$layout: 'default', 'single', 'continuous', 'two'

### **Alias para Número Total de Páginas**

php

\$pdf->AliasNbPages(\$alias = '{nb}');

- Substitui \$alias pelo número total de páginas no texto

### **Metadados do Documento**

php

\$pdf->SetTitle(\$title, \$isUTF8 = false);

\$pdf->SetAuthor(\$author, \$isUTF8 = false);

\$pdf->SetSubject(\$subject, \$isUTF8 = false);

\$pdf->SetKeywords(\$keywords, \$isUTF8 = false);

\$pdf->SetCreator(\$creator, \$isUTF8 = false);

### **Compressão**

php

\$pdf->SetCompression(\$compress);

- Ativa/desativa compressão do PDF (requer zlib)

## **Saída do Documento**

### **Output**

php

\$pdf->Output(\$dest, \$name, \$isUTF8 = false);

Destinos (\$dest):

- 'I': Enviar para saída padrão (inline no browser)
- 'D': Forçar download
- 'F': Salvar em arquivo local
- 'S': Retornar como string

Exemplos:

php

_// Exibir no browser_

\$pdf->Output('I', 'documento.pdf');

_// Download_

\$pdf->Output('D', 'meu_pdf.pdf');

_// Salvar no servidor_

\$pdf->Output('F', '/caminho/arquivo.pdf');

_// Obter como string_

\$content = \$pdf->Output('S');

### **Close**

php

\$pdf->Close();

- Finaliza o documento (chamado automaticamente pelo Output())

## **Métodos Protegidos (Internos)**

### **Métodos Principais de Renderização**

php

protected function \_out(\$s); _// Adiciona linha à página atual_

protected function \_put(\$s); _// Adiciona linha ao buffer do documento_

protected function \_newobj(\$n); _// Inicia novo objeto PDF_

protected function \_putstream(\$data); _// Adiciona stream_

### **Processamento de Fontes**

php

protected function \_loadfont(\$path); _// Carrega definição de fonte_

protected function \_putfonts(); _// Processa todas as fontes_

protected function \_tounicodecmap(\$uv); _// Cria mapa ToUnicode_

### **Processamento de Imagens**

php

protected function \_parsejpg(\$file); _// Processa JPEG_

protected function \_parsepng(\$file); _// Processa PNG_

protected function \_parsegif(\$file); _// Processa GIF (via conversão PNG)_

### **Codificação UTF**

php

protected function \_UTF8encode(\$s); _// Converte ISO-8859-1 para UTF-8_

protected function \_UTF8toUTF16(\$s); _// Converte UTF-8 para UTF-16BE_

protected function \_escape(\$s); _// Escapa caracteres especiais_

protected function \_textstring(\$s); _// Formata string de texto_

### **Geração da Estrutura PDF**

php

protected function \_beginpage(); _// Inicia nova página_

protected function \_endpage(); _// Finaliza página_

protected function \_putpages(); _// Processa todas as páginas_

protected function \_putresources(); _// Adiciona recursos_

protected function \_putinfo(); _// Adiciona metadados_

protected function \_putcatalog(); _// Adiciona catálogo_

protected function \_enddoc(); _// Finaliza documento_

## **Exemplo Completo de Uso**

php

<?php

require_once('fpdf.php');

class MeuPDF extends FPDF {

function Header() {

\$this->SetFont('Arial', 'B', 15);

\$this->Cell(0, 10, 'Título do Documento', 0, 1, 'C');

\$this->Ln(10);

}

function Footer() {

\$this->SetY(-15);

\$this->SetFont('Arial', 'I', 8);

\$this->Cell(0, 10, 'Página ' . \$this->PageNo() . '/{nb}', 0, 0, 'C');

}

}

_// Criar documento_

\$pdf = new MeuPDF();

\$pdf->AliasNbPages();

\$pdf->AddPage();

_// Adicionar conteúdo_

\$pdf->SetFont('Arial', '', 12);

\$pdf->Cell(0, 10, 'Olá, Mundo!', 0, 1);

\$pdf->Ln();

_// Tabela simples_

\$pdf->SetFillColor(200, 220, 255);

\$pdf->Cell(40, 10, 'Coluna 1', 1, 0, 'C', true);

\$pdf->Cell(40, 10, 'Coluna 2', 1, 1, 'C', true);

\$pdf->Cell(40, 10, 'Dado 1', 1);

\$pdf->Cell(40, 10, 'Dado 2', 1, 1);

_// Imagem_

\$pdf->Image('logo.png', 10, 100, 30);

_// Saída_

\$pdf->Output('I', 'documento.pdf');

?>

## **Considerações Finais**

- Performance: A compressão reduz significativamente o tamanho do arquivo
- UTF-8: Use \$isUTF8 = true para textos em UTF-8
- Herança: Estenda a classe para personalizar Header() e Footer()
- Estado: A classe mantém estado interno, siga a ordem correta das operações
- Erros: Use try-catch para capturar exceções lançadas pelo método Error()

Constantes importantes:

- FPDF::VERSION: Versão da classe
- FPDF_FONTPATH: Define caminho personalizado para fontes

Este manual cobre os métodos públicos e protegidos mais importantes da classe FPDF 1.86. Para casos específicos, consulte o código fonte diretamente.
