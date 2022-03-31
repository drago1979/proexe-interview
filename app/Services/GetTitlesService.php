<?php


namespace App\Services;

use External\Foo\Movies\MovieService as MoviesFoo;
use External\Foo\Exceptions\ServiceUnavailableException as FooServiceUnavailableException;
use External\Baz\Movies\MovieService as MoviesBaz;
use External\Baz\Exceptions\ServiceUnavailableException as BazServiceUnavailableException;
use External\Bar\Movies\MovieService as MoviesBar;
use External\Bar\Exceptions\ServiceUnavailableException as BarServiceUnavailableException;

class GetTitlesService
{
    private $titles = [];
    private $cachingPeriod = 1;
    private $numberOfApiCallAttempts = 5;
    private $failedAttemptCounter = 0;

    /**
     * @return array
     * @throws \Exception
     * If data retrieved successfully: data array returned;
     * If not, array with "error" key returned.
     *
     */
    public function getTitles(): array
    {
        // Getting titles` values & returning error flag if any of values not retrieved
        if (empty($titlesFoo = $this->getFooTitles())) {
            return $this->titles = ['error' => true];
        }

        if (empty($titlesBaz = $this->getBazTitles())) {
            return $this->titles = ['error' => true];
        }

        if (empty($titlesBar = $this->getBarTitles())) {
            return $this->titles = ['error' => true];
        }

        // If no errors - create return result with title values
        $this->titles = array_merge($this->titles, $titlesFoo);

        foreach ($titlesBaz['titles'] as $value) {
            $this->titles[] = $value;
        }

        foreach ($titlesBar['titles'] as $value) {
            $this->titles[] = $value['title'];
        }

        return $this->titles;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getFooTitles()
    {
        return cache()->remember('api_call_foo', $seconds = $this->cachingPeriod, function () {
            return $this->getFooTitlesApi();
        });
    }

    /**
     * @return array|null
     * We try to retrieve the titles for defined number of attempts ($numberOfApiCallAttempts).
     * If the number is reached - we abort;
     */
    private function getFooTitlesApi(): ?array
    {
        try {
            return (new MoviesFoo())->getTitles();
        } catch (FooServiceUnavailableException $e) {
            if ($this->failedAttemptCounter < $this->numberOfApiCallAttempts) {
                ++$this->failedAttemptCounter;

                $this->getFooTitlesApi();
            }
            return null;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getBazTitles()
    {
        return cache()->remember('api_call_baz', $seconds = $this->cachingPeriod, function () {
            return $this->getBazTitlesApi();
        });
    }

    /**
     * @return array|null
     * We try to retrieve the titles for defined number of attempts ($numberOfApiCallAttempts).
     * If the number is reached - we abort;
     */
    private function getBazTitlesApi(): ?array
    {
        try {
            return (new MoviesBaz())->getTitles();
        } catch (BazServiceUnavailableException $e) {
            if ($this->failedAttemptCounter < $this->numberOfApiCallAttempts) {
                ++$this->failedAttemptCounter;

                $this->getFooTitlesApi();
            }
            return null;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getBarTitles()
    {
        return cache()->remember('api_call_bar', $seconds = $this->cachingPeriod, function () {
            return $this->getBarTitlesApi();
        });
    }

    /**
     * @return array|null
     * We try to retrieve the titles for defined number of attempts ($numberOfApiCallAttempts).
     * If the number is reached - we abort;
     */
    private function getBarTitlesApi(): ?array
    {
        try {
            return (new MoviesBar())->getTitles();
        } catch (BarServiceUnavailableException $e) {
            if ($this->failedAttemptCounter < $this->numberOfApiCallAttempts) {
                ++$this->failedAttemptCounter;

                $this->getFooTitlesApi();
            }
            return null;
        }
    }
}
