## showHTML()

lê $action = getParameter('do')
```text
┌── lê $action = getParameter('do')
│
├─ case "" ou "l"   ➜  lista (makeAlist + includes)
│
├─ case "dm"        ➜  prepara modal de importação CSV
│
├─ case "di"        ➜  executa a importação CSV
│
├─ case "n"         ➜  mostra formulário “Novo”
│
├─ case "cn"        ➜  grava novo registo
│
├─ case "e"         ➜  mostra formulário “Editar”
│
├─ case "ce"        ➜  grava alterações
│
├─ case "d"         ➜  pede confirmação de apagar
│
└─ case "cd"        ➜  apaga definitivamente e redirecciona
```

## makeAlist()

```text
├── load_file(template)
├── fazListaCamposAccao("csv")
├── prepareTableRows
│ └── executeSQL ⋯
├── prepareEditNewForm()
├── translate / inject:
│ ├── #deleteKey
│ ├── #importLst
│ ├── .titleTable (table headers)
│ ├── #bodyTable (table rows)
│ ├── #frmIU (edit/new form)
│ └── .tbTitle (page title)
└── echo $html
```

## prepareEditNewForm()

Esta função gera dinamicamente um formulário HTML para **editar** ou **criar** um novo registo. Utiliza um template HTML, substitui conteúdos em zonas específicas e retorna a secção do formulário pronta a ser inserida noutro layout.

```text
prepareEditNewForm($toDo = "e")
├── load_file($this->template)           # carrega template HTML
├── define $accao                        # "editar" (e) ou "novo" (a)
├── calcular ncc                         # nº campos por coluna
├── loop $this->camposLista:
│   ├── verificar se campo deve aparecer (com base em $accao)
│   ├── gerar inputHTML($campo)
│   ├── se campo é chave primária:
│   │   ├── se não visível → hidden
│   │   └── se visível → alterar id para "editKey"
│   └── distribuir campos em 2 colunas
├── montar tabela HTML com campos
├── substituir conteúdo de:
│   └── #frmIOH3   → insere os inputs gerados
├── guardar e devolver:
│   └── #frmIU     → secção completa do formulário
```


## inputHTML($campo)

```text
├── substr($field['Type'], 0, 3)
├── simple_html_dom()
│   └── load_file($this->template)
├── switch (prefixo de $field['Type'])
│
│   ├── "lst" (select box)
│   │   ├── set id, name de <select>
│   │   ├── inject label em #selectL
│   │   ├── loop em $field['lista']
│   │   │   ├── detectar selected
│   │   │   ├── set <option selected> via .select#txt{Field} option
│   │   │   └── build options em #txt{Field}
│   │   └── $e->outertext from .select
│
│   ├── "dat" (date input)
│   │   ├── set id, name de <input>
│   │   ├── inject label em #dateL
│   │   └── $e->outertext from .date
│
│   ├── "int" / "var" / "dec" / "dou" / "tim" / "img"
│   │   ├── set id, name de <input>
│   │   ├── inject label em #textL
│   │   └── $e->outertext from .text
│
│   ├── "tex" / "med" (textarea)
│   │   ├── set id, name de <textarea>
│   │   ├── inject label em #textAreaL
│   │   └── $e->outertext from .textArea
│
│   └── "pas" (password input)
│       ├── set id, name de <input>
│       ├── inject label em #passwordL
│       └── $e->outertext from .password
│
├── if (isset($field['action']))
│   └── str_replace para adicionar atributos no input
└── return $t (string com HTML final)
```
