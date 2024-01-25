<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use Config\Custom_config;
use App\Models\CustomModel;

class RequestForAssistanceController extends BaseController
{
    public    $type_of_request_table    = 'type_of_request';
    public    $order_by_desc                = 'desc';
    public    $order_by_asc                 = 'asc';
    protected $request;
    protected $CustomModel;
    public $config;


    public function __construct()
    {
       $db = db_connect();
       $this->CustomModel = new CustomModel($db); 
       $this->request = \Config\Services::request();  
       $this->config                   = new Custom_config;

    }
    public function index()
    {
        if (session()->get('user_type')       == 'user') {

        $data['title']                      = 'Request For Assistance';
        $data['barangay']                   = $this->config->barangay;
        $data['employment_status']          = $this->config->employment_status;
        $data['type_of_request']            = $this->CustomModel->get_all_desc($this->type_of_request_table,'type_of_request_name',$this->order_by_desc);
        $data['type_of_transactions']       = $this->config->type_of_transactions;
        $where                              = array('user_status' => 'active','user_type' => 'user');
        $data['users']                      = $this->CustomModel->getwhere('users',$where); 
    
        return view('user/request_for_assistance/index',$data);

        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
