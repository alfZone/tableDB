# **Documentação da Classe TableBD**

## **Visão Geral**

A classe TableBD é um ORM (Object-Relational Mapping) simplificado para gerenciar tabelas de banco de dados. Permite realizar operações CRUD (Create, Read, Update, Delete), importação de dados via CSV e geração automática de interfaces HTML para gestão dos dados.

## **Índice**

- Introdução
- Instalação e Requisitos
- Métodos Principais
- Configuração de Campos
- Permissões e Autenticação
- Templates e Interface
- Exemplos de Uso
- Referência Completa de Métodos

## **Introdução**

### **Propósito**

Criar uma forma simples de gerenciar tabelas de banco de dados com configurações mínimas, gerando automaticamente interfaces para listagem, inserção, edição e exclusão de registros.

### **Características Principais**

- ✅ CRUD completo (Create, Read, Update, Delete)
- ✅ Importação de CSV
- ✅ Interface HTML automática
- ✅ Suporte a múltiplos tipos de campos
- ✅ Permissões de usuário
- ✅ Templates customizáveis
- ✅ Upload de arquivos
- ✅ Campos calculados
- ✅ Paginação e ordenação

## **Instalação e Requisitos**

### **Requisitos**

- PHP 7.0+
- MySQL/MariaDB
- Classe Database.php (para conexão com banco)
- Biblioteca simple_html_dom (incluída)
- Classes auxiliares: UploadC, Log

### **Estrutura de Diretórios Recomendada**

text

projeto/

├── classes/

│ ├── db/

│ │ ├── TableBD.php

│ │ └── Database.php

│ ├── simplehtmldom/

│ │ └── simple_html_dom.php

│ └── files/

│ └── UploadC.php

└── templates/


## **Métodos Principais**

### **1\. Inicialização**

php

use classes\\db\\TableBD;

\$tabela = new TableBD();

\$tabela->prepareTable("nome_da_tabela");

### **2\. Configuração Básica**

php

_// Definir título_

\$tabela->setTitle("Lista de Usuários");

_// Configurar permissões_

\$tabela->setAutentication("a"); _// a = todas as permissões_

_// Definir critérios de consulta_

\$tabela->setCriterion("ativo = 1");

_// Definir ordenação_

\$tabela->setOrder("nome ASC");

_// Habilitar modo debug_

\$tabela->setDebugShow(1);

### **3\. Exibição da Interface**

php

_// Exibir interface completa_

\$tabela->showHTML();

_// Ou apenas os dados em JSON_

\$tabela->prepareJsonLinhas();

## **Configuração de Campos**

### **Tipos de Campos Suportados**

- int - Números inteiros
- var - Textos curtos (VARCHAR)
- tex - Textos longos (TEXT)
- dat - Datas
- tim - Timestamps
- lst - Listas/Combobox
- img - Imagens
- fil - Upload de arquivos
- pas - Passwords
- calc - Campos calculados

### **Exemplos de Configuração**

#### **Campos Visíveis na Listagem**

php

_// Mostrar apenas campos específicos na listagem_

\$tabela->setFieldsAtive("id,nome,email,telefone", "list");

_// Ou mostrar todos_

\$tabela->setAllFieldAtive("list", 1);

_// Esconder um campo específico_

\$tabela->setFieldAtive("senha", "list", 0);

#### **Campo do Tipo Lista**

php

_// De uma query SQL_

\$tabela->setFieldList("categoria_id", "1",

"SELECT id, nome FROM categorias ORDER BY nome");

_// De valores estáticos_

\$tabela->setFieldList("status", "2",

"1=>Ativo,2=>Inativo,3=>Suspenso");

_// Misturando SQL com valores estáticos_

\$tabela->setFieldList("tipo", "3",

"SELECT id, descricao FROM tipos|0=>Não Definido");

#### **Campo do Tipo Imagem**

php

\$tabela->setImageField("foto", "/uploads/fotos/", "80%", "default.jpg");

#### **Campo do Tipo Upload**

php

\$tabela->setFieldUpload("anexo", "/uploads/documentos/");

