<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use Config\Custom_config;
use App\Models\CustomModel;
use App\Models\RFAModel;

class RequestForAssistanceController extends BaseController
{
    public    $type_of_request_table    = 'type_of_request';
    public    $rfa_transactions_table       = 'rfa_transactions';
    public    $order_by_desc                = 'desc';
    public    $order_by_asc                 = 'asc';
    protected $request;
    protected $CustomModel;
    protected $RFAModel;
    public $config;


    public function __construct()
    {
       $db = db_connect();
       $this->CustomModel = new CustomModel($db); 
       $this->request = \Config\Services::request();  
       $this->config                   = new Custom_config;
       $this->RFAModel = new RFAModel($db); 

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
        $data['reference_number']           = $this->get_last_ref_number();
    
    
  
        return view('user/request_for_assistance/index',$data);

        }else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

        public function get_last_ref_number(){

    

            $l = '';
            $verify = $this->CustomModel->count_all_order_by($this->rfa_transactions_table,'rfa_date_filed',$this->order_by_desc);
            if($verify) {
                if(date('Y', time()) > date('Y', strtotime($this->CustomModel->get_all_order_by($this->rfa_transactions_table,'rfa_date_filed',$this->order_by_desc)[0]->rfa_date_filed)))
                {
                    
                    $l = '001';

                }else if(date('Y', time()) < date('Y', strtotime($this->CustomModel->get_all_order_by($this->rfa_transactions_table,'rfa_date_filed',$this->order_by_desc)[0]->rfa_date_filed))){

                    $x = $this->RFAModel->get_last_ref_number_where(date('Y-m-d', time()))->getResult()[0]->number + 1;

                    $l = $this->put_zeros($x);

                }else if (date('Y', time()) === date('Y', strtotime($this->CustomModel->get_all_order_by($this->rfa_transactions_table,'rfa_date_filed',$this->order_by_desc)[0]->rfa_date_filed))) 
    
                {
                    $x = $this->RFAModel->get_last_ref_number_where(date('Y', time()))->getResult()[0]->number + 1;

                    $l = $this->put_zeros($x);
                }
            }else {

                $l = '001';

            }
            
            return $l;

        
    }




    function put_zeros($x){

        $l = '';

           if ($x  < 10) {

                        $l = '00'.$x;
                      
                    }else if($x < 100 ) {

                        $l = '0'.$x;
                       

                    }else {


                         $l = $x;
                        
                    }

                    return $l;

    }




}
