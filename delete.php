<?php

include 'functions.php';
$pdo = pdo_connect();

session_start();

function csrf_token_is_valid()  {
    if(!isset($_POST['csrf_token']))    {
        return false;
    }
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return ($_POST['csrf_token'] === $_SESSION['csrf_token']);
}

if (csrf_token_is_valid())  {
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        header("location:index.php");
    } else {
        die ('No ID specified!');
    }
}   else    {

}

?>