<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	
	public function index()
	{
		$this->load->model('product_model');
		$data['products'] = $this->product_model->get_product();
		$this->load->view('home',$data);
	}
	public function get_product()
	{
		$this->load->model('product_model');
		$product = $this->product_model->fetch_product();
		$data = array(
			'id'=>$product->id,
			'name'=>$product->name,
			'price'=>$product->price,
			'color'=>$product->color,
			'unit'=>$product->unit,
		);
		echo json_encode($data);
	}
	public function add_cart()
	{
		$this->load->library('cart');
		$product = $this->input->post('product');
		for ($i=0; $i < count($product); $i++) { 
			$data = array(
				'id'      => $product[$i]['id'],
				'qty'     => 1,
				'price'   => $product[$i]['price'],
				'name'    => $product[$i]['name'],
				'options' => array('color'=>$product[$i]['color'],'unit'=>$product[$i]['unit'])
			);

			$this->cart->insert($data);
		}
	}
	public function get_cart_data()
	{
		$this->load->library('cart');
		$collections = $this->cart->contents();
		$total_qty = $this->cart->total_items();
		$total_cost = $this->cart->total();
		$output = '';
		$output .='
             <table class="table-bordered table">
                <thead>
                    <tr>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Quantity</th>
                      <th>Color</th>
                      <th>Unit</th>
                      <th>Subtotal</th>
                    </tr>
                </thead>
		';
		foreach ($collections as $cart_content) {
			$output .='
                <tbody>
                    <tr>
                       <td>'.$cart_content['name'].'</td>
                       <td>'.$cart_content['price'].'</td>
                       <td>
                          <form method="post" action="update-qty">
                          <input class="form-control" type="hidden" name="row_id" value="'.$cart_content['rowid'].'">
                              <input class="form-control" type="number" name="qty" value="'.$cart_content['qty'].'" min="1">
                              <button type="submit" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-refresh"></span></button>
                          </form>
                       </td>
                       <td>'.$cart_content['options']['color'].'</td>
                       <td>'.$cart_content['options']['unit'].'</td>
                       <td>'.$cart_content['price']*$cart_content['qty'].'</td>
                    </tr>
                </tbody>
			';
		}
		$output .='
                <tfooter>
                      <tr>
                         <td><b>Total</b></td>
                         <td></td>
                         <td>'.$total_qty.'</td>
                         <td></td>
                         <td></td>
                         <td>'.$total_cost.'</td>
                      </tr>
                </tfooter>
		';
		$output .='</table>';
		echo $output;
	}
	public function update_qty()
	{
		$this->load->library('cart');
		$rowid = $this->input->post('row_id');
		$qty   = $this->input->post('qty');
		$data  = array(
                'rowid' => $rowid,
                'qty'   => $qty
		);
		$this->cart->update($data);
		redirect(base_url());
	}
	public function save_product()
	{
		$this->load->library('session');
		$this->load->library('cart');
		$collections = $this->cart->contents();
		$this->load->model('product_model');
		$this->product_model->save($collections);
		$msg = $this->session->set_flashdata('msg','Product Save Successfull');
		$this->cart->destroy();
		redirect(base_url());
	}
}
