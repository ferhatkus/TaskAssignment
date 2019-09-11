<?php

namespace App\Model\Todolist;

/**
 *
 * @author Ferhat KUS
 */
abstract class ToDoListModel extends \App\Entity\Todolist {

    abstract public function createFromJSON($obj);
}
