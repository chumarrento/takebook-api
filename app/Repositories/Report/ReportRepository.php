<?php


namespace App\Repositories\Report;


use App\Entities\Report\Report;
use App\Enums\Report\Status;
use App\Repositories\Repository;

class ReportRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Report();
    }

    public function findAll()
    {
        $data = $this->model->where('status_id', Status::ANALYZE)->paginate(15);
        return $data;
    }

    public function delete($id)
    {
        $report = $this->find($id);

        if ($report->status_id == Status::ANALYZE) {
            return false;
        }
        return parent::delete($id); // TODO: Change the autogenerated stub
    }
}
