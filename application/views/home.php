<!DOCTYPE html>
<html>
<head>
	<title>Add To Cart</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<br>
	<h3 class="text-success text-center"><?php echo $this->session->flashdata('msg');?></h3>
	<br>
	<div class="container">
		<div class="row">
			<div class="col-lg-2">
				<select id="product" class="form-control">
					<option>Select Product</option>
					<?php foreach ($products as $product) { ?>
						<option value="<?php echo $product->id ?>"><?php echo $product->name ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-lg-1">
				<input type="text" id="price" class="form-control">
			</div>
			<div class="col-lg-1">
				<input type="text" id="color" class="form-control">
			</div>
			<div class="col-lg-1">
				<input type="text" id="unit" class="form-control">
			</div>
			<div class="col-lg-1">
				<input type="button" id="add_btn" value="+" class="btn btn-primary btn-sm">
			</div>
			<div class="col-lg-1">
				<input type="hidden" id="id" class="form-control">
			</div>
			<div class="col-lg-1">
				<input type="hidden" id="name" class="form-control">
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-lg-offset-1">
				<table class="table" id="product_table">
					<tbody></tbody>
				</table>
				<div class="form-group">
					<input id="save_btn" class="btn btn-info btn-sm" value="Add To Cart">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-lg-offset-3">
				<div id="show_data"></div>
				<input id="final_add_btn" class="btn btn-success btn-sm" value="Save Product">
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			$('#product').change(function(){
				var id = $(this).val();
				if (id !=  '') {
					$.ajax({
						url:"<?php echo base_url() ?>get-product",
						method:"post",
						data:{id:id},
						dataType:"json",
						success:function(data){
							$('#id').val(data.id);
							$('#name').val(data.name);
							$('#price').val(data.price);
							$('#color').val(data.color);
							$('#unit').val(data.unit);
						}
					})
				}
			});
			$('#add_btn').click(function(){
				var id    = $('#id').val();
				var name  = $('#name').val();
				var price = $('#price').val();
				var color = $('#color').val();
				var unit  = $('#unit').val();
				var row   ="<tr><td style='display:none;'>"+id+"</td><td>"+name+"</td><td>"+price+"</td><td>"+color+"</td><td>"+unit+"</td><td><input id='remove_btn' type='button' class='btn btn-info btn-xs' value='X'></td></tr>";
				$('#product_table tbody').append(row);
			});
			$('#save_btn').click(function(){
				var product = [];
				$('#product_table tr').each(function(row,tr){
					var sub = {
						'id' : $(tr).find('td:eq(0)').text(),
						'name' : $(tr).find('td:eq(1)').text(),
						'price' : $(tr).find('td:eq(2)').text(),
						'color' : $(tr).find('td:eq(3)').text(),
						'unit' : $(tr).find('td:eq(4)').text()
					}
					product.push(sub);
				});
				$.ajax({
					url:"<?php echo base_url() ?>add-to-cart",
					method:"post",
					data:{product:product},
					success:function(data){
						collect_cart_data();
					}
				});
			});
			function collect_cart_data(){
				$.ajax({
					url:"<?php echo base_url() ?>get-from-cart",
					method:"post",
					success:function(data){
						$('#show_data').html(data);
					}
				});
			}
			collect_cart_data()
			$('#final_add_btn').click(function(){
				$.ajax({
					url:"<?php echo base_url() ?>save-product",
					method:"post",
					success:function(){
						collect_cart_data();
					}
				})
			});
			$('#product_table tbody').on('click','#remove_btn',function(){
				$(this).parent().parent().remove();
			});
		});
	</script>
</body>
</html>