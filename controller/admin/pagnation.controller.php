<?php
include_once('../../model/connect.php');

if (isset($_POST['function'])) {
    $function = $_POST['function'];
    switch ($function) {
        case 'render':
            render();
            break;
        case 'getRecords':
            getTotalRecords();
            break;
    }
}
function render()
{

    if (isset($_POST['number_of_item']) && isset($_POST['current_page'])) {
        include_once('../../model/admin/pagnation.model.php');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $render = $_SESSION["render"];
        if (isset($_POST["filter"])) $render->setFilter(getFilterSQL($render->getTable(), $_POST["filter"]));
        else $render->setFilter("");
        $orderby;
        if(!empty($_POST["orderby"])) $orderby=$_POST["orderby"];
        $type = " ASC";
        if (!empty($_POST["order_type"])) $type = " " . $_POST["order_type"];
        if (empty($orderby)) {
            $id = array(
                'products' => 'id',
                'orders' => 'id',
                'accounts' => 'username',
                'authors' => 'id',
                'publishers' => 'id',
                'categories' => 'id',
                'suppliers' => 'id',
                'functions' => 'id',
                'goodsreceipts' => 'id',
                'discounts' => 'discount_code',
            );
            $orderby = $id[$render->getTable()];
        }
        $render->setOrderby("ORDER BY " . $render->getTable() . "." . $orderby . $type);
        $render->setNumberOfItem($_POST['number_of_item']);
        $render->setCurrentPage($_POST['current_page']);
        echo $render->render();
    }
}

function getTotalRecords()
{
    if (isset($_POST['number_of_item']) && isset($_POST['current_page'])) {
        include_once('../../model/admin/pagnation.model.php');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $render = $_SESSION["render"];
        if (isset($_POST["filter"])) $render->setFilter(getFilterSQL($render->getTable(), $_POST["filter"]));
        else $render->setFilter("");
        $render->setNumberOfItem($_POST['number_of_item']);
        $render->setCurrentPage($_POST['current_page']);
        echo ceil($render->getTotalRecords() / $_POST['number_of_item']);
    }
}
