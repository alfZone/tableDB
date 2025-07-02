## showHTML()

lê $action = getParameter('do')
    case "" ou "l"   ➜  lista (makeAlist + includes)
    case "dm"        ➜  prepara modal de importação CSV
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
