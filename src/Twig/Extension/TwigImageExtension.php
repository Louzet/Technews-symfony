<?php

namespace App\Twig\Extension;

use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigImageExtension extends AbstractExtension
{

    private $package;

    public function __construct(Packages $package)
    {
       $this->package = $package;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('image', [$this, 'imageFilter'], ['is_safe' =>['html']])
        ];
    }

    public function imageFilter(string $image, array $options = []) : string
    {
        $default = [
            'height' => '100',
            'width' => '100'
        ];

        $options = array_merge($default, $options);

        $height = $options['height'];
        $width = $options['width'];

        $image = $this->package->getUrl('images/product/'.$image);

        return '<img alt="" src="'.$image.'" class="img-responsive" style="height:'.$height.'px; width:'. $width.'px;">';
    }


}