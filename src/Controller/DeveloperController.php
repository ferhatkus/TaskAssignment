<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Developer;
use App\Entity\Todolist;

/**
 *
 * @author Ferhat KUS
 */
class DeveloperController extends AbstractController {

    /**
     * @Route("/", name="developer_list")
     */
    public function developer() {

        //difficulty değeri sort edilmiş bir şekilde tüm Developer lar dönecek.
        //difficulty küçük den büyüğe doğru sort edilecek.
        //Developer ın 1 saatte yaptığı iş zorluğuna göre...
        $developers = $this->getDoctrine()->getRepository(Developer::class)
                ->findAll();

        //estimated_duration değeri sort edilmiş bir şekilde tüm TodoList ler dönecek.
        //level küçük den büyüğe doğru sort edilecek.
        $todoLists = $this->getDoctrine()->getRepository(Todolist::class)
                ->findAll();

        $averageWork = count($todoLists) / count($developers);
        $totalTime = $this->workSharing($developers, $todoLists, $averageWork);
        $workDays = array();
        foreach ($developers as $developer) {
            $workDays[] = $developer->getTotalWorkDay();
        }
        
        

        return $this->render('base.html.twig', array
                    ('developers' => $developers,
                    'todolists' => $todoLists,
                    'totaltime' => $totalTime,
                    'maxWorkDay' => max($workDays)
        ));
    }

//    private function workSharing(Developer $developers, Todolist $todoLists) {
    private function workSharing($developers, $todoLists, $averageWork) {
        $i = 0;
        $totalTime = 0;
        $notAssignmentTodoLists = array();
//        echo 'burada... developers: '
//        . count($developers)
//        . ' - $todoLists: ' . count($todoLists)
//        . '<br>';
        foreach ($todoLists as $todoList) {
            if ($todoList->getAssignetDeveloper() == null) {

                $developer = $developers[$i++];
                //İş yapma süresi (Saat cinsinden)
                $devTime = $developer->getTime();

                // $devTime saatte yaptığı işin zorluğu.
                $devLevel = $developer->getDifficulty();

                //Verilecek işin zorluğu
                $todoLevel = $todoList->getLevel();

                //İşi tamamlayabileceği süre
                $todoTime = round((($devTime * $todoLevel) / $devLevel), 2);
//                echo '>>$i:' . $i
//                . '>>$developers:' . count($developers)
//                . '>>$developer:' . $developer->getName()
//                . ' $devTodoLists: ' . count($developer->getTodoLists())
//                . ' getTodoListsCountBigFromLevel: ' . $developer->getTodoListsCountBigFromLevel($devLevel)
//                . ' $averageWork: ' . $averageWork
//                . ' $workLevel: ' . $todoList->getLevel()
//                . ' $todoList: ' . $todoList->getName()
//                . ' canTakeWork: ' . $developer->canTakeWork($averageWork, $todoLevel)
//                . '<br>';
                if ($developer->canTakeWork($averageWork, $todoList->getLevel())) {
                    $todoList->setTime($todoTime);
                    $todoList->setAssignetDeveloper($developer);
                    $developer->addTodoList($todoList);
                    $totalTime += $todoTime;
                } else {
                    $notAssignmentTodoLists[] = $todoList;
                }

                if ($i > count($developers) - 1) {
                    $i = 0;
                }
            }
        }

        if (count($notAssignmentTodoLists) > 0) {
            if (count($notAssignmentTodoLists) < count($developers)) {
                $x = count($developers) - count($notAssignmentTodoLists);
                for ($j = 0; $j < $x; $j++) {
                    $notAssignmentTodoLists[] = $notAssignmentTodoLists[0];
                }
            }
            return round($totalTime + $this->workSharing($developers, $notAssignmentTodoLists, $averageWork), 2);
        } else {
            return round($totalTime, 2);
        }
    }

}
