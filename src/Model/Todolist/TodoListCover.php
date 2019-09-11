<?php

namespace App\Model\Todolist;

use App\Model\Todolist\ToDoListJSONType1;
use App\Model\Todolist\ToDoListJSONType2;

/**
 *
 * @author Ferhat KUS
 */
class TodoListCover {

    public static function insertDBFromJSON($json, $output, $connection) {
        $arr = json_decode($json);
        $todoListArray = array();
        foreach ($arr as $obj) {
            if (TodoListCover::isKeyExists($obj, 'zorluk')) {
                $todoList = new ToDoListJSONType1();
            } else {
                $todoList = new ToDoListJSONType2();
            }

            $todoList->createFromJson($obj);
            $todoListArray[] = $todoList;
        }

        //Tüm todolist datasını sil.
        $query = 'DELETE FROM todolist;';
        foreach ($todoListArray as $todoList) {
            $query .= TodoListCover::getInsertQuery($todoList);
        }

//        $output->writeln($query);
        $connection->executeQuery($query);
    }

    private static function isKeyExists($json, $keyS) {
        foreach ($json as $row) {
            if (gettype($row) == 'object') {
                return TodoListCover::isKeyExists($row, $keyS);
            }
            if (isset($json->{$keyS})) {
                return true;
            }
        }

        return false;
    }

    private static function getInsertQuery(\App\Model\Todolist\ToDoListModel $todolist) {
        return "INSERT INTO `todolist` (`name`, `level`, `estimated_duration`) "
                . "VALUES ('" . $todolist->getName() . "'," .
                $todolist->getLevel() . "," .
                $todolist->getEstimatedDuration() . ");";
    }

}