#### **Campo do Tipo Password**

php

\$tabela->setFieldPass("senha", "1", "md5");

_// Modos: 0-off, 1-repetir entrada, 2-mostrar senha_

_// Cifras: "md5", "sha1", "base64"_

#### **Campo Calculado**

php

\$tabela->setCalculatedField("idade",

"YEAR(CURDATE()) - YEAR(data_nascimento)");

#### **Valor Padrão**

php

\$tabela->setDefaultValue("data_cadastro", "CURDATE()");

\$tabela->setDefaultValue("ativo", "1");

#### **Ação JavaScript**

php

\$tabela->setJSAction("nome", "onclick='mostrarDetalhes(this.value)'");

## **Permissões e Autenticação**

### **Níveis de Permissão**

php

_// a - Todas as permissões (view, create, edit, delete)_

\$tabela->setAutentication("a");

_// u - Apenas atualização (edit)_

\$tabela->setAutentication("u");

_// e - Apenas edição_

\$tabela->setAutentication("e");

_// n - Apenas criação de novos registros_

\$tabela->setAutentication("n");

_// r - Apenas leitura (view)_

\$tabela->setAutentication("r");

### **Exclusão Múltipla**

php

_// Habilitar checkboxes para exclusão múltipla_

\$tabela->setMultiple(true);

## **Templates e Interface**

### **Template Padrão**

php

_// Definir template customizado_

\$tabela->setTemplate("../templates/meu_template.php");

### **Estrutura do Template**

O template deve conter os seguintes elementos HTML:

html

_&lt;!-- Título --&gt;_

&lt;h3 class="tbTitle"&gt;&lt;/h3&gt;

_&lt;!-- Tabela de listagem --&gt;_

&lt;table&gt;

&lt;thead class="titleTable"&gt;&lt;/thead&gt;

&lt;tbody id="bodyTable"&gt;&lt;/tbody&gt;

&lt;/table&gt;

_&lt;!-- Formulário de edição/inserção --&gt;_

&lt;div id="frmIU"&gt;

&lt;div id="frmIOH3"&gt;&lt;/div&gt;

&lt;/div&gt;

_&lt;!-- Modal de confirmação de exclusão --&gt;_

&lt;input type="hidden" id="deleteKey"&gt;

_&lt;!-- Modal de importação CSV --&gt;_

&lt;div id="importLst"&gt;&lt;/div&gt;

_&lt;!-- Botões (opcional) --&gt;_

&lt;button id="bnew"&gt;Novo&lt;/button&gt;

&lt;button class="bedit"&gt;Editar&lt;/button&gt;

&lt;button class="bdel"&gt;Excluir&lt;/button&gt;

&lt;button id="bcsv"&gt;Importar CSV&lt;/button&gt;

&lt;button id="bdelm"&gt;Excluir Selecionados&lt;/button&gt;

### **Link para Página Externa**

php

_// Para abrir registro em página separada_

\$tabela->setLinkPage("detalhes.php", 0);

_// Style 0: detalhes.php?id=123_

_// Style 1: detalhes.php/123_

## **Exemplos de Uso**

### **Exemplo 1: Gestão Simples de Usuários**

php

<?php

require_once "classes/db/TableBD.php";

\$usuarios = new TableBD();

\$usuarios->prepareTable("usuarios");

_// Configurações básicas_

\$usuarios->setTitle("Gestão de Usuários");

\$usuarios->setAutentication("a");

_// Campos visíveis_

\$usuarios->setFieldsAtive("id,nome,email,telefone,data_cadastro", "list");

\$usuarios->setFieldsAtive("nome,email,telefone,senha", "new");

\$usuarios->setFieldsAtive("nome,email,telefone", "edit");

_// Configurar campo senha_

\$usuarios->setFieldPass("senha", "1", "md5");

_// Exibir interface_

\$usuarios->showHTML();

?>

### **Exemplo 2: Produtos com Categorias**

php

<?php

\$produtos = new TableBD();

\$produtos->prepareTable("produtos");

\$produtos->setTitle("Catálogo de Produtos");

