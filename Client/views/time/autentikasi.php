<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Autentikasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-autentikasi">
    <div class="body-content">
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
</div>

<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery-3.1.1.min.js"></script>

<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/md5.js"></script>
<script type="text/javascript">
	var time = 0;
	var i = 1;
	var unm_token = '';
	var url_token = '';
	var seq_num = '';

	function reqIdentifikasi()
	{
		var start_time;
		
		$.ajax({
			url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/getauth',
			method: 'GET',
			cache: false,  // prevent caching response
			dataType: 'json',
			success: function(data){
				url_token = data.url_token;
				unm_token = data.unm_token;
				seq_num = data.seq_num;
				
				$.ajax({
					url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/identifikasi/'+url_token+'/'+unm_token+'/'+seq_num,
					type: 'POST',
					dataType: 'json',
					success: function(data){
						
						var next_seq_num = seq_num + 1;
						var n1 = data.info.n.n1;
						var n2 = data.info.n.n2;
						var n3 = data.info.n.n3;
						var n4 = data.info.n.n4;
						
						$.ajax({
							url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/saveseqnum',
							method: 'GET',
							cache: false,  // prevent caching response
							dataType: 'json',
							data: 'seq_num='+next_seq_num,
							success: function(data){
								
								$.ajax({
									url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/gettokenhash',
									method: 'GET',
									cache: false,  // prevent caching response
									dataType: 'json',
									data: 'n1='+ n1+'&n2='+ n2 +'&n3='+ n3+'&n4='+ n4,
									beforeSend: function (request, settings) {
										start_time = performance.now();
									},
									success: function(data){
										
										var hash_value = data.hash_value;
										var url_token = data.url_token;
										var unm_token = data.unm_token;
										var seq_num = data.seq_num;
										
										$.ajax({
											url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/autentikasi/'+url_token+'/'+unm_token+'/'+seq_num,
											method: 'POST',
											cache: false,  // prevent caching response
											dataType: 'json',
											data: 'hashvalue='+ hash_value + '',
											success: function(data){
												
												var access_token = data.info.token;
										
												$.ajax({
													url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/savetoken',
													method: 'GET',
													dataType: 'json',
													data: 'token='+ access_token,
													success: function(data){
														
														if(i<$('#iterasi').val())
														{
															//var end_time = new Date().getTime();
															var end_time = performance.now();
															time = time + (end_time - start_time);
															reqIdentifikasi();
															
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
												// autentikasi gagal
												if(i<$('#iterasi').val())
												{
													//var end_time = new Date().getTime();
													var end_time = performance.now();
													time = time + (end_time - start_time);
													reqIdentifikasi();
													
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
							}
						});
						
					},
					error: function (jqXHR, textStatus, errorThrown) {
						
					}
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
		
		
	}

	$(document).ready(function(){
		//reqIdentifikasi();
		$('#btn-submit').click(function(){
			time = 0;
			i = 1;
			reqIdentifikasi();
			
			//alert('tes');
		});
	});
</script>