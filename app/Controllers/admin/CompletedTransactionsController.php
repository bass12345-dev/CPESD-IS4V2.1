<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomModel;
use App\Models\TransactionModel;

class CompletedTransactionsController extends BaseController
{

    private $transactions_table         = 'transactions';
    private $type_of_activity_table     = 'type_of_activities';
    private $training_table             = 'trainings';
    private $project_monitoring_table   = 'project_monitoring';
    private $activity_table             = 'type_of_activities';
    private $cso_table                  = 'cso';
    private $order_by_desc              = 'desc';
    private $order_by_asc               = 'asc';
    protected $request;
    protected $CustomModel;
    protected $TransactionModel;
    protected $db;

       public function __construct()
    {
       $this->db                       = db_connect();
       $this->CustomModel              = new CustomModel($this->db); 
       $this->TransactionModel         = new TransactionModel($this->db); 
       $this->request                  = \Config\Services::request();  
    }

    public function index()
    {
         if (session()->get('user_type') == 'admin') {

            $data['title']             = 'Completed Transaction';
            $data['activities']        = $this->CustomModel->get_all_order_by($this->activity_table,'type_of_activity_name',$this->order_by_desc);
            $data['cso']               = $this->CustomModel->get_all_order_by($this->cso_table,'cso_name',$this->order_by_asc);
            $data['rgpm_text']               = 'regular monthly project monitoring';
            return view('admin/transactions/completed/index',$data);
        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
