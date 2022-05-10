<?php
// les messages d'erreurs qui s'affichent si les conditions ne sont pas renseignées
const ERROR_REQUIRED = "veuillez renseigner une todo";
const ERROR_TOO_SHORT = "veuillez entrer au moins 5 caractères";
// création de dbjson
$filename = __DIR__ . "/data/todos.json";
$error = "";
$todo = "";
$todos = [];

// 
if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
}
// 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $todo = $_POST["todo"] ?? "";
    // si il y a une erreur et que les conditions ne sont pas rempli alors les messages d'erreur vont s'afficher 
    if (!$todo) {
        $error = ERROR_REQUIRED;
        // permet de donner la condition a moins de 5 caractères 
    } elseif (strlen($todo) < 5) {
        $error = ERROR_TOO_SHORT;
    };
    // si il y a pas d'erreur il affiche le nom et l'id et la création du tableau
    if (!$error) {
        // les 3 points permet de récuperer tous les élements du tableau en php
        $todos = [...$todos, [
            "name" => $todo,
            "done" => false,
            "id" => time(),
        ]];
        file_put_contents($filename, json_encode($todos));
        $todo = "";
        header("location: index.php");
    }
};








?>






<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "./includes/head.php" ?>
    <title>Todo List</title>

</head>

<body>
    <div class="container">
        <?php require_once "./includes/header.php" ?>
        <div class="content">
            <div class="todo-container">
                <h1>Ma todo</h1>
                <form class="todo-form" action="#" method="post">
                    <input type="text" name="todo">
                    <button class="btn btn-primary">Ajouter</button>
                </form>
                <?php if ($error) :  ?>
                    <p class="text-danger"><?= $error ?></p>
                <?php endif; ?>
                <ul class="todo-list">
                    <!-- le foreach est une boucle qui va permetre d'afficher les données dans la todo -->
                    <?php foreach ($todos as $todo) : ?>
                        <li class="todo-item <?= $todo["done"] ? "low-opacity" : "" ?>">
                            <span class="todo-name"><?= $todo["name"]  ?></span>
                            <a href="./edit-todo.php?id=<?= $todo["id"] ?>">
                                <button class="btn btn-primary btn-small">Valider</button>
                            </a>
                            <a href="./remove-todo.php?id=<?= $todo["id"] ?>">
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php require_once "./includes/footer.php" ?>
    </div>

</body>

</html>