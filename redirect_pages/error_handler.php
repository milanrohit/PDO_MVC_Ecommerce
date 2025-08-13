<?php
// error_handler.php

// ✅ Custom Error Handler
function customError($errno, $errstr, $errfile, $errline) {
    $message = "Error [$errno]: $errstr in $errfile on line $errline\n";
    error_log($message, 3, __DIR__ . "/logs/errors.log");
    echo "<h2>Something went wrong. Please try again later.</h2>";
}

// ✅ Custom Exception Handler
function customException($exception) {
    $message = "Uncaught Exception: " . $exception->getMessage() . "\n";
    error_log($message, 3, __DIR__ . "/logs/errors.log");
    echo "<h2>Unexpected error occurred.</h2>";
}

// ✅ Register Handlers
set_error_handler("customError");
set_exception_handler("customException");
