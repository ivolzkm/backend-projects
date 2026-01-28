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

function listTasks($filter = null){
    $tasks = loadTasks();
    
    if (empty($tasks)) {
        echo "Nenhuma tarefa encontrada.\n";
        return;
    }
    
    foreach ($tasks as $task) {
        // Se tem filtro E o status não bate, pula esta tarefa
        if ($filter !== null && $task['status'] !== $filter) {
            continue;
        }
        
        // Exibe a tarefa
        echo "[{$task['id']}] {$task['description']} - Status: {$task['status']}\n";
    }
}

function updateTaskStatus($id, $newStatus){
    $tasks = loadTasks();
    $found = false;
    foreach($tasks as &$task){
    // Procura a tarefa pelo ID    
    if ($task['id']==$id){
          $task['status']=$newStatus;  
          $task['UpdatedAt'] = date("Y-m-d H:i:s");
          $found = true;
          break;
        }
    }
    if ($found){
        file_put_contents("tasks.json", json_encode($tasks, JSON_PRETTY_PRINT));
        echo "Tarefa $id atualizada para '$newStatus' ! \n";
    } else {
        echo "Tarefa não encontrada";
    }
}

function deleteTask($id){
    $tasks = loadTasks();
    $found = false;
    foreach ($tasks as $index => $task) {
        // Procura a tarefa pelo ID
        if ($task['id'] == $id) {
            unset($tasks[$index]);
            $found = true;
            break;
        }
    }
    
    if ($found) {
        $tasks = array_values($tasks);  // reindexar
        file_put_contents("tasks.json", json_encode($tasks, JSON_PRETTY_PRINT));
        echo "Tarefa $id deletada com sucesso!\n";
    } else {
        echo "Tarefa com ID $id não encontrada.\n";
    }
}
function updateTask($id, $newDescription) {
    $tasks = loadTasks();
    $found = false;
    
    foreach ($tasks as &$task) {
        if ($task['id'] == $id) {
            $task['description'] = $newDescription;
            $task['UpdatedAt'] = date("Y-m-d H:i:s");
            $found = true;
            break;
        }
    }

    if ($found) {
        file_put_contents("tasks.json", json_encode($tasks, JSON_PRETTY_PRINT));
        echo "Tarefa $id atualizada com sucesso!\n";
    } else {
        echo "Tarefa com ID $id não encontrada.\n";
    }
}

//Verifica se o usuário está adicionando uma tarefa, executando a função "addTask" se verdadeiro.
if (isset($argv[1]) && $argv[1] == "add") {
    if (isset($argv[2])) {
        addTask($argv[2]);
    } else {
        echo "Forneça uma tarefa para adicionar.\n";
    }
} elseif (isset($argv[1]) && $argv[1] == "list") {  // <- elseif
    $filter = isset($argv[2]) ? $argv[2] : null;
    listTasks($filter);
} elseif (isset($argv[1]) && $argv[1] == "mark-in-progress") {
    if (isset($argv[2])) {
        updateTaskStatus($argv[2], "in-progress");
    } else {
        echo "Forneça o ID da tarefa.\n";
    }
}
elseif (isset($argv[1]) && $argv[1] == "mark-done") {
    if (isset($argv[2])) {
        updateTaskStatus($argv[2], "done");
    } else {
        echo "Forneça o ID da tarefa.\n";
    }
}
elseif (isset($argv[1])&&($argv[1]=="delete")){
    if (isset($argv[2])) {
        deleteTask($argv[2]);
    } else {
        echo "Forneça um ID para a tarefa. \n";
    }
} elseif (isset($argv[1]) && $argv[1] == "update") {
    if (isset($argv[2]) && isset($argv[3])) {
        updateTask($argv[2], $argv[3]);
    } else {
        echo "Forneça o ID e a nova descrição.\n";
    }
}
else {
    echo "Comando não reconhecido. Use 'add', 'list', 'mark-in-progress', 'mark-done' ou 'delete'.\n";
} 