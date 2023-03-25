<?php
    // Chama o arquivo das funcoes
    require_once "tarefa.php";

    header("Content-Type: appication/json");
    // Array com  o retorno da api
    $retornoApi = [];

    // Requisições da query
    $fn = $_REQUEST["fn"] ?? null;
    $id = $_REQUEST["id"] ?? 0 ;
    $nomeTarefa = $_REQUEST["nomeTarefa"] ?? null;
    $descricao = $_REQUEST["descricao"] ?? null;


    // Recebe os dados de query
    $tarefa = new Tarefa;
    $tarefa->setId($id);

    // INSERIR
    if ($fn === "criar" && $nomeTarefa !== null && $descricao !== null) {
        // Define valores para serem inseridos
        $tarefa->setNomeTarefa($nomeTarefa);
        $tarefa->setDescricao($descricao);

        // Chama a função que insere e armazera retorno na chave tarefa 
        $data["tarefa"] = $tarefa->criar();
    }

    // LER
    if ($fn === "ler") {
        // Chama a função e armazera o retorno
        $data["tarefa"] = $tarefa->ler();
    }

    // ATUALIZA
    if ($fn === "atualizar" && $id > 0  && $nomeTarefa !== null && $descricao !== null) {
        // Define valores para serem enseridos
        $tarefa->setNomeTarefa($nomeTarefa);
        $tarefa->setDescricao($descricao);

        // Chama a função que insere e armazera retorno na chave tarefa 
        $data["tarefa"] = $tarefa->atualizar();
    }

    // EXCLUIR
    if ($fn === "excluir" && $id > 0 ) {
        // Chama a função e armazena o retorno
        $data["tarefa"] = $tarefa->excluir();
    }

    die(json_encode($retornoApi))
?>