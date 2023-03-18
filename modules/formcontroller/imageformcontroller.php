<?php

namespace FormController;

class ImageFormController extends \FormController\BaseFormController
{
    public function item(array $data) 
    {
        echo 'I\'m method item of ImageFormController<br>';
        print_r($data);
    }
}

?>