\$produtos->setCriterion("ativo = 1");

_// Campo categoria como lista_

\$produtos->setFieldList("categoria_id", "1",

"SELECT id, nome FROM categorias WHERE ativo = 1 ORDER BY nome");

_// Campo preço com valor padrão_

\$produtos->setDefaultValue("preco", "0.00");

_// Upload de imagem_

\$produtos->setImageField("imagem", "/uploads/produtos/", "100px", "sem-imagem.jpg");

_// Exibir_

\$produtos->showHTML();

?>

### **Exemplo 3: API JSON**

php

<?php

_// Retornar dados em formato JSON_

header('Content-Type: application/json');

\$clientes = new TableBD();

\$clientes->prepareTable("clientes");

\$clientes->setCriterion("ativo = 1");

\$clientes->setLimites(50, 0); _// Paginação: 50 registros a partir do 0_

echo json_encode(\$clientes->prepareJsonLinhas());

?>

### **Exemplo 4: Importação de CSV**

php

<?php

\$produtos = new TableBD();

\$produtos->prepareTable("produtos");

_// Habilitar importação_

\$produtos->setFieldsAtive("codigo,nome,preco,estoque", "csv");

_// Processar upload_

if (\$\_SERVER\['REQUEST_METHOD'\] === 'POST' && isset(\$\_POST\['txtCSV'\])) {

\$produtos->importCSV();

header("Location: ?do=l");

exit;

}

\$produtos->showHTML();

?>

## **Referência Completa de Métodos**

### **Métodos de Configuração**

| Método | Descrição | Parâmetros |
| --- | --- | --- |
| prepareTable(\$table) | Prepara a tabela para uso | \$table: nome da tabela |
| setTitle(\$value) | Define título da página | \$value: texto do título |
| setAutentication(\$value) | Define permissões | \$value: "a","u","e","n","r" |
| setCriterion(\$criterion) | Define critério WHERE | \$criterion: string SQL |
| setOrder(\$order) | Define ordenação | \$order: string SQL |
| setDebugShow(\$value) | Ativa/desativa debug | \$value: 0 ou 1 |
| setTemplate(\$page) | Define template | \$page: caminho do arquivo |


### **Configuração de Campos**

| Método | Descrição | Parâmetros |
| --- | --- | --- |
| setFieldsAtive(\$fields, \$action) | Ativa campos para ação | \$fields: lista separada por vírgulas, \$action: "list","new","edit","csv" |
| setAllFieldAtive(\$action, \$value) | Ativa/desativa todos campos | \$action: tipo de ação, \$value: 0 ou 1 |
| setFieldList(\$field, \$mode, \$listOrSql, \$hideCode) | Configura campo lista | \$field: nome campo, \$mode: 1,2,3, \$listOrSql: dados, \$hideCode: 0 ou 1 |
| setImageField(\$field, \$path, \$percentage, \$defaultImage) | Configura campo imagem | \$field: nome campo, \$path: caminho, \$percentage: tamanho, \$defaultImage: imagem padrão |
| setFieldUpload(\$field, \$path) | Configura upload de arquivo | \$field: nome campo, \$path: diretório |
| setFieldPass(\$field, \$mode, \$cipher) | Configura campo password | \$field: nome campo, \$mode: 0,1,2, \$cipher: tipo cifra |
| setCalculatedField(\$nameField, \$sqlCalcFormula) | Adiciona campo calculado | \$nameField: nome, \$sqlCalcFormula: fórmula SQL |
| setDefaultValue(\$field, \$valor) | Define valor padrão | \$field: nome campo, \$valor: valor |
| setJSAction(\$field, \$action) | Adiciona ação JavaScript | \$field: nome campo, \$action: código JS |
| setLinkJS(\$field, \$jsCode) | Adiciona link JavaScript | \$field: nome campo, \$jsCode: código |

### **Métodos de Exibição**

