<?php

namespace Joking\Kernel\Functions\Interfaces;


interface ApplicationListen {

    public function handle(ApplicationEvent $event);

}