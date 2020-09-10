<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use ZipArchive;

/**
 * Class TemplatesController
 * @package App\Controller
 */
class TestController extends AppController {

    public function index()
    {
        $zip = new ZipArchive;

        $archive = $zip->open(WWW_ROOT . 'documents/matters.zip');

//
//        if (isset($_GET['content'])) {
//
//            echo $_GET['filename'];
//
//            $content = $_GET['content'];
//            $path = $_GET['path'];
//            header("Content-type:" . $content);
//            header("Content-Disposition: inline; filename=" . $_GET['filename']);
//            @readfile('zip://' . WWW_ROOT . 'documents/matters.zip#' . $path);
//        }
//
//
//        if ($archive === TRUE) {
//
//            $dirName = '1 - Thomas Alvord v Quick Fi Capital';
//
//            $matterTable = TableRegistry::getTableLocator()->get('Matters');
//            $matters     = $matterTable->find()->all();
//
//
//            //foreach ($matters as $matter) {
//
//                //$dirName = $matter->id . ' - ' . $matter->name;
//
//                for ($i = 1; $i < $zip->numFiles; $i++) {
//
//                    $stat = $zip->statIndex($i);
//
//                    if (preg_match("/\/" . $dirName . "\//", $stat['name'])) {
//
//                        if (strpos(basename($stat['name']), '.pdf') !== false) {
//                            echo '<a target="_blank" href="?content=application/pdf&path=' . $stat['name'] . '&filename=' . basename($stat['name']) . '">' . basename($stat['name']) . '</a>' . '<br>';
//                        }
//
//                    }
//
//                }
//            //}
//
//
//        } else {
//            echo 'Error with code:' . $archive;
//        }


        if ($archive === TRUE) {
            $zip->addFile('token.json', 'test/newtoken.json');
            $zip->close();
            echo 'done';
        } else {
            echo 'error';
        }

        return $this->response->withStatus(200);
    }


    public function createMatterDir() {

        $matterTable = TableRegistry::getTableLocator()->get('Matters');
        $matters     = $matterTable->find()->all();

        foreach ($matters as $matter) {

            $dirName = $matter->id .' - '. $matter->name;
            $dirPath = WWW_ROOT.'documents/matters/'.$dirName;

            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0777, true);
                echo $dirPath . '<br>';
            }

        }

        return $this->response->withStringBody('someTest');
    }

    public function getListFolders()
    {
        $path = WWW_ROOT . 'documents/matters';
        return array_diff(scandir($path), array('.', '..'));
    }

}
