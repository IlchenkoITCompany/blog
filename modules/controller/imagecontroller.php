<?php
/**
 * There are result array of preg_match function in argument $params. To use parametres we can write $params['paramName']. paramName we can find in routes.php.
 */
namespace Controller;
class ImageController extends \Controller\BaseController
{
    public function list($params) 
    {
        $this->render('list', ['filename' => 'helloworld.png']);
    }

    public function item($params) 
    {
        $this->render('item', ['filename' => 'helloworld.png']);
        print_r($params['params']['pictureId']);
    }

    public function by_user($params)
    {
        $this->render('by_user', $params);
    }

    public function by_cat($params)
    {
        $this->render('by_cat', $params);
    }
}

?>