<?php

$app->match('/admin/logs', 'Precursor\\Application\\Controller\\Backend\\Log::index')
    ->bind('logs_list');