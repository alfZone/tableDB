<?php
namespace classes\files;

/**
 * Esta classe trata o upload de ficheiros
 *
 * @author Ant�nio Lira Fernandes
 * @version 1.5
 * @updated 09-Jan-2025 18:10:03
 */

//Métodos:



class UploadC {

    /**
	 * Caminho para o diretório de upload
	 */
	var $targetDir = "/home/paa/public_html/uploads2/";

    /**
     * Construtor da classe
     * @param string $targetDir Caminho para o diretório de upload
     */
    function __construct($targetDir = "/home/paa/public_html/uploads2/") {
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
	 * descreve o objecto
	 */
	function toString(){
      $txt="";
      
      
      return $txt;
	}

}

//####################################################################
// exemplos de utiliza��o
//echo "teste";

//$a= new UploadC();
//$a->respondeProtocolo();

?>
