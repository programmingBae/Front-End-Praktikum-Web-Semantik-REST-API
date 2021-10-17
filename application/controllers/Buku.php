<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Buku extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Buku_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'buku/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'buku/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'buku/index.html';
            $config['first_url'] = base_url() . 'buku/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Buku_model->total_rows($q);
        $buku = $this->Buku_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'buku_data' => $buku,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $data['title'] = 'Buku';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();


        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('buku/buku_list', $data);
        $this->load->view('template/footer');
        //$this->load->view('buku/buku_list', $data);
    }

    public function read($id)
    {
        $row = $this->Buku_model->get_by_id($id);
        if ($row) {
            $data = array(
                'idBuku' => $row->idBuku,
                'namaBuku' => $row->namaBuku,
                'penerbit' => $row->penerbit,
                'pengarang' => $row->pengarang,
            );
            $data['title'] = 'Buku';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();


            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('buku/buku_read', $data);
            $this->load->view('template/footer');
            //$this->load->view('buku/buku_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('buku/create_action'),
            'idBuku' => set_value('idBuku'),
            'namaBuku' => set_value('namaBuku'),
            'penerbit' => set_value('penerbit'),
            'pengarang' => set_value('pengarang'),
        );
        $data['title'] = 'Buku';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();


        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('buku/buku_form', $data);
        $this->load->view('template/footer');
        //$this->load->view('buku/buku_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'namaBuku' => $this->input->post('namaBuku', TRUE),
                'penerbit' => $this->input->post('penerbit', TRUE),
                'pengarang' => $this->input->post('pengarang', TRUE),

            );

            $this->Buku_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('buku'));
        }
    }

    public function update($id)
    {
        $row = $this->Buku_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('buku/update_action'),
                'idBuku' => set_value('idBuku', $row->idBuku),
                'namaBuku' => set_value('namaBuku', $row->namaBuku),
                'penerbit' => set_value('penerbit', $row->penerbit),
                'pengarang' => set_value('pengarang', $row->pengarang),
            );
            $data['title'] = 'Buku';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();


            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('buku/buku_form', $data);
            $this->load->view('template/footer');
            //$this->load->view('buku/buku_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('idBuku', TRUE));
        } else {
            $data = array(
                'namaBuku' => $this->input->post('namaBuku', TRUE),
                'penerbit' => $this->input->post('penerbit', TRUE),
                'pengarang' => $this->input->post('pengarang', TRUE),
            );

            $this->Buku_model->update($this->input->post('idBuku', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('buku'));
        }
    }

    public function delete($id)
    {
        $row = $this->Buku_model->get_by_id($id);

        if ($row) {
            $this->Buku_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('buku'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku'));
        }
    }

    public function exportCSV ()
    {
        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM buku");
        $delimiter = ", ";
        $newline = "\r\n";
        $enclosure = '"';
        $hasilcsv = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
        $this->load->helper('file');
        if (write_file('data_saw.csv',$hasilcsv)){
            $this->load->helper('download');
            force_download('data_saw.csv', $hasilcsv);
            echo "Successful";
        } else {
            echo "Error";
        }
    }

    public function importCSV ()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('buku/importCSV'),
            'idBuku' => set_value('idBuku'),
            'namaBuku' => set_value('namaBuku'),
            'penerbit' => set_value('penerbit'),
            'pengarang' => set_value('pengarang'),
        );
        $data['title'] = 'Buku';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();


        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('buku/importCSV', $data);
        $this->load->view('template/footer');
        //$this->load->view('buku/buku_form', $data);
    }

    public function do_upload($csv){
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 100;
        if ($csv){
            $config['file_name']='data_saw.csv';
        }
        $config['overwrite']=true;
        $this->load->library('upload',$config);
        if (! $this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
        }

        else {
        $fo = fopen("./uploads/data_saw.csv","r");$i=0;
        while ($fr=fgetcsv($fo,4600,', ','"','"')){
            if ($i>0){
                echo "<br>".$fr[1];
                $data = array(
                    'idBuku' => html_escape($this->security->xss_clean($fr[0])),
                    'namaBuku' => html_escape($this->security->xss_clean($fr[1])),
                    'penerbit' => html_escape($this->security->xss_clean($fr[2])),
                    'pengarang' => html_escape($this->security->xss_clean($fr[3])),
                    'stokBuku' => html_escape($this->security->xss_clean($fr[4]))

                );
                $this->Buku_model->insert($data);
            };$i++;
        }   
        }
        fclose($fo);
        unlink("./uploads/".$config['file_name']);
        $this->session->set_flashdata('message', 'Create Record Success');
        redirect(site_url('buku'));
    }

    public function exportXML ()
    {
        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM buku");
        $config = array (
            'root' => 'root',
            'element' => 'element',
            'newline' => "\n",
            'tab' => "\t" 
        );
        $hasilxml = $this->dbutil->xml_from_result($query, $config);
        $this->load->helper('file');
        if (write_file('data_saw.xml', $hasilxml)){
            $this->load->helper('download');
            force_download('data_saw.xml', $hasilxml);
            echo "Successful";
        } else {
            echo "Failed exporting data to xml";
        }
    }
    
    public function exportJSON ()
    {
        $query = $this->db->query("SELECT * FROM buku");
        $hasilJSON = json_encode($query->result(),JSON_PRETTY_PRINT);
        $this->load->helper('file');
        if (write_file('databuku.json',$hasilJSON)){
            $this->load->helper('download');
            force_download('databuku.json',$hasilJSON);
        } else {
            echo "Failed";
        }
    }
    

    public function _rules()
    {
        $this->form_validation->set_rules('namaBuku', 'namabuku', 'trim|required');
        $this->form_validation->set_rules('penerbit', 'penerbit', 'trim|required');
        $this->form_validation->set_rules('pengarang', 'pengarang', 'trim|required');


        $this->form_validation->set_rules('idBuku', 'idBuku', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}
