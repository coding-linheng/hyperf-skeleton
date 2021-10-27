<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
use App\Services\ReportService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * 举报/投诉接口.
 */
class Report extends AbstractController
{
    #[Inject]
    protected ReportService $reportService;

    /**
     * 举报素材.
     */
    public function reportMaterial(\App\Request\Report $report): ResponseInterface
    {
        $report->scene('report_material')->validateResolved();
        $params           = $report->all();
        $params['bid']    = $params['material_id'];
        $params['uid']    = user()['id'];
        $params['type']   = 3; //1-灵感 2-文库 3-素材
        $params['status'] = 1; //1-举报 2-投诉
        $params['time']   = time();
        return $this->success($this->reportService->report($params));
    }

    /**
     * 投诉素材.
     */
    public function complaintMaterial(\App\Request\Report $report): ResponseInterface
    {
        $report->scene('complaint_material')->validateResolved();
        $params           = $report->all();
        $params['bid']    = $params['material_id'];
        $params['uid']    = user()['id'];
        $params['type']   = 3;
        $params['status'] = 2;
        $params['time']   = time();
        return $this->success($this->reportService->report($params));
    }

    /**
     * 举报文库.
     */
    public function reportLibrary(\App\Request\Report $report): ResponseInterface
    {
        $report->scene('report_library')->validateResolved();
        $params           = $report->all();
        $params['bid']    = $params['library_id'];
        $params['uid']    = user()['id'];
        $params['type']   = 2;
        $params['status'] = 1;
        $params['time']   = time();
        return $this->success($this->reportService->report($params));
    }

    /**
     * 投诉文库.
     */
    public function complaintLibrary(\App\Request\Report $report): ResponseInterface
    {
        $report->scene('complaint_library')->validateResolved();
        $params           = $report->all();
        $params['bid']    = $params['library_id'];
        $params['uid']    = user()['id'];
        $params['type']   = 2;
        $params['status'] = 2;
        $params['time']   = time();
        return $this->success($this->reportService->report($params));
    }

    /**
     * 举报灵感
     */
    public function reportAlbum(\App\Request\Report $report): ResponseInterface
    {
        $report->scene('report_album')->validateResolved();
        $params           = $report->all();
        $params['bid']    = $params['album_id'];
        $params['uid']    = user()['id'];
        $params['type']   = 1;
        $params['status'] = 1;
        $params['time']   = time();
        return $this->success($this->reportService->report($params));
    }

    /**
     * 举报封面.
     */
    public function reportImage(\App\Request\Report $report): ResponseInterface
    {
        $report->scene('complaint_library')->validateResolved();
        $params           = $report->all();
        $params['bid']    = $params['user_id'];
        $params['uid']    = user()['id'];
        $params['type']   = 4;
        $params['status'] = 1;
        $params['time']   = time();
        return $this->success($this->reportService->report($params));
    }
}
