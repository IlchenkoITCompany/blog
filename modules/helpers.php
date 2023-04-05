<?php

namespace Helpers
{
    function render(string $template, array $context)
    {
        global $basePath;
        extract($context);
        require_once $basePath.'modules/templates/'.$template.'.php';
    }

    function connectToDb() 
    {
        $connectionString = 'mysql:host='.\Settings\DB_HOST.';dbname='.\Settings\DB_NAME.';charset=utf8';
        return new \PDO($connectionString, \Settings\DB_USERNAME, \Settings\DB_PASSWORD);
    }

    function showErrors(string $fldName, array $formData)
    {
        if(isset($formData['__errors'][$fldName])) {
            if(is_array($formData['__errors'][$fldName])) {
                foreach ($formData['__errors'][$fldName] as $error => $value) {
                    echo '<div class="error">'.$formData['__errors'][$fldName][$error].'</div>';
                }
            } else {
                echo '<div class="error">'.$formData['__errors'][$fldName].'</div>';
            }
        }
    }

    function redirect(string $url, int $status = 302) 
    {
        header('Location: '.$url, TRUE, $status);
    }

    function getFragmentPath(string $fragment): string 
    {
        global $basePath;
        return $basePath.'modules/templates/helpers_templates/'.$fragment.'.php';
    }

    function getRenderedLabel($fieldName, $labelName, $fieldType = 'input')
    {
        $labelString = '';
        if($fieldType === 'checkbox') {
            $labelString = '<label class=\'form-check-label\' for=\''.$fieldName.'\'>'.$labelName.'</label>';
        } else {
            $labelString = '<div><label class=\'form-label\' for=\''.$fieldName.'\'>'.$labelName.'</label></div>';
        }

        return $labelString;
    }

    function getRenderedInput($type, $name, array $formData, $value = '', $placeholder = '')
    {
        $input = '<div><input class=\'form-control\' type=\''.$type.'\' name=\''.$name.'\' ';

        if ($value !== '') {
            $input .= 'value=\''.$value.'\' ';
        }
        
        if ($placeholder !== '') {
            $input .= 'placeholder=\''.$placeholder.'\'';
        } 
        $input .= '></div>';
        
        return $input;
    }

    function getRenderedCheckbox($name, array $formData, $value = FALSE) 
    {
        $input = '<input class=\'form-check-input checkbox-input\' type=\'checkbox\' name=\''.$name.'\' ';

        if($value === TRUE) {
            $input .= 'value=\''.$value.'\' checked';
        } else if ($value === FALSE) {
            $input .= 'value=\''.$value.'\'';
        }

        $input .= '>';

        return $input;
    }

    function getRenderedFileInput($name, array $formData, $multiple = FALSE)
    {
        $input = '<div><input class=\'form-control\' type=\'file\' ';

        if($multiple === TRUE) {
            $input .= 'name=\''.$name.'[]\'';
        } else {
            $input .= 'name=\''.$name.'\'';
        }

        if($multiple === FALSE) {
            $input .= '>';
        } else if ($multiple === TRUE) {
            $input .= ' multiple>';
        }

        $input .= '</div>';


        return $input;
    }

    function getRenderedTextArea($name, array $formData)
    {
        $textArea = '<div><textarea rows=\'3\' class=\'form-control\' name="'.$name.'"></textarea></div>';

        return $textArea;
    }

    function getOptionsForSelect($modelName, $fields, $links = NULL, $where = '')
    {
        $workArray = array();
        $modelName = '\Model\\'.$modelName;
        $model = new $modelName;
        $fieldsArray = explode(', ', $fields);
        $optionName = $fieldsArray[0];
        $optionValue = $fieldsArray[1];
        
        if($links !== NULL) {
            $model->select($fields, $links);
        } else if ($links !== NULL && $where !== ''){
            $model->select($fields, $links, $where);
        } else {
            $model->select($fields);
        }

        foreach ($model as $key => $value) {
            $workArray[$value[$optionName]] = $value[$optionValue];
        }

        return $workArray;
    }

    function getPreparedSelectArray(array $selectOptions) 
    {
        $preparedArray = array();
        foreach ($selectOptions as $optionKey => $optionValue) {
            if (is_array($optionValue) && count($optionValue) === 2) {
                $keys = array_keys($optionValue);
                $preparedArray[$keys[0]] = $keys[1];
            } else if (!is_array($optionValue)) {
                    $preparedArray[$optionKey] = $optionValue;
            } else {
                echo 'Array is not right';
            }
        }
        return $preparedArray;
    }

    function getRenderedSelect($name, array $formData, array $options, $selectedOption = FALSE)
    {
        $options = getPreparedSelectArray($options);
        $select = '<div><select class=\'form-select\' name=\''.$name.'\'>';

        if($selectedOption) {
            $select .= '<option selected>'.$selectedOption.'</option>';
        }

        foreach ($options as $valueName => $value) {
            $select .= '<option value=\''.$valueName.'\'>'.$value.'</option>';
        }

        $select .= '</select></div>';
        
        return $select;
    }

    function fileProcessing($field, $fileName, array $extensions, $error, array &$errors, $optional = FALSE, $multiple = FALSE)
    {
        $extensionsString = implode(', ', $extensions);
        if ($error == UPLOAD_ERR_NO_FILE) {
            if ($optional === FALSE) {
                if($multiple === FALSE) {
                    $errors[$field][$fileName] = 'Укажите необходымый файл';
                } else {
                    $errors[$field][$fileName] = 'Укажите необходымые файлы';
                }
            }
        } else if (!in_array(pathinfo($fileName, PATHINFO_EXTENSION), $extensions))
        {
            $errors[$field][$fileName] = 'Укажите файл с изображением в каком-либо из указанных ниже форматов: '.$extensionsString;
        } else if ($error == UPLOAD_ERR_OK) {

        } else if ($error == UPLOAD_ERR_INI_SIZE) {
            $errors[$field][$fileName] = 'Размер данного файла: '.$fileName.' превышает допустимый';
        } else {
            $errors[$field][$fileName] = 'Файл не был отправлен';
        }
    }

    function getFormattedTimestamp(string $timestamp) : string
    {
        $date = new \DateTime($timestamp);
        return $date->format('Y-m-d H:i:s');
    }

    function getGETparams(array $existingParamNames, array $newParams = []) : string
    {
        $a = [];
        foreach ($existingParamNames as $name)
        {
            if (!empty($_GET[$name]))
            {
                $a[] = $name.'='.urlencode($_GET[$name]);
            }
        }

        foreach ($newParams as $name => $value)
        {
            $a[] = $name.'='.urlencode($value);
        }
        $s = implode('&', $a);
        if($s)
        {
            $s = '?'.$s;
        }
        return $s;
    }

    function generateToken() : string 
    {
        if(session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        $token = bin2hex(random_bytes(32));
        $_SESSION[$token] = 'anti_csrf';
        return $token;
    }

    function checkToken(array $formData)
    {
        if(empty($formData['__token'])) {
            throw new \Exception\Page403Exception;
        }
        $token = $formData['__token'];
        if(empty($_SESSION[$token])) {
            throw new \Exception\Page403Exception;
        }
        $value = $_SESSION[$token];
        unset($_SESSION[$token]);
        if($value != 'anti_csrf') {
            throw new \Exception\Page403Exception;
        }
    }

}

?>