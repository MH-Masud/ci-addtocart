<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class product_model extends CI_Model {

	
	public function get_product()
	{
		$this->db->select('*');
		$this->db->from('products');
		$query = $this->db->get();
		$products = $query->result();
		return $products;
	}
	public function fetch_product()
	{
		$id = $this->input->post('id');
		$this->db->select('*');
		$this->db->from('products');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$product = $query->row();
		return $product;

	}
	public function save($collections)
	{
		foreach ($collections as $item) {
			$data = array(
				'name'=>$item['name'],
				'price'=>$item['price'],
				'quantity'=>$item['qty'],
				'color'=>$item['options']['color'],
				'unit'=>$item['options']['unit'],
				'subtotal'=>$item['price']*$item['qty']
			);
			$this->db->insert('sells',$data);
		}
	}
}
