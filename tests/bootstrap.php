<?php

use App\Kernel;

/**
 * @throws Exception
 */
function bootstrap(): void
{
    $kernel = new Kernel('test', true);
    $kernel->boot();
}

bootstrap();