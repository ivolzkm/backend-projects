#!/usr/bin/env php
<?php

function loadTasks(){
    $filename = "tasks.json";
    //Verifica se o arquivo "tasks.json" existe, se não existir cria e abre um "[]"
    if (!file_exists($filename)){
        file_put_contents($filename, "[]");
    }
    //Cria o json para as tarefas. 
    $json = file_get_contents($filename);
    $tasks = json_decode($json, true);
    
    return $tasks;
}

function addTask($description){
    //Carega as tarefas existentes
    $tasks = loadTasks();
    //Cria o ID
    $newId = count($tasks) + 1;
     
    //Cria a nova tarefa como array
    $newTask = [
        "id" => $newId,
        "description" => $description,
        "status" => "todo",
        "CreatedAt"=> date("Y-m-d H:i:s"),
        "UpdatedAt"=> date("Y-m-d H:i:s"),
    ];

    //Adiciona a nova tarefa no array $tasks[]
    $tasks[] = $newTask;
    //salva de volta no arquivo tasks.json
    file_put_contents("tasks.json", json_encode($tasks, JSON_PRETTY_PRINT));
    echo "Tarefa adicionada com sucesso! (ID: $newId)\n";

}

//Verifica se o usuário está adicionando uma tarefa, executando a função "addTask" se verdadeiro.
if (isset($argv[1]) && $argv[1] == "add") {
    if (isset($argv[2])) {
        addTask($argv[2]);
    } else {
    echo"Forneça uma tarefa para adicionar.";
    }
}