| Método | Descrição | Parâmetros |
| --- | --- | --- |
| showHTML() | Exibe interface completa | \-  |
| makeAlist(\$withForms) | Gera lista HTML | \$withForms: incluir formulários |
| prepareEditNewForm(\$toDo, \$style) | Prepara formulário | \$toDo: "e" ou "a", \$style: "table" |
| prepareJsonLinhas() | Retorna dados JSON | \-  ||

### **Operações CRUD**

| Método | Descrição | Parâmetros |
| --- | --- | --- |
| querySQL(\$sql) | Executa query SELECT | \$sql: instrução SQL |
| executeSQL(\$sql) | Executa query genérica | \$sql: instrução SQL |
| importCSV() | Importa dados CSV | \-  |
| multiDelete() | Exclusão múltipla | \-  |
| getWhereData(\$keyValue) | Busca registro por chave | \$keyValue: valor da chave |

### **Métodos Auxiliares**

| Método | Descrição | Parâmetros |
| --- | --- | --- |
| getKey() | Retorna nome da chave primária | \-  |
| getParameter(\$para) | Obtém parâmetro da requisição | \$para: nome parâmetro |
| redirecciona(\$url) | Redireciona para URL | \$url: destino |
| setLimites(\$NumReg, \$LimInf) | Define paginação | \$NumReg: registros por página, \$LimInf: início |
| setLinkPage(\$page, \$style) | Define link para página externa | \$page: URL, \$style: 0 ou 1 |
| setMultiple(\$value) | Habilita exclusão múltipla | \$value: true/false |

## **Estrutura da Tabela de Campos**

Cada campo no array \$camposLista contém:

php

\[

'Field' => 'nome_campo', _// Nome do campo no banco_

'Type' => 'tipo_campo', _// Tipo (int, var, tex, etc.)_

'label' => 'Rótulo', _// Rótulo para exibição_

'Key' => 'PRI', _// Chave primária_

'Default' => 'valor', _// Valor padrão_

'Null' => 'YES/NO', _// Aceita nulos_

'ver' => 1, _// Visível na listagem_

'novo' => 1, _// Visível no formulário novo_

'editar' => 1, _// Visível no formulário editar_

'csv' => 1, _// Incluído na importação CSV_

'valor' => '', _// Valor atual_

'change' => 0 _// Flag de alteração_

\]

## **Tratamento de Erros**

### **Modo Debug**

php

\$tabela->setDebugShow(1); _// Mostra SQL gerado_

### **Logs**

A classe utiliza a classe Log para registrar operações (se disponível).

### **Validações**

- Campos marcados como Null = "NO" são obrigatórios
- Uploads são validados pelo UploadC
- Passwords são criptografadas antes do armazenamento

## **Considerações de Segurança**

### **Proteções Implementadas**

- SQL Injection: Uso de prepared statements via classe Database
- XSS: Escape de HTML nos campos de texto
- Uploads: Validação de tipos e tamanhos pelo UploadC
- Permissões: Controle por níveis de acesso
- Passwords: Criptografia antes do armazenamento

### **Recomendações**

- Sempre definir permissões apropriadas
- Validar uploads de arquivos
- Usar HTTPS em produção
- Implementar autenticação adicional se necessário

## **Roadmap e Melhorias Futuras**

### **Planejado para Versões Futuras**

- Suporte a relacionamentos entre tabelas
- Filtros avançados na listagem
- Exportação para Excel/PDF
- Validações personalizadas por campo
- Suporte a transactions
- Cache de consultas
- Internacionalização (i18n)
- API RESTful completa

### **Versão Atual: 14.8**

- Correções diversas de código
- Upload de arquivos para o servidor
- Exibição de arquivos com link para download
- Problemas de encoding resolvidos

## **Links Úteis**

- Repositório: <https://github.com/alfZone/tabledb>
- Wiki: <https://github.com/alfZone/tabledb/wiki>
- Autor: António Lira Fernandes
- Última atualização: 04-08-2025

## **Licença**

Classe de uso livre para projetos educacionais e comerciais. Consulte o repositório para detalhes de licenciamento.

Nota: Esta documentação refere-se à versão 14.8 da classe TableBD. Para informações atualizadas, consulte sempre o repositório oficial.
