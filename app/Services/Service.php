<?php

namespace App\Services;

use Zttp\PendingZttpRequest;
use Zttp\ZttpResponse;

abstract class Service {

    protected $attributes = [];

    /** @var PendingZttpRequest $applicantForm */
    protected $pendingRequest;

    protected $headers = [
        'Accept' => 'application/json',
    ];

    static $baseRouteURL = '';

    public function __construct(array $attributes)
    {
        static::$baseRouteURL = config('pendency.api_url');

        $this->attributes = $attributes;

        $this->pendingRequest = new PendingZttpRequest;
    }

    /**
     * Post a json request to micro service.
     *
     * @param $route
     *
     * @return ZttpResponse;
     */
    public function postJson($route)
    {
        $baseRouteURL = static::$baseRouteURL;

        /** @var ZttpResponse $response */
        $response = $this->pendingRequest
            ->asFormParams()
            ->post("{$baseRouteURL}{$route}", $this->attributes);

        if ($response->isSuccess()) {
            return $response;
        }

        $this->abortIfApiError($response);
    }

    /**
     * @param ZttpResponse $response
     *
     */
    protected function abortIfApiError($response): void
    {
        abort(500, response()->json(['message' => $response->json()['message'] ?? 'Something went wrong.']));
    }
}