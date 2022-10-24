<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageTest extends TestCase
{
    public function testStorage()
    {
        $filesystem = Storage::disk('local');
        
        $filesystem->put("file.txt", "Rachmat Ardico Perdana");

        $content = $filesystem->get('file.txt');

        self::assertEquals("Rachmat Ardico Perdana", $content);
    }

    public function testPublic()
    {
        $filesystem = Storage::disk('public');
        
        $filesystem->put("file.txt", "Rachmat Ardico Perdana");

        $content = $filesystem->get('file.txt');

        self::assertEquals("Rachmat Ardico Perdana", $content);
    }
}
