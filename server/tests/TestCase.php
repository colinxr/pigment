<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->detectEnvironment(function () {
            return 'testing';
        });

        $this->initializeDirectory($this->getTempDirectory());
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }

    protected function setUpTempTestFiles()
    {
        $this->initializeDirectory($this->getTestFilesDirectory());
        File::copyDirectory(__DIR__ . '/TestSupport/testfiles', $this->getTestFilesDirectory());
    }

    public function getTempDirectory(string $suffix = ''): string
    {
        return __DIR__ . '/TestSupport/temp' . ($suffix == '' ? '' : '/' . $suffix);
    }

    public function getTestFilesDirectory(string $suffix = ''): string
    {
        return $this->getTempDirectory() . '/testfiles' . ($suffix == '' ? '' : '/' . $suffix);
    }

    public function getTestJpg(): string
    {
        return $this->getTestFilesDirectory('test.jpg');
    }

    public function getTestPng(): string
    {
        return $this->getTestFilesDirectory('test.png');
    }

    function buildHiATimestamp(string $time)
    {
        return date('h:i a', strtotime($time));
    }

    function buildShopHours(string $open, string $close): array
    {
        return [
            'open' => $this->buildHiATimestamp($open),
            'close' => $this->buildHiATimestamp($close)
        ];
    }
}
