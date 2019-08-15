<?php

namespace Yestae\OpenApi;

/**
 * 用户中心接口类
 */
class OpenApi
{
    private $config = [];
    private $version = '1.0.0';

   
    public function __construct()
    {
        self::run();
    }

    public function run()
    {
        echo 'test';
    }