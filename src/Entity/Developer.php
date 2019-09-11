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

    /**
     * Bu metod bir developerın kendi seviyesinden büyük
     * atandığı işlerin sayısını verir.
     * @param type $level
     * @return \App\Entity\ınt
     */
    public function getTodoListsCountBigFromLevel($level) {
        $cnt = 0;
        foreach ($this->todoLists as $todoList) {
            if ($todoList->getLevel() > $level) {
                $cnt++;
            }
        }

        return $cnt;
    }

    /**
     * Bu metod developera verilen tüm işlerin toplam tamamlanma süresini verir.
     * @return type
     */
    public function getTotalWorkTime() {
        $totalTime = 0;
        foreach ($this->todoLists as $todoList) {
            $totalTime += $todoList->getTime();
        }

        return $totalTime;
    }

    /**
     * Bu metod developara verilen tüm işlerin toplam tamamlanma gün sayısını veir.
     * @return type
     */
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

    /**
     * Bu metod ile developera iş ataması yapılırken,
     * mevcut iş yükü ve verilecek işin seviyesine göre
     * işi yapıp yapamayacağı belirlenir.
     * @param type $averageWork
     * @param type $workLevel
     * @return ınt|boolean
     */
    public function canTakeWork($averageWork, $workLevel) {
        //İşin seviyesi developerın 1 saatte yaptığı iş seviyesinden küçük mü?
        if ($workLevel < $this->difficulty) {
            return true;
        } else {
            //İşin seviyesi developerın 1 saatte yaptığı iş seviyesinden büyük mü?
            if ($workLevel > $this->difficulty) {


                //Developerlara iş dağılımı ve iş yükü eşit bir şekilde verilebilmesi için
                //Developerın 1 saatte yaptığı iş seviyesinin üstünde işlerden
                //kendi seviyesinin 3 katı kadar iş alabilir.
                $x = $this->difficulty * 3;
                if ($x > $this->getTodoListsCountBigFromLevel($this->difficulty)) {
//                    if ($averageWork > count($this->todoLists)) {
                    return true;
//                    }
                }
            } else {
                return true;
            }

            return false;
        }
    }

}
