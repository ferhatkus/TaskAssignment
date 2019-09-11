<?php

namespace App\Model\Todolist;

/**
 * Description of ToDoListJSONType2
 *
 * @author Asus
 */
class ToDoListJSONType2 extends \App\Model\Todolist\ToDoListModel {

    public function createFromJSON($obj) {
        foreach ($obj as $key => $val) {
            $this->setName($key);
            $this->setLevel($val->{'level'});
            $this->setEstimatedDuration($val->{'estimated_duration'});
        }
    }

}
