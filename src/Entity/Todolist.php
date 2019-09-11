<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodolistRepository")
 */
class Todolist {

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
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $estimated_duration;
    private $time;
    private $assignetDeveloper = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getEstimatedDuration() {
        return $this->estimated_duration;
    }

    public function getTime() {
        return $this->time;
    }

    public function getAssignetDeveloper() {
        return $this->assignetDeveloper;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setEstimatedDuration($estimated_duration) {
        $this->estimated_duration = $estimated_duration;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function setAssignetDeveloper(Developer $assignetDeveloper) {
        $this->assignetDeveloper = $assignetDeveloper;
    }

}
