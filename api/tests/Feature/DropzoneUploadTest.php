<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DropzoneUploadTest extends TestCase
{

    use RefreshDatabase;

    protected  function tearDown(): void
    {
        Storage::deleteDirectory('/temp');
        Storage::makeDirectory('/temp');
    }

    public function test_can_upload_files_from_dropzone(): void
    {
        $response = $this->post('/api/messages/images/temp', [
            'attachments' => [
                UploadedFile::fake()->image('test.jpg'),
                UploadedFile::fake()->image('test.png'),
            ],
        ]);

        $response->assertStatus(201);
        $data = json_decode($response->getContent());
        $this->assertCount(2, $data->data);
    }
}
