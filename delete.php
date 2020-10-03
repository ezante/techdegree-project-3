<?php
require 'inc/functions.php';

if (isset($_POST['id'])) {
    deleteEntry(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
}

header('Location: index.php');
exit;
