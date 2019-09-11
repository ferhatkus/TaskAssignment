<?php

namespace App\Model\Todolist;

/**
 *
 * @author Ferhat KUS
 */
class ToDoListJSONType1 extends \App\Model\Todolist\ToDoListModel {

    /**
     * Bu metod providerdan gelen JSON değerlerini
     * Object e eklemek için kullanılır.
     * @abstract
     * @param type $obj
     */
    public function createFromJSON($obj) {
        $this->setName($obj->{'id'});
        $this->setLevel($obj->{'zorluk'});
        $this->setEstimatedDuration($obj->{'sure'});
    }

}
