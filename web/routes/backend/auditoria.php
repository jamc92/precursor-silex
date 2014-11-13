<?php

$app->match('/admin/auditoria', 'Precursor\\Application\\Controller\\Backend\\Auditoria::ver')
    ->bind('auditoria_list')
    ->method('GET|POST');

