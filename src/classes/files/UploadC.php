<?php
namespace classes\files;

/**
 * Esta classe trata o upload de ficheiros
 *
 * @author Ant�nio Lira Fernandes
 * @version 2.0
 * @updated 09-Jan-2025 18:10:03
 */

	// REQUIRES
	
// MISSION: manager the upload of files to a specific directory. It should support both POST and PUT methods for uploading, and also provide a way to list the uploaded 
//          files with their details (name, size, modification date). Additionally, it should have a method to generate HTML for displaying file previews based on their 
//          type (e.g., images, documents, etc.). The class should be flexible enough to allow configuration of the target upload directory and handle errors gracefully.

// METHODS
// __construct($targetDir) - Class constructer that initializes the target directory for uploads. If the directory does not exist, it creates it. $targetDir is the full 
//                           path on file system (e.g. /home/USER/public_html/FOLDER_FOR_UPLOAD
// respondeProtocolo() - Method to handle incoming HTTP requests and route them to the appropriate upload or listing method based on the request method (POST, PUT, GET).
// uploadUsingPOST() - Method to handle file uploads sent via POST requests. It processes the uploaded file from the $_FILES superglobal, moves it to the target directory, 
//                     and returns a JSON response indicating success or failure.
// uploadUsingPUT() - Method to handle file uploads sent via PUT requests. It reads the raw input stream, saves the file to the target directory, and returns a JSON response.
// listFiles() - Method to list all files in the target directory. It returns a JSON array with details of each file (name, size, modification date).
// formatarTamanho($bytes) - Helper method to format file sizes into human-readable strings (e.g., KB, MB).
// JSUploadFileUsingPOST($EndPoint) - Method that generates JavaScript code for uploading a file using a POST request to the specified endpoint.
// JSUploadFileUsingPUT($EndPoint) - Method that generates JavaScript code for uploading a file using a PUT request to the specified endpoint.
// getFilePreviewHTML($url, $options) - Method that generates HTML for displaying a file preview based on its type. It checks the file extension and returns appropriate HTML 
//                                      (e.g., an <img> tag for images or an icon for documents) with options for customization.
// generateImageHTML($url, $filename, $options) - Helper method to generate HTML for displaying an image file, including support for lazy loading and optional download links.
// generateFileIconHTML($url, $extension, $filename, $options) - Helper method to generate HTML with an SVG icon for non-image files, based on their extension. It includes a 
//                                                               mapping of common file types to specific icons.
// getDownloadAttributes($url, $filename, $options) - Helper method to generate attributes for download links, including the 'download' attribute if specified in options.
// getFilePreviewCSS() - Method that returns CSS styles for the file preview elements, allowing for consistent styling across different file types.
// toString() - Method to return a string representation of the class, typically for debugging purposes.




class UploadC {

    /**
	 * Caminho para o diretório de upload
	 */
	var $targetDir = "/home/turma12r/public_html/alf/caderneta/images/cromos/";

    /**
     * Class constructer that initializes the target directory for uploads. If the directory does not exist, it creates it. 
     * @param string $targetDir s the full path on file system (e.g. /home/USER/public_html/FOLDER_FOR_UPLOAD
     */
    function __construct($targetDir = "/home/turma12r/public_html/alf/caderneta/images/cromos/") {
        $this->targetDir = $targetDir;
        //echo "Diretório de upload: " . $this->targetDir . "<br>";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // cria a pasta se não existir
        }
        //echo  $this->targetDir;
    }
  
//#######################################################################
/*
 * Método para escolher a resposta a um protocolo
 */    
function respondeProtocolo(){
    //echo  $this->targetDir;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $this->uploadUsingPOST();
            break;
        case 'PUT':
            $this->uploadUsingPUT();
            break;
        case 'GET':
            if (isset($_REQUEST['a'])){
                if ($_REQUEST['a']=="lf") {
                    $this->listFiles();
                } 
            }           
            break;
        default:
            http_response_code(405);
            echo json_encode(["error" => "Método não permitido."]);
    }
}
//#######################################################################
/*
 * Método para fazer upload de um arquivo via PUT
 */ 
function uploadUsingPOST(){
    //echo $this->$targetDir;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['file']['tmp_name'];
            $fileName = basename($_FILES['file']['name']);
            $targetFile = $this->targetDir . $fileName;
            //echo $targetFile;
            if (move_uploaded_file($fileTmp, $targetFile)) {
                //echo json_encode(["message" => "Upload feito com sucesso!"],["ficheiro" => $targetFile]);
                echo json_encode(["message" => "Upload feito com sucesso!","ficheiro" => $targetFile,"pasta" => $this->targetDir]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Erro ao mover o arquivo."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Nenhum arquivo enviado ou erro no upload."]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido."]);
    }
}
    
