<?php
session_start();

$memcache = memcache_connect('localhost', 11211);
 
require_once 'db.php';