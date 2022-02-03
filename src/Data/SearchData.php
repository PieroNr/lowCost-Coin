<?php
namespace App\Data;

use App\Entity\Tag;

class SearchData
{

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Tag[]
     */
    public $tags = [];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;


}