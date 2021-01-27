<?php

namespace ThaoHR\Http\Controllers\Api;

use ThaoHR\Repositories\Country\CountryRepository;
use ThaoHR\Transformers\CountryTransformer;

/**
 * Class CountriesController
 * @package ThaoHR\Http\Controllers\Api
 */
class CountriesController extends ApiController
{
    /**
     * @var CountryRepository
     */
    private $countries;

    public function __construct(CountryRepository $countries)
    {
        $this->middleware('auth');
        $this->countries = $countries;
    }

    /**
     * Get list of all available countries.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->respondWithCollection(
            $this->countries->all(),
            new CountryTransformer
        );
    }
}
