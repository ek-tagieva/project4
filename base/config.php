<?php
const DB_USER = 'stage';
const DB_NAME = 'stage';
//const DB_HOST = 'localhost:33060';
const DB_HOST = 'mysql_db:3306';
const DB_PASSWORD = 'stage';
const HOST_SITE = 'http://project4.loc';

const ADMIN_IDS = [18];

function d(...$args)
{
    var_dump($args);
    die;
}