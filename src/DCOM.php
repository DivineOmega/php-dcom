<?php

namespace DivineOmega\DCOM;

use Exception;

abstract class DCOM
{
    private $envPrefix = DCOM;
    private $connections = [];

    public static function getConnection($name)
    {
        if (array_key_exists($name, $this->connections)) {
            return $this->connections[$name];
        }

        $objType = self::getEnvVar($name, 'object_type');
        $dbType = self::getEnvVar($name, 'database_type');

        switch($objType) {

            case 'mysqli':

                if ($dbType!='mysql') {
                    throw new Exception('Mysqli objects only support MySQL databases. Change your database type to \'mysql\'.');
                }

                $dbHost = self::getEnvVar($name, 'database_host');
                $dbUsername = self::getEnvVar($name, 'database_username');
                $dbPassword = self::getEnvVar($name, 'database_password');
                $dbName = self::getEnvVar($name, 'database_name');

                $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

                if ($mysqli->connect_errno) {
                    throw new Exception("Failed to connect to MySQL: (".$mysqli->connect_errno.") ".$mysqli->connect_error);
                }

                self::$connections[$name] = $mysql;

                return $mysql;

                break;

            default:

                throw new Exception('Unexpected object type: \''.$objType.'\'.');

        }
    }

    private static function getEnvVar($name, $key)
    {
        $varName = strtoupper(self::$envPrefix.'_'.$name.'_'.$key);

        $value = getenv($varName);

        if (!$value) {
            throw new Exception('Empty or non-existant environment variable: \''.$varName.'\'. Please ensure it exists in your `.env` file.');
        }
    }
}