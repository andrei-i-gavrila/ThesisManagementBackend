<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function get($uri, array $options = [])
    {
        return $this->getJson($uri, $options);
    }


    public function post($uri, array $options = [], array $headers = [])
    {
        return $this->postJson($uri, $options);
    }


    public function delete($uri, array $options = [], array $headers = [])
    {
        return $this->deleteJson($uri, $options);
    }


    public function patch($uri, array $options = [], array $headers = [])
    {
        return $this->patchJson($uri, $options);
    }


    public function put($uri, array $options = [], array $headers = [])
    {
        return $this->putJson($uri, $options);
    }
}
