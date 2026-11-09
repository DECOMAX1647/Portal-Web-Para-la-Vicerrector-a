<?php
/* Front controller: única entrada del sitio. */

declare(strict_types=1);

$app = require dirname(__DIR__) . '/bootstrap/app.php';

$app->run();
