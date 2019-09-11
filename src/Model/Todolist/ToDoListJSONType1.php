<?php

namespace App\Model\Todolist;

/**
 * Description of ToDoListJSONType1
 *
 * @author Asus
 */
class ToDoListJSONType1 extends \App\Model\Todolist\ToDoListModel {

    public function createFromJSON($obj) {
        $this->setName($obj->{'id'});
        $this->setLevel($obj->{'zorluk'});
        $this->setEstimatedDuration($obj->{'sure'});
    }

}