//#######################################################################   
/*
 * Método para fazer upload de um arquivo via PUT
 */
function uploadUsingPUT(){
    //echo "aaa: " . $this->targetDir;
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    
        $headers = getallheaders();
        $fileName = isset($headers['X-Filename']) ? basename($headers['X-Filename']) : 'arquivo_recebido.bin';

        // segurança básica
        $fileName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileName);

        $targetFile = $this->targetDir . $fileName;
        //echo $targetFile;
        $putData = fopen("php://input", "rb");
        $outFile = fopen($targetFile, "wb");
    
        if ($putData && $outFile) {
            while ($chunk = fread($putData, 1024)) {
                fwrite($outFile, $chunk);
            }
            fclose($putData);
            fclose($outFile);
            //echo json_encode(["message" => "Upload via PUT concluído com sucesso!"],["ficheiro" => $targetFile]);
            echo json_encode(["message" => "Upload feito com sucesso!","ficheiro" => $targetFile,"pasta" => $this->targetDir]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Erro ao processar o arquivo."]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido."]);
    }   
}

//#######################################################################
/*
 * Método para listar arquivos em um diretório
 */
function listFiles() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $lista = [];

        if (!is_dir($this->targetDir)) {
            echo json_encode(["error" => "Diretório não encontrado."]);
            exit;
        }
        $files = scandir($this->targetDir);
        
        // Remove os "." e ".."
        $files = array_diff($files, array('.', '..'));

        //print_r($files);
        
        foreach ($files as $ficheiro) {
            $caminhoCompleto =  $this->targetDir .  $ficheiro;
            //echo $caminhoCompleto;
            if (is_file($caminhoCompleto)) {
                //echo "é ficheiro<br>";
                $lista[] = [
                    'nome'     => $ficheiro,
                    'tamanho'  => $this->formatarTamanho(filesize($caminhoCompleto)),
                    'modificado' => date("d/m/Y H:i", filemtime($caminhoCompleto)),
                ];
            }
        }
        //print_r($lista);
        echo json_encode($lista);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido."]);
    }
}

//#######################################################################
/*
 * Método para formatar o tamanho do arquivo
 */
function formatarTamanho($bytes) {
    $unidades = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($unidades) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $unidades[$i];
}

//#######################################################################
/*
 * JavaScript para fazer upload de um arquivo usando POST
 */
function JSUploadFileUsingPOST($EndPoint) {
    ?>
        const uploadFile = async () => {
            const fileInput = document.querySelector("#fileInput");
            const file = fileInput.files[0];

            if (!file) {
                console.error("Nenhum arquivo selecionado!");
                return;
            }

            const formData = new FormData();
            formData.append("file", file);

            try {
                const response = await fetch("<?=$EndPoint?>", {
                    method: "POST",
                    body: formData,
                });

                if (!response.ok) throw new Error("Erro ao fazer upload");

                const result = await response.json();
                console.log("Sucesso:", result);
            } catch (error) {
                console.error("Erro:", error);
            }
        };
    <?php
}

//#######################################################################
/*
 * JavaScript para fazer upload de um arquivo usando PUT
 */
function JSUploadFileUsingPUT($EndPoint) {
    ?>
    const uploadFile = async () => {
        alert("Fazendo upload do arquivo...");
        const fileInput = document.querySelector("#fileInput");
        const file = fileInput.files[0];

        if (!file) {
            console.error("Nenhum arquivo selecionado!");
            return;
        }

        try {
            const response = await fetch("<?=$EndPoint?>", {
                method: "PUT",
                headers: {
                    "X-Filename": file.name,
                    "Content-Type": file.type, // Define o tipo de conteúdo
                },
                body: file, // Envia o arquivo diretamente
            });

            if (!response.ok) throw new Error("Erro ao fazer upload");

            const result = await response.json();
            console.log("Sucesso:", result);
        } catch (error) {
            console.error("Erro:", error);
        }
    };
    <?php
}


//#######################################################################
/**
 * Gera HTML para exibir uma imagem ou ícone representativo baseado no tipo de ficheiro
 * 
 * @param string $url O URL do ficheiro a ser verificado
 * @param array $options Opções adicionais para personalizar a exibição
 * @return string HTML formatado para exibição
 */
