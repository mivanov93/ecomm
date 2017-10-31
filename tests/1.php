<?php

namespace G;

interface p {
      const INTERFACE_NAME = __NAMESPACE__."\\p";
}

namespace z;
use G\p;
echo p::class;echo p::INTERFACE_NAME;