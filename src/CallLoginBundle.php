<?php

declare(strict_types=1);

namespace vBridgeCloud\CallLoginBundle;

use vBridgeCloud\CallLoginBundle\DependencyInjection\CallLoginExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class CallLoginBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new CallLoginExtension();
    }
}
