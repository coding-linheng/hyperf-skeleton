<?php

declare(strict_types=1);

namespace App\Task\Crontab;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

///**
// * Class CheckJobCrontabTask.
// * @Crontab(name="DoStaticsCrontabTask", rule="*\/1 * * * *", callback="execute", memo="定时检查未处理的日志以及其他未完成的任务")
// */
class DoStaticsCrontabTask
{
    /**
     * @Inject
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function execute()
    {
        $startTime        = time();
        $isDoneCheckArr[] = [];
        echo 'start crontab CheckJobCrontabTask' . date('Y-m-d H:i:s');
        go(function () use ($isDoneCheckArr) {
//            $accountArr       = array_column($isDoneCheckArr, null, 'wechat_id');
            $accountWeChatIds = array_column($isDoneCheckArr, 'wechat_id');
        });
    }

    //统计专辑每周热门等,在原创藏馆等筛选的时候用得到
    public function albumStatic(){
      //昨日最新
//      $data = db('waterdo')->distinct(true)->where("DateDiff(CURDATE(),from_unixtime(time,'%Y-%m-%d'))=1 AND aid<>0 AND type=1")->field('aid')->select();
//      $arr=[0];
//      foreach($data as $v){
//        $arr[]=$v['aid'];
//      }
//      db('album')	->where('id','in',$arr)->update(['yesterday'=>1]);
    }
}
