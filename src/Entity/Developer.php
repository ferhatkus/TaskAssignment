<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeveloperRepository")
 */
class Developer {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @ORM\Column(type="integer")
     */
    private $difficulty;
    private $todoLists = array();

    //Getters & Setters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getTime() {
        return $this->time;
    }

    public function getDifficulty() {
        return $this->difficulty;
    }

    public function getTodoLists() {
        return $this->todoLists;
    }

    public function getTodoListsCountBigFromLevel($level) {
        $cnt = 0;
        foreach ($this->todoLists as $todoList) {
            if ($todoList->getLevel() > $level) {
                $cnt++;
            }
        }

        return $cnt;
    }

    public function getTotalWorkTime() {
        $totalTime = 0;
        foreach ($this->todoLists as $todoList) {
            $totalTime += $todoList->getTime();
        }

        return $totalTime;
    }

    public function getTotalWorkDay() {
        $totalTime = $this->getTotalWorkTime();
        return round($totalTime / 9, 1);
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function setDifficulty($difficulty) {
        $this->difficulty = $difficulty;
    }

    public function addTodoList($todoList) {
        $this->todoLists[] = $todoList;
    }

    public function canTakeWork($averageWork, $workLevel) {
        if ($workLevel < $this->difficulty) {
            return 1;
        } else {
            if ($workLevel > $this->difficulty) {
                $x = $this->difficulty * 3;
                if ($x > $this->getTodoListsCountBigFromLevel($this->difficulty)) {
//                    if ($averageWork > count($this->todoLists)) {
                    return 2;
//                    }
                }
            } else {
                return 3;
            }

            return false;
        }
    }

}
