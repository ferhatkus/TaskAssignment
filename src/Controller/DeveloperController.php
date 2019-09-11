<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Developer;
use App\Entity\Todolist;

/**
 * Bu class Developer lara işleri dağıtmak için ve ekrana render etmek içindir.
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

        //Toplam işlerden Developer başına düşen iş miktarı.
        $averageWork = count($todoLists) / count($developers);
        
        //İş dağıtımı sonrası developerların toplam çalışma süresi
        $totalTime = $this->workSharing($developers, $todoLists, $averageWork);
        
        //Developerların günlük 9 saat çalıştığı varsayılarak, dağıtılan işlerdeki çalışılacak gün sayısı
        //Developerların eş zamanlı çalıştığı varsayılarak en fazla günde iş yapan
        //developerın gün sayısı toplam işin gün sayısı demektir.
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

    /**
     * Bu Metod İş paylaşımı yapan asıl metoddur.
     * @param type $developers
     * @param type $todoLists
     * @param type $averageWork
     * @return type float
     */
    private function workSharing($developers, $todoLists, $averageWork) {
        $i = 0;
        $totalTime = 0;
        $notAssignmentTodoLists = array();
        
        foreach ($todoLists as $todoList) {
            //Atama yapılmış bir developer var mı?
            if ($todoList->getAssignetDeveloper() == null) {
                $developer = $developers[$i++];
                
                //İş yapma süresi (Saat cinsinden)
                $devTime = $developer->getTime();

                // $devTime saatte yaptığı işin zorluğu.
                $devLevel = $developer->getDifficulty();

                //Verilecek işin zorluğu
                $todoLevel = $todoList->getLevel();

                //İşi tamamlayabileceği süre
                //Saatte yaptığı iş zorluğu ile doğru orantılıdır. 
                $todoTime = round((($devTime * $todoLevel) / $devLevel), 2);
                
                //İşi yapabilir mi ? Mevcut iş yükü ve seviyesi uygun mu?
                if ($developer->canTakeWork($averageWork, $todoList->getLevel())) {
                    $todoList->setTime($todoTime);
                    $todoList->setAssignetDeveloper($developer);
                    $developer->addTodoList($todoList);
                    $totalTime += $todoTime;
                } else {
                    //Toplam iş yükü ve seviyesi bu iş için uygun değil,
                    //Diğer developerlara dağıtılabilmesi için listeye ekle.
                    $notAssignmentTodoLists[] = $todoList;
                }

                //$i developer sayacıdır.
                //developerlar bittiğinde başa döndürülür.
                if ($i > count($developers) - 1) {
                    $i = 0;
                }
            }
        }

        //Atama yapılamayan iş var mı diye bakılır.
        //Varsa metod tekrar çağrılır ve atama yapılana kadar devam eder.
        //Atama yapılamayan iş sayısı developer sayısından az ise, 
        //döngünün sağlıklı devam edebilmesi için atama yapılamayan işler developer sayısı kadar arttırılır.
        if (count($notAssignmentTodoLists) > 0) {
            if (count($notAssignmentTodoLists) < count($developers)) {
                $x = count($developers) - count($notAssignmentTodoLists);
                for ($j = 0; $j < $x; $j++) {
                    $notAssignmentTodoLists[] = $notAssignmentTodoLists[0];
                }
            }
            
            //Atama yapılamayan işler için tekrar çalış.
            return round($totalTime + $this->workSharing($developers, $notAssignmentTodoLists, $averageWork), 2);
        } else {
            return round($totalTime, 2);
        }
    }

}
