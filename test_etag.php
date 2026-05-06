<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Storage;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$response = Storage::disk('local')->response('test.txt');
echo get_class($response)."\n";
echo method_exists($response, 'setEtag') ? "setEtag exists\n" : "no setEtag\n";
echo method_exists($response, 'isNotModified') ? "isNotModified exists\n" : "no isNotModified\n";
