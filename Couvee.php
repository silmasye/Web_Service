<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use RestServer\Libraries\REST_Controller;

class Couvee extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
	}

	//Menampilkan data
	public function index_get()
	{
		$id = $this->get('id');
		if ($id == '') {
			$data = $this->db->get('signature')->result();
			foreach($data as $row=>$key):
				$data[]=["nameID"=>$key->nameID,
							  "name"=>$key->name,
							  "_links"=>[(object)["href"=>"order/{$key->nameID}",
										"rel"=>"order",
										"type"=>"GET"]],
							  "stock"=>$key->stock];
			endforeach;
		} else {
			$this->db->where('nameID', $id);
			$data = $this->db->get('signature')->result();
		}
		$result = [
			"took" => $_SERVER["REQUEST_TIME_FLOAT"],
			"code" => 200,
			"message" => "Response successfully",
			"data" => $data
			];
		$this->response($result, 200);
	}

	//Menambahkan data
	public function index_post()
	{
		$data = array(
			'nameID'	=> $this->post('nameID'),
			'name'	=> $this->post('name'),
			'stock'	=> $this->post('stock'),
		);
		$insert = $this->db->insert('signature', $data);
		if ($insert) {
			//$this->response($data, 200);
			$result = [
				"took" => $_SERVER["REQUEST_TIME_FLOAT"],
				"code" => 201,
				"message" => "Data has successfully added",
				"data" => $data
			];
			$this->response($result, 201);
		} else {
			$result = [
				"took" => $_SERVER["REQUEST_TIME_FLOAT"],
				"code" => 502,
				"message" => "Failed adding data",
				"data" => null
			];
			$this->response($result, 502);
		}
	}

	//Memperbarui data yang telah ada
	public function index_put()
	{
		$id = $this->put('id');
		$data = array(
			'nameID'	=> $this->put('id'),
			'name'	=> $this->put('name'),
			'stock'	=> $this->put('stock'),
		);
		$this->db->where('nameID', $id);
		$update = $this->db->update('signature', $data);
		if ($update) {
			$this->response($data, 200);
		} else {
			$this->response(array('status' => 'fail', 502));
		}
	}

	//Menghapus data customers
	public function index_delete()
	{
		$id = $this->delete('id');
		$this->db->where('nameID', $id);
		$delete = $this->db->delete('signature');
		if ($delete) {
			$this->response(array('status' => 'success'), 201);
		} else {
			$this->response(array('status' => 'fail', 502));
		}
	}
}