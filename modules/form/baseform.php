<?php

namespace Form;
class BaseForm 
{
    protected const FIELDS = [];

    protected const FILES_FIELDS = [];
    
    private static function getInitialValue($fldName, $fldParams, $initial = [])
    {
        if(isset($initial[$fldName])) {
            $val = $initial[$fldName];
        } else if (isset($fldParams['initial'])) {
            $val = $fldParams['initial'];
        } else {
            $val = '';
        }
        return $val;
    }


    protected static function afterInitializeData(&$data) {}
    
    public static function getInitialData($initial = []) 
    {
        $data = [];
        foreach(static::FIELDS as $fldName => $fldParams) {
            $data[$fldName] = self::getInitialValue($fldName, $fldParams, $initial);
        }
        static::afterInitializeData($data);
        return $data;
    }

    protected static function afterNormalizeData(&$data, &$errors) {}

    public static function getNormalizedData($formData)
    {
        $data = [];
        $errors = [];
        foreach(static::FIELDS as $fldName => $fldParams) {
            $fldType = (isset($fldParams['type'])) ? $fldParams['type'] : 'string';
            if($fldType == 'boolean') {
                $data[$fldName] = !empty($formData[$fldName]);
            } else {
                if(empty($formData[$fldName])) {
                    $data[$fldName] = self::getInitialValue($fldName, $fldParams);
                    
                    if(!isset($fldParams['optional'])) {
                        $errors[$fldName] = 'Это поле обязательно к заполнению';
                    }
                } else {
                    $fldValue = $formData[$fldName];
                    switch($fldType) {
                        case 'integer':
                            $v = filter_var($fldValue, FILTER_SANITIZE_NUMBER_INT);
                            if($v) {
                                $data[$fldName] = $v;
                            } else {
                                $errors[$fldName] = 'Введите целое число';
                            }
                            break;
                        case 'float':
                            $v = filter_var($fldValue, FILTER_SANITIZE_NUMBER_FLOAT, ['flags' => FILTER_FLAG_ALLOW_FRACTION]);
                            if($v) {
                                $data[$fldName] = $v;
                            } else {
                                $errors[$fldName] = 'Введите вещественное число';
                            }
                            break;
                        case 'timestamp':
                            $v = date('Y-m-d H:i:s', $fldValue);
                            if($v) {
                                $data[$fldName] = $v;
                            } else {
                                $errors[$fldName] = 'Выберите дату и время';
                            }
                            break;
                        case 'email':
                            $v = filter_var($fldValue, FILTER_SANITIZE_EMAIL);
                            if($v) {
                                $data[$fldName] = $v;
                            } else {
                                $errors[$fldName] = 'Введите адрес электронной почты';
                            }
                            break;
                        default:
                            $data[$fldName] = filter_var($fldValue, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    }
                }
            }
        }
        foreach (static::FILES_FIELDS as $field => $params) {
            if(is_array($_FILES[$field]['name'])) {
                foreach ($_FILES[$field]['name'] as $index => $name) {
                    $error = $_FILES[$field]['error'][$index];
                    \Helpers\fileProcessing($field, $name, $params['extensions'], $error, $errors, $params['optional'], TRUE);
                }
            } else {
                $name = $_FILES[$field]['name'];
                $error = $_FILES[$field]['error'];
                \Helpers\fileProcessing($field, $name, $params['extensions'], $error, $errors, $params['optional'], FALSE);
            }
        }
        static::afterNormalizeData($data, $errors);
        if($errors) {
            $data['__errors'] = $errors;
        }
        return $data;
    }


    protected static function afterPrepareData(&$data, &$normData) {}

    public static function getPreparedData($normData)
    {
        $data = [];
        foreach(static::FIELDS as $fldName => $fldParams) {
            if (!isset($fldParams['nosave']) && isset($normData[$fldName])) {
                $val = $normData[$fldName];
                $data[$fldName] = $val;
            } 
        }
        static::afterPrepareData($data, $normData);
        return $data;
    }

    public static function getRenderedForm($formData, $submitButton, $formCssStyle, $selectOptions = NULL)
    {
        $initialData = static::getInitialData();
        if(static::FILES_FIELDS === NULL)
            echo '<div class=\'container\'><form method=\'post\'>';
        else {
            echo '<div class=\'container\'><form method=\'post\' enctype=\'multipart/form-data\'>';
        }
        if(static::FIELDS !== NULL) {
            foreach (static::FIELDS as $field => $params) {
                echo \Helpers\getRenderedLabel($field, $params['labelName'], $params['fieldType']);
                switch ($params['fieldType']) {
                    case 'input':
                        if(isset($params['placeholder']) && $params['placeholder'] !== '') {
                            echo \Helpers\getRenderedInput($params['inputType'], $field, $formData, $initialData[$field], $params['placeholder']);
                        } else {
                            echo \Helpers\getRenderedInput($params['inputType'], $field, $formData, $initialData[$field]);
                        }
                        break;
                    case 'checkbox':
                        echo \Helpers\getRenderedCheckbox($field, $formData, $initialData[$field]);
                        break;
                    case 'textarea':
                        echo \Helpers\getRenderedTextArea($field, $formData);
                        break;
                    case 'select':
                        if($selectOptions !== NULL) {
                            echo \Helpers\getRenderedSelect($field, $formData, $selectOptions[$field]);
                        }
                        break;
                }
                \Helpers\showErrors($field, $formData);
            }
        }
        if(static::FILES_FIELDS !== NULL) {
            foreach (static::FILES_FIELDS as $field => $params) {
                echo \Helpers\getRenderedLabel($field, $params['labelName']);
                if($params['maxFilesCount'] >= 2) {
                    echo \Helpers\getRenderedFileInput($field, $formData, TRUE);
                } else if ($params['maxFilesCount'] == 1) {
                    echo \Helpers\getRenderedFileInput($field, $formData, FALSE);
                }
                \Helpers\showErrors($field, $formData);
            }
        }
        echo '<input type=\'hidden\' name=\'token\' value=\''.$formData['__token'].'\'>';
        echo '<button class=\'btn btn-primary mb-3 submit-button\' type=\'submit\'>'.$submitButton.'</button></form></div>';
    }
}

?>