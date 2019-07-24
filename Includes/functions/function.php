<?php

//title
function getTitle()
{
    global $pageTitle;
    $title = '';
    if (isset($pageTitle)) {
        $title = $pageTitle;
    } else {
        $title = 'Default';
    }
    return $title;
}

//Get Data Query
function getdata($select, $from, $where = null, $value = null)
{
    global $conn;
    $stmt = $conn->prepare("SELECT $select FROM $from $where");
    $stmt->execute(array($value));
    $data = $stmt->fetchAll();
    return $data;
}
