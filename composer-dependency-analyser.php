<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return (new Configuration())
    ->ignoreUnknownClasses(['tl_calendar_events'])
;
