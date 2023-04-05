<?php
/**
 * There are result array of preg_match function in argument $params. To use parametres we can write $params['paramName']. paramName we can find in routes.php.
 */
namespace Controller;
class RecordController extends \Controller\BaseController
{
    public function list($params) 
    {
        $users = new \Model\User;
        $users->select();
        $this->render('list', ['filename' => 'helloworld.png', 'users' => $users]);
    }

    public function item($params) 
    {
        global $basePath;
        $formData = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = \Form\EverythingToTest::getNormalizedData($_POST);
            if(!isset($formData['__errors'])) {
                $formData = \Form\EverythingToTest::getPreparedData($formData);
                $files = new \Core\FileSystem($basePath);
                $filename = '202303281645.jpg';
                $files->deleteFile($filename);
            }
        } else {
            $formData = [];
        }
        $usersList = \Helpers\getOptionsForSelect('User', 'id, name');

        $context = 
        [
            'formData' => $formData,
            'users' => $usersList,
        ];
        $this->render('formtotest', $context);
    }

    public function by_user($params)
    {
        $this->render('by_user', $params);
    }

    public function by_cat($params)
    {
        $this->render('by_cat', $params);
    }

    public function add($params)
    {
        
    }
}

?>