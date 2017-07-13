<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Sinkronisasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-sinkronisasi">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?php $form = Html::beginForm(); ?>
		<div class="form-group">
			<label class="control-label" for="">Jumlah Iterasi</label>
			<?= Html::textInput('iterasi', '50',['class'=>'form-control','style'=>'width:300px','id'=>'iterasi']); ?>
		</div>
		<div class="form-group">
			<?= Html::submitButton('Hitung Waktu', ['class' => 'btn btn-primary','id'=>'btn-submit']) ?>
		</div>
		
	<?php Html::endForm();?>
	
	<div id="hasil">
		<table id="tabel-hasil" class="table">
			<tr><th>Iterasi</th><th>Total Waktu</th></tr>
		</table>
	</div>
</div>


<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
	var time = 0;
	var i = 1;
	var unm_token = 'unm_token';
	var url_token = 'url_token';
	var seq_num = 'seq_num';
	var sync_key = '';

	function sinkronisasi()
	{
		var start_time;
		
		$.ajax({
			url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/getsynckey',
			method: 'GET',
			cache: false,  // prevent caching response
			dataType: 'json',
			beforeSend: function (request, settings) {
				start_time = performance.now();
			},
			success: function(data){
				sync_key = data.sync_key;
				
				$.ajax({
					url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/sinkronisasi',
					method: 'POST',
					data: 'synckey='+sync_key,
					dataType: 'json',
					success: function(data){
						
						$.ajax({
							url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/gettokenhashforsync',
							method: 'GET',
							data: 'n1='+ data.info.n.n1+'&n2='+ data.info.n.n2+'&n3='+ data.info.n.n3+'&n4='+ data.info.n.n4,
							success: function(data){
								var hash_value = data;
								
								$.ajax({
									url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/sinkronisasi',
									method: 'POST',
									dataType: 'json',
									data: 'hashvalue='+ hash_value+'&synckey='+sync_key,
									success: function(data){
										var seq_num = data.info.seqnum;
										
										$.ajax({
											url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/saveseqnum',
											method: 'GET',
											dataType: 'json',
											data: 'seq_num='+ seq_num,
											success: function(data){
												if(i<$('#iterasi').val())
												{
													//var end_time = new Date().getTime();
													var end_time = performance.now();
													time = time + (end_time - start_time);
													sinkronisasi()
													
													i++;
												}
												else 
												{
													var end_time = performance.now();
													time = time + (end_time - start_time);
													
													//alert(time);
													$('#tabel-hasil').append('<tr><td>'+$('#iterasi').val()+'</td><td>'+time+'</td></tr>');
												}
											},
											error: function (jqXHR, textStatus, errorThrown) {
											}
										});
									},
									error: function (jqXHR, textStatus, errorThrown) {
										// hash value invalid
										if(i<$('#iterasi').val())
										{
											//var end_time = new Date().getTime();
											var end_time = performance.now();
											time = time + (end_time - start_time);
											sinkronisasi()
											
											i++;
										}
										else 
										{
											var end_time = performance.now();
											time = time + (end_time - start_time);
											
											alert(time);
										}
									}
								})
								
							},
							error: function (jqXHR, textStatus, errorThrown) {
							}
						});
					},
					error: function (jqXHR, textStatus, errorThrown) {
						// error: sync key invalid
						if(i<$('#iterasi').val())
						{
							//var end_time = new Date().getTime();
							var end_time = performance.now();
							time = time + (end_time - start_time);
							sinkronisasi()
							
							i++;
						}
						else 
						{
							var end_time = performance.now();
							time = time + (end_time - start_time);
							
							alert(time);
						}
					}
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
		
		
	}
	
	$(document).ready(function(){
		//sinkronisasi();
		
		$('#btn-submit').click(function(){
			time = 0;
			i = 1;
			sinkronisasi();
			
			//alert('tes');
		});
	});
</script>