<?php
    require_once "autoload/transaction.php";
    $Transaction = new Transaction();
    $is_active_transaction = $Transaction->checkTransactionWaiting($_SESSION['id']);
    if($is_active_transaction) {
        $pending_transaction = $Transaction->getTransactionWaiting($_SESSION['id']);
    }
