<?php

namespace App\Model\Todolist;

/**
 *
 * @author Ferhat KUS
 */
class ToDoListJSONType2 extends \App\Model\Todolist\ToDoListModel {

    /**
     * Bu metod providerdan gelen JSON değerlerini
     * Object e eklemek için kullanılır.
     * @abstract
     * @param type $obj
     */
    public function createFromJSON($obj) {
        foreach ($obj as $key => $val) {
            $this->setName($key);
            $this->setLevel($val->{'level'});
            $this->setEstimatedDuration($val->{'estimated_duration'});
        }
    }

}
