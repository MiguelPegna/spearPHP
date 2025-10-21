<?php

    // helpers.php
    use core\Helpers\DebugHelper;
    use core\Helpers\FileHelper;
    use core\Helpers\SecurityHelper;

    if (!function_exists('dp')) {
        function dp($data) {
            DebugHelper::dp($data);
        }
    }

    if (!function_exists('isCli')) {
        function isCli(): bool {
            return DebugHelper::isCli();
        }
    }

    if (!function_exists('uuid')) {
        function uuid(): string {
            return DebugHelper::uuid();
        }
    }

    if (!function_exists('makeDirectory')) {
        function createDirectory(string $path): bool {
            return FileHelper::createDirectory($path);
        }
    }

    if (!function_exists('hashPassword')) {
        function hashPassword(string $password): string {
            return SecurityHelper::hashPassword($password);
        }
    }

    if (!function_exists('verifyPassword')) {
        function verifyPassword(string $password, string $hash): bool {
            return SecurityHelper::verifyPassword($password, $hash);
        }
    }

    if (!function_exists('createPassword')) {
        function createPassword(int $length = 12): string {
            return SecurityHelper::createPassword($length);
        }
    }