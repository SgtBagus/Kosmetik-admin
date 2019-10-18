<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Onlineuser extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['page_name'] = "Pembeli Online";
        $this->template->load('template/template', 'onlineuser/index', $data);
    }
    
    public function json()
    {
        $status = $_GET['status'];
        if($status==''){
            $status = 'ENABLE';
        }
        header('Content-Type: application/json');

        $this->datatables->select('a.id as id,c.url as url, a.name as name,a.email as email,a.phone as phone, a.status as status');
        $this->datatables->where('a.status',$status);
        $this->datatables->join('file c', "c.table_id=a.id AND c.table = 'tbl_user'", 'left');
        $this->datatables->from('tbl_user a');
        echo $this->datatables->generate();
    }

    public function status($id, $status)
    {
        $this->mymodel->updateData('m_produk', array('status' => $status), array('idProduk' => $id));
        redirect(base_url('produk'));
    }
}