<?php


namespace App\Services;

use External\Bar\Movies\MovieService as MoviesBar;
use External\Baz\Movies\MovieService as MoviesBaz;
use External\Foo\Movies\MovieService as MoviesFoo;

class GetTitlesService
{
    private $titles = [];

    public function getTitles()
    {
        $titlesFoo = (new MoviesFoo())->getTitles();
        $titlesBaz = (new MoviesBaz())->getTitles();
        $titlesBar = (new MoviesBar())->getTitles();


        $this->titles = array_merge($this->titles, $titlesFoo);

        foreach ($titlesBaz['titles'] as $value) {
            $this->titles[] = $value;
        }

        foreach ($titlesBar['titles'] as $value) {
            $this->titles[] = $value['title'];
        }

        return $this->titles;
    }
}
