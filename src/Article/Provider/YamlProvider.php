<?php

namespace App\Article\Provider;

use Symfony\Component\Yaml\Yaml;

class YamlProvider
{
    /**
     * retourne les articles de articles.yaml
     * sous forme de tableau
     */
    public function getArticles() : array
    {
        try {
            return Yaml::parseFile(__DIR__.'/articles.yaml')['data'];
        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
    }
}
