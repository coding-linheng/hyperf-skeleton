<?php

return [

    /**
     * 风控相关配置
     */
    'default' => [
        'open' => env('RCP_OPEN', 1), //默认开关，默认开启
        'common_limit_count' => [
            'ip_day_limit' =>10000,  //每个ip每天限制访问接口次数
            'user_day_limit' =>10000, //每个用户限制访问次数
            'ip_uri_day_limit' => 12000,//每个ip访问同一个页面每天最多访问次数
            'user_uri_day_limit' => 1000,//每个用户访问同一个页面最多访问次数
            'limit_rate_count'=>1000, //最大并发1000
            'limit_rate_ip_count'=>30 //单个IP最大并发
        ],
        'special_rules' => [
          [
            //多个规则平行
            'A' => [ 'title' => "查看灵感原图", //标题
                  'uri' => "/v1/album/getOriginPic", //多个规则路径使用逗号隔开
                  'score' => 50,   //该规则通过占有比例分数
                  'limit_count' => 500, //该规则执行次数
                  'type' => 1,  //1为根据uri来执行，2为根据根据用户表字段名
                  'op'=>">",//规则操作符，大于小于等于，与非或，以什么开始，以什么结束等
                  'column' => 'user.reg', //用户注册时间字段
                  'cp' => '', //比较字段，注册时间小于多少，则在这里填写
                  'check_score'=>80, //总分大于该分值则可以通过
                  'remark' => '', //提示语
                  ],
            'B' => [ 'title' => "查看灵感原图", //标题
                    'uri' => "/v1/album/getOriginPic", //多个规则路径使用逗号隔开
                    'score' => 50,   //该规则通过占有比例分数
                    'limit_count' => 500, //该规则执行次数
                    'type' => 1,  //1为根据uri来执行，2为根据根据用户表字段名
                    'op'=>">",//规则操作符，大于小于等于，与非或，以什么开始，以什么结束等
                    'column' => 'user.reg', //用户注册时间字段
                    'cp' => '', //比较字段，注册时间小于多少，则在这里填写
                    'check_score'=>80, //总分大于该分值则可以通过
                    'remark' => '', //提示语
                  ],
            ],

        ],
    ],

];