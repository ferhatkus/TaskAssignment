<?php

namespace App\Model\Todolist;

/**
 * Description of ToDoList
 *
 * @author Asus
 */
abstract class ToDoListModel extends \App\Entity\Todolist {

    abstract public function createFromJSON($obj);
}
