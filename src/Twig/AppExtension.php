<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('tva', [$this, 'calculTva']),
        ];
    }

    function calculTva($prixHT, $tva)
    {

        return round($prixHT / $tva, 0);
    }

    public function getName()
    {
        return 'tva_extension';
    }
    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$' . $price;

        return $price;
    }
}
