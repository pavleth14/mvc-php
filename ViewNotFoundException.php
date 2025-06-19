<?php

    namespace App;

    class ViewNotFoundException extends \Exception {
        protected $message = 'View not found';
    }
