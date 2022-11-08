<?php
if (isset($_GET['Submit'])) {

    $id = $_GET['id'];

    $pdo = new PDO($dsn, $user, $pass, $options);

    if (is_numeric($id) && strlen((string)$id) == 3) {
        $statement = $pdo->prepare('SELECT first_name, last_name FROM users WHERE user_id = :id;');
        $statement->bindParam("id", $id, PDO::PARAM_INT);
        $statement->execute();

        if ($statement->rowCount() == 1) {
            $html .= '<h3>User ID exists in the database.</h3>';
        } else {
            header($_SERVER[ 'SERVER_PROTOCOL' ].' 404 Not Found');
            $html .= '<h3>User ID not present in the database.</h3>';
        }
    }
}
