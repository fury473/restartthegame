<?php

namespace RTG\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RTGUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