function getFilePreviewHTML($url, $options = []) {
    
    // Opções padrão
    $defaults = [
        'width' => '200',
        'height' => '200',
        'class' => 'file-preview',
        'fallback_text' => 'Pré-visualização indisponível',
        'icon_size' => '64',
        'show_filename' => true,
        'lazy_load' => true,
        'thumbnail' => false,
        'download_text' => 'Descarregar ficheiro',
        'show_download_button' => true,
        'target' => '_blank', // '_blank' para nova aba, '_self' para mesma janela
        'download_attribute' => true // Adicionar atributo download ao link
    ];
    
    $options = array_merge($defaults, $options);
    
    // Extrair extensão do ficheiro
    $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    $filename = basename(parse_url($url, PHP_URL_PATH));
    
    // Mapeamento de extensões de imagem
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'ico', 'tiff', 'tif', 'psd', 'ai', 'eps'];
    
    // Verificar se é uma imagem
    if (in_array($extension, $imageExtensions)) {
        return $this->generateImageHTML($url, $filename, $options);
    }
    
    // Para não-imagens, gerar ícone apropriado com link de download
    return $this->generateFileIconHTML($url, $extension, $filename, $options);
}

/**
 * Gera HTML para exibir uma imagem real
 */
function generateImageHTML($url, $filename, $options) {
    
    // Verificar se é SVG
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $isSvg = ($extension === 'svg');
    
    // Atributos da imagem
    $attributes = [
        'src' => $url,
        'alt' => $filename,
        'class' => $options['class'] . ' image-preview',
        'loading' => $options['lazy_load'] ? 'lazy' : 'eager'
    ];
    
    // Para SVG, podemos permitir dimensões diferentes
    if (!$isSvg) {
        $attributes['width'] = $options['width'];
        $attributes['height'] = $options['height'];
    }
    
    // Se for thumbnail via URL
    if ($options['thumbnail'] && function_exists('generateThumbnailUrl')) {
        $attributes['src'] = generateThumbnailUrl($url, $options['width'], $options['height']);
    }
    
    // Construir atributos HTML
    $attrString = '';
    foreach ($attributes as $key => $value) {
        $attrString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
    }
    
    // Container para a imagem
    $html = '<div class="' . htmlspecialchars($options['class'] . '-container image-container') . '">';
    
    // Imagem pode ser clicável para download também
    if ($options['show_download_button']) {
        $html .= '<a href="' . htmlspecialchars($url) . '" ' . $this->getDownloadAttributes($url, $filename, $options) . ' class="image-link">';
        $html .= '<img' . $attrString . '>';
        $html .= '</a>';
    } else {
        $html .= '<img' . $attrString . '>';
    }
    
    if ($options['show_filename']) {
        $html .= '<div class="' . htmlspecialchars($options['class'] . '-caption image-caption') . '">';
        $html .= '<span class="filename">' . htmlspecialchars($filename) . '</span>';
        $html .= '<span class="dimensions">' . $options['width'] . 'x' . $options['height'] . '</span>';
        
        // Botão de download explícito
        if ($options['show_download_button']) {
            $html .= ' <a href="' . htmlspecialchars($url) . '" ' . $this->getDownloadAttributes($url, $filename, $options) . ' class="download-button" title="' . htmlspecialchars($options['download_text']) . '">⬇️</a>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Gera HTML com ícone SVG para tipos de ficheiro não-imagem
 */
function generateFileIconHTML($url, $extension, $filename, $options) {
    
    // Array de ícones SVG para diferentes tipos de ficheiro
    $fileIcons = [
        // Documentos
        'pdf' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .8-.7 1.5-1.5 1.5H8v1.5h2v2.5h1.5v-8H10v3h1.5v.5zm4 4.5c-.8 0-1.5-.7-1.5-1.5V8c0-.8.7-1.5 1.5-1.5H16c.8 0 1.5.7 1.5 1.5v4.5c0 .8-.7 1.5-1.5 1.5h-1.5zm0-6H16v4.5h-1.5V8zM3 6H1v14c0 1.1.9 2 2 2h14v-2H3V6z"/></svg>',
        
        'doc' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'docx' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        
        // Planilhas
        'xls' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'xlsx' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        
        // Apresentações
        'ppt' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'pptx' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        
        // Arquivos compactados
        'zip' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 10h-2v-2h2v2zm0-4h-2v-2h2v2zm-4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm-4 4H6v-2h2v2zm0-4H6v-2h2v2z"/></svg>',
        'rar' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 10h-2v-2h2v2zm0-4h-2v-2h2v2zm-4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm-4 4H6v-2h2v2zm0-4H6v-2h2v2z"/></svg>',
        '7z' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 10h-2v-2h2v2zm0-4h-2v-2h2v2zm-4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm-4 4H6v-2h2v2zm0-4H6v-2h2v2z"/></svg>',
        
        // Áudio
        'mp3' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>',
        'wav' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>',
        'ogg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>',
        'flac' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>',
        
        // Vídeo
        'mp4' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M18 3v2h-2V3H8v2H6V3H4v18h2v-2h2v2h8v-2h2v2h2V3h-2zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/></svg>',
        'avi' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M18 3v2h-2V3H8v2H6V3H4v18h2v-2h2v2h8v-2h2v2h2V3h-2zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/></svg>',
        'mkv' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M18 3v2h-2V3H8v2H6V3H4v18h2v-2h2v2h8v-2h2v2h2V3h-2zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/></svg>',
        'mov' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M18 3v2h-2V3H8v2H6V3H4v18h2v-2h2v2h8v-2h2v2h2V3h-2zM8 17H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V7h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2z"/></svg>',
        
        // Texto
        'txt' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'rtf' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'md' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        
        // Código
        'php' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'html' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'css' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'js' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'json' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        'xml' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0 4h8v2H8v-2zm0-8h8v2H8v-2z"/></svg>',
        
        // Executáveis
        'exe' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 8v2h-2v-2h2zm0-4v2h-2v-2h2zm-4 4v2h-2v-2h2zm0-4v2h-2v-2h2zm-4 4H6v-2h2v2zm0-4H6v-2h2v2z"/></svg>',
        'msi' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 8v2h-2v-2h2zm0-4v2h-2v-2h2zm-4 4v2h-2v-2h2zm0-4v2h-2v-2h2zm-4 4H6v-2h2v2zm0-4H6v-2h2v2z"/></svg>',
        'apk' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4 8v2h-2v-2h2zm0-4v2h-2v-2h2zm-4 4v2h-2v-2h2zm0-4v2h-2v-2h2zm-4 4H6v-2h2v2zm0-4H6v-2h2v2z"/></svg>',
        
        // Base de dados
        'sql' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 6h-6v2h6v2h-6v2h-2v-2H8v-2h6v-2H8V8h6v2h6v2z"/></svg>',
        'db' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M20 6h-8l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 6h-6v2h6v2h-6v2h-2v-2H8v-2h6v-2H8V8h6v2h6v2z"/></svg>'
    ];
    
    // Cores para diferentes tipos de ficheiro
    $colors = [
        'pdf' => '#FF0000',
        'doc' => '#2B5797',
        'docx' => '#2B5797',
        'xls' => '#217346',
        'xlsx' => '#217346',
        'ppt' => '#D24726',
        'pptx' => '#D24726',
        'zip' => '#FFA500',
        'rar' => '#FFA500',
        '7z' => '#FFA500',
        'mp3' => '#1ED760',
        'wav' => '#1ED760',
        'ogg' => '#1ED760',
        'flac' => '#1ED760',
        'mp4' => '#FF69B4',
        'avi' => '#FF69B4',
        'mkv' => '#FF69B4',
        'mov' => '#FF69B4',
        'txt' => '#808080',
        'rtf' => '#808080',
        'md' => '#808080',
        'php' => '#4F5B93',
        'html' => '#E44D26',
        'css' => '#264DE4',
        'js' => '#F7DF1E',
        'json' => '#F7DF1E',
        'xml' => '#F7DF1E',
        'exe' => '#0078D7',
        'msi' => '#0078D7',
        'apk' => '#0078D7',
        'sql' => '#FF8C00',
        'db' => '#FF8C00'
    ];
    
    // Cor padrão
    $defaultColor = '#808080';
    $color = isset($colors[$extension]) ? $colors[$extension] : $defaultColor;
    
    // Ícone padrão se a extensão não estiver mapeada
    if (!isset($fileIcons[$extension])) {
        $fileIcons['default'] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%s" width="%s" height="%s"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 18H6V4h7v5h5v11z"/></svg>';
        $extension = 'default';
    }
    
    // Construir o HTML do ícone
    $iconHTML = sprintf(
        $fileIcons[$extension],
        urlencode($color),
        htmlspecialchars($options['icon_size']),
        htmlspecialchars($options['icon_size'])
    );
    
    // Construir o HTML completo com link
    $html = '<div class="' . htmlspecialchars($options['class'] . '-container file-icon-container') . '">';
    
    // Link em volta do ícone
    $html .= '<a href="' . htmlspecialchars($url) . '" ' . $this->getDownloadAttributes($url, $filename, $options) . ' class="file-icon-link">';
    $html .= '<div class="' . htmlspecialchars($options['class'] . ' file-icon-preview') . '">';
    $html .= $iconHTML;
    $html .= '</div>';
    $html .= '</a>';
    
    if ($options['show_filename']) {
        $html .= '<div class="' . htmlspecialchars($options['class'] . '-caption file-caption') . '">';
        $html .= '<span class="filename">' . htmlspecialchars($filename) . '</span>';
        $html .= '<span class="filetype">' . strtoupper($extension) . '</span>';
        
        // Botão de download explícito
        if ($options['show_download_button']) {
            $html .= ' <a href="' . htmlspecialchars($url) . '" ' . $this->getDownloadAttributes($url, $filename, $options) . ' class="download-button" title="' . htmlspecialchars($options['download_text']) . '">⬇️</a>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Gera os atributos para o link de download
 */
function getDownloadAttributes($url, $filename, $options) {
    $attributes = [];
    
    // Atributo target
    if ($options['target']) {
        $attributes[] = 'target="' . htmlspecialchars($options['target']) . '"';
    }
    
    // Atributo download (força o download em vez de abrir)
    if ($options['download_attribute']) {
        // Podemos especificar um nome diferente para o ficheiro descarregado
        $downloadName = $filename; // Ou podemos modificar se necessário
        $attributes[] = 'download="' . htmlspecialchars($downloadName) . '"';
    }
    
    // Rel para segurança quando target="_blank"
    if ($options['target'] === '_blank') {
        $attributes[] = 'rel="noopener noreferrer"';
    }
    
    return implode(' ', $attributes);
}

// CSS básico para estilização
function getFilePreviewCSS() {
    return '
    <style>
        .file-preview-container {
            display: inline-block;
            margin: 10px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        
        .image-container {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            background: #f9f9f9;
            transition: transform 0.2s;
        }
        
        .image-container:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .image-preview {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: 3px;
        }
        
        .image-link {
            text-decoration: none;
            display: block;
        }
        
        .image-caption, .file-caption {
            margin-top: 8px;
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .file-icon-container {
            display: inline-block;
            text-align: center;
        }
        
        .file-icon-link {
            text-decoration: none;
            display: inline-block;
        }
        
        .file-icon-preview {
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            display: inline-block;
            transition: all 0.2s;
        }
        
        .file-icon-link:hover .file-icon-preview {
            background: #e8e8e8;
            border-color: #ccc;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .file-icon-preview svg {
            display: block;
            margin: 0 auto;
        }
        
        .filename {
            display: inline-block;
            font-weight: bold;
            color: #333;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .filetype, .dimensions {
            font-size: 11px;
            color: #999;
            display: inline-block;
        }
        
        .dimensions {
            color: #4CAF50;
        }
        
        .download-button {
            display: inline-block;
            text-decoration: none;
            color: #666;
            font-size: 14px;
            padding: 2px 5px;
            border-radius: 3px;
            transition: all 0.2s;
        }
        
        .download-button:hover {
            background: #4CAF50;
            color: white;
            transform: scale(1.1);
        }
        
        .file-icon-link .download-button {
            margin-left: 5px;
        }
    </style>
    ';
}



//#######################################################################

	/**
	 * descreve o objecto
	 */
	function toString(){
      $txt="";
      
      
      return $txt;
	}

}

//####################################################################
// Samples of UserCode
//echo "teste";

//$a= new UploadC();
//$a->respondeProtocolo();
/*
// Include CSS
echo $a->getFilePreviewCSS();

// Image (aviable for download usinig a click on the image)
echo $a->getFilePreviewHTML('https://exemplo.com/foto.jpg', [
    'width' => '300',
    'height' => '200',
    'class' => 'minha-imagem'
]);

// PDF with link to download
echo $a->getFilePreviewHTML('https://exemplo.com/documento.pdf', [
    'icon_size' => '48',
    'download_text' => 'Descarregar PDF'
]);

// Vídeo for download
echo $a->getFilePreviewHTML('https://exemplo.com/video.mp4', [
    'show_filename' => true,
    'target' => '_self' // Descarregar na mesma janela
]);

// Desable download button
echo getFilePreviewHTML('https://exemplo.com/arquivo.zip', [
    'show_download_button' => false,
    'icon_size' => '64'
]);

// Desable download attribute (opens instead of downloading)
echo $a->getFilePreviewHTML('https://exemplo.com/relatorio.pdf', [
    'download_attribute' => false,
    'target' => '_blank'
]);
*/

?>
