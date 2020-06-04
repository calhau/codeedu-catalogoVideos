<?php

declare(strict_types=1);

namespace Tests\Traits;

use Exception;
use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{
    protected function assertStore(array $sendData, array $testDatabaseData, array $testJsonData = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->routeStore(), $sendData);
        if ($response->status() !== 201) {
            throw new Exception("Response status must be 201, given {$response->status()}:\n {$response->content()}");
        }

        $this->assertInDatabase($response, $testDatabaseData);
        $this->assertJsonResponseContent($response, $testDatabaseData, $testJsonData);

        return $response;
    }

    protected function assertUpdate(array $sendData, array $testDatabaseData, array $testJsonData = null)
    {
        /** @var TestResponse $response */
        $response = $this->json('PUT', $this->routeUpdate(), $sendData);
        if ($response->status() !== 200) {
            throw new Exception("Response status must be 200, given {$response->status()}:\n {$response->content()}");
        }

        $this->assertInDatabase($response, $testDatabaseData);
        $this->assertJsonResponseContent($response, $testDatabaseData, $testJsonData);

        return $response;
    }

    private function assertInDatabase($response, $testDatabaseData)
    {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabaseData + ['id' => $response->json('id')]);
    }

    private function assertJsonResponseContent($response, $testDatabaseData, $testJsonData)
    {
        $testResponse = $testJsonData ?? $testDatabaseData;
        $response->assertJsonFragment($testResponse + ['id' => $response->json('id')]);
    }
}
