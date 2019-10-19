<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Transaksi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['page_name'] = "Data Transaksi";

        $data['invoice'] = $this->mymodel->selectData('transaksi');
        foreach ($data['invoice'] as $row) {
            if ($row['transaksiEXP'] <= date("Y-m-d H:i:s")) {
                $dt['statusTransaksi'] = 'EXPIRED';
                $this->mymodel->updateData('transaksi', $dt, array('idTransaksi' => $row['idTransaksi']));
            }
        }

        $this->template->load('template/template', 'transaksi/index', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        $this->datatables->select('a.idTransaksi as idTransaksi, b.name as idUser, c.name as idAdmin, a.kodeTransaksi as kodeTransaksi, a.totalTransaksi as totalTransaksi,a.alamatKirim as alamatKirim, a.nama_pengirim as nama_pengirim, a.noHubungi as noHubungi, a.statusTransaksi as statusTransaksi, a.catatan as catatan, a.status as status');
        $this->datatables->join('tbl_user b', 'a.idUser = b.id', 'left');
        $this->datatables->join('user c', 'a.idAdmin = c.id', 'left');
        $this->datatables->from('transaksi a');
        $this->datatables->add_column('view', '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" onclick="view($1)"><i class="fa fa-eye"></i> View</button><button type="button" onclick="hapus($1)" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> Hapus</button></div>', 'idTransaksi');

        echo $this->datatables->generate();
    }

    public function view($id)
    {
        $data['page_name'] = "Data Transaksi";
        $data['transaksi'] = $this->mymodel->selectDataone('transaksi', array('idTransaksi' => $id));
        
		if($data['transaksi']['transaksiEXP'] <= date("Y-m-d H:i:s")){
			$dt['statusTransaksi'] = 'EXPIRED';
			$this->mymodel->updateData('transaksi', $dt, array('idTransaksi' => $data['transaksi']['idTransaksi']));
        }
        
        if ($data['transaksi']['idUser']) {
            $data['user'] = $this->mymodel->selectDataone('tbl_user', array('id' => $data['transaksi']['idUser']));
        } else {
            $data['user'] = $this->mymodel->selectDataone('user', array('id' => $data['transaksi']['idAdmin']));
        }

        $data['file_tranksaksi'] = $this->mymodel->selectDataone('file', array('table' => 'bukti_pembayaran', 'table_id' => $id));
        $data['invoice_produk'] = $this->mymodel->selectWhere('transaksi_produk', array('kodeKeranjang' => $data['transaksi']['kodeTransaksi']));
        $this->template->load('template/template', 'transaksi/view', $data);
    }

    public function delete()
    {
        // $id = $this->input->post('idTransaksi', TRUE);
        // $this->alert->alertdanger('Success Delete Data');
    }

    public function status($id, $status)
    {
        $this->mymodel->updateData('transaksi', array('status' => $status), array('idTransaksi' => $id));
        redirect(base . url('transaksi'));
    }

    public function approve($id)
    {
        $transaksi = $this->mymodel->selectDataone('transaksi', array('idTransaksi' => $id));
        $sumproduk = $this->mymodel->selectWhere('transaksi_produk', array('kodeKeranjang' => $transaksi['kodeTransaksi']));

        foreach ($sumproduk as $row) {
            $produk = $this->mymodel->selectWithQuery("SELECT COUNT(idStok) as sumStok FROM produk_stok WHERE idProduk = '" . $row['idProduk'] . "' AND statusStok = 'TERSEDIA' AND status = 'ENABLE'");
            if ($produk[0]['sumStok'] < $row['jumlahProduk']) {
                $this->alert->alertdanger("Terdapat Stock Produk Yang Tidak Mencukup!");
                return false;
            }
        }
        $data = array(
            'statusTransaksi' => 'APPROVE',
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->mymodel->updateData('transaksi', $data, array('idTransaksi' => $id));

        foreach ($sumproduk as $row) {
            $dfd = array(
                'statusTransaksi' => 'APPROVE',
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->mymodel->updateData('transaksi_produk', $dfd, array('kodeKeranjang' => $transaksi['kodeTransaksi']));

            $updateStock = $this->db->limit(intval($row['jumlahProduk']))->order_by('idStok', 'ASC')->get_where('produk_stok', array('statusStok' => 'TERSEDIA', 'idProduk' => $row['idProduk']))->result_array();

            foreach ($updateStock as $stok) {
                $dstokc = array(
                    'statusStok' => 'TERJUAL',
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->mymodel->updateData('produk_stok', $dstokc, array('idStok' => $stok['idStok']));
            }
        }
        $this->alert->alertapprove();
    }


    public function reject($id)
    {
        $transaksi = $this->mymodel->selectDataone('transaksi', array('idTransaksi' => $id));
        $sumproduk = $this->mymodel->selectWhere('transaksi_produk', array('kodeKeranjang' => $transaksi['kodeTransaksi']));

        $data = array(
            'statusTransaksi' => 'REJECT',
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->mymodel->updateData('transaksi', $data, array('idTransaksi' => $id));

        foreach ($sumproduk as $row) {
            $dfd = array(
                'statusTransaksi' => 'REJECT',
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->mymodel->updateData('transaksi_produk', $dfd, array('kodeKeranjang' => $transaksi['kodeTransaksi']));
        }

        $this->alert->alertreject();
    }

    public function sumbitImage($id)
    {
        if (!empty($_FILES['file']['name'])) {
            $dir  = "webfile/bukti/";
            $config['upload_path']          = $dir;
            $config['allowed_types']        = '*';
            $config['file_name']           = md5('smartsoftstudio') . rand(1000, 100000);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $this->alert->alertdanger($error);
            } else {
                $file = $this->upload->data();

                $data = array(
                    'name' => $file['file_name'],
                    'mime' => $file['file_type'],
                    'dir' => $dir . $file['file_name'],
                    'table' => 'bukti_pembayaran',
                    'table_id' => $id,
                    'url' => base_url() . $dir . $file['file_name'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => 'ENABLE',
                );

                $file_dir = $this->mymodel->selectDataone('file', array('table_id' => $id, 'table' => 'bukti_pembayaran'));
                if ($file_dir['name']) {
                    @unlink($file_dir['dir']);
                    $this->mymodel->updateData('file', $data, array('table_id' => $id, 'table' => 'bukti_pembayaran'));
                } else {
                    $this->mymodel->insertData('file', $data);
                }
            }
        }

        $this->alert->alertsuccess('Success Send Data');
    }

    function sumbitNote($id)
    {
        $data = array(
            'catatan' => $_POST['note'],
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->mymodel->updateData('transaksi', $data, array('idTransaksi' => $id));
        $this->alert->alertsuccess('Berhasil Tambah Catatan');
    }
}
