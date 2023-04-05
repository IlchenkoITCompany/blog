<?php

namespace Model;
class Comment extends \Model\BaseModel
{
    protected const TABLE_NAME = 'comments';
    protected const DEFAULT_ORDER = 'uploaded DESC';
}

?>