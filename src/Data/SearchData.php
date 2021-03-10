<?php

namespace App\Data;

class SearchData
{
    /** 
     * @var string
     */
    public $q = '';

    /** 
     * @var Categories[]
     */
    public $categories = [];

    /** 
     * @var Couleurs[]
     */
    public $couleurs = [];

    /** 
     * @var Marques[]
     */
    public $marques = [];

    /** 
     * @var Formes[]
     */
    public $formes = [];

    /** 
     * @var Styles[]
     */
    public $style = [];

    /** 
     * @var null|integer
     */
    public $max;

    /** 
     * @var null|integer
     */
    public $min;
}
