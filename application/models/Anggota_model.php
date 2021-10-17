<?php

use GuzzleHttp\Client;


class Anggota_model extends CI_Model
{

    public $table = 'anggota';
    public $id = 'idAnggota';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        /* $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();*/
        $client =  new Client();
        $response = $client->request('GET', 'http://localhost/perpus-rest-server/Anggota', [
            'query' => [
                'perpus-api-key' => 'perpus123'
            ]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result['data'];
    }

    // get data by id
    function get_by_id($id)
    {
        /*$this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();*/
        $client =  new Client();
        $response = $client->request('GET', 'http://localhost/perpus-rest-server/Anggota', [
            'query' => [
                'perpus-api-key' => 'perpus123',
                'idAnggota' => $id
            ]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result['data'][0];
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('idAnggota', $q);
        $this->db->or_like('namaAnggota', $q);
        $this->db->or_like('alamatAnggota', $q);
        $this->db->or_like('statusKeanggotaan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('idAnggota', $q);
        $this->db->or_like('namaAnggota', $q);
        $this->db->or_like('alamatAnggota', $q);
        $this->db->or_like('statusKeanggotaan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        //$this->db->insert($this->table, $data);
        $client =  new Client();
        $response = $client->request('POST', 'http://localhost/perpus-rest-server/Anggota', [
            'form_params' => [
                'perpus-api-key' => 'perpus123',
                'namaAnggota' => $data['namaAnggota'],
                'alamatAnggota' => $data['alamatAnggota'],
                'statusKeanggotaan' => $data['statusKeanggotaan']
            ]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result;
    }

    // update data
    function update($id, $data)
    {
        //$this->db->where($this->id, $id);
        //$this->db->update($this->table, $data);
        $client =  new Client();
        $response = $client->request('PUT', 'http://localhost/perpus-rest-server/Anggota', [
            'form_params' => [
                'perpus-api-key' => 'perpus123',
                'namaAnggota' => $data['namaAnggota'],
                'alamatAnggota' => $data['alamatAnggota'],
                'statusKeanggotaan' => $data['statusKeanggotaan'],
                'idAnggota' => $id
            ]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result;
        
    }

    // delete data
    function delete($id)
    {
        //$this->db->where($this->id, $id);
       // $this->db->delete($this->table);
       $client =  new Client();
       $response = $client->request('DELETE', 'http://localhost/perpus-rest-server/Anggota', [
           'form_params' => [
               'perpus-api-key' => 'perpus123',
               'idAnggota' => $id
           ]
       ]);
       $result = json_decode($response->getBody()->getContents(), true);
       return $result;
    }
}
