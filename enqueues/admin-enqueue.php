<?php

use MyPluginNamespace\WaxFramework\Enqueue\Enqueue;

defined( 'ABSPATH' ) || exit;

Enqueue::script( 'wax-app-script', 'js/app' );
Enqueue::style( 'wax-app-style', 'css/app' );
