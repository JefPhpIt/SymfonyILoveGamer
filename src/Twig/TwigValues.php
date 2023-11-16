<?php
namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigValues extends AbstractExtension
{
    public function  getFilters(): array{

        return [
            new TwigFilter('values', [$this, 'twig_array_values']),
        ];
    }

    public function twig_array_values(array $array): array
    {
        return array_values($array);
    }
}