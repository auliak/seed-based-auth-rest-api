<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Inisialisasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-inisialisasi">

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
	var unm_token = '';
	var url_token = '';
	var seq_num = '';
	var init_key = '';
	
	function inisialisasi()
	{
		var start_time;
		
		$.ajax({
			//url: 'http://10.4.3.36/client/web/index.php/auth/getinitkey',
			url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/getinitkey',
			method: 'GET',
			cache: false,  // prevent caching response
			dataType: 'json',
			beforeSend: function (request, settings) {
				start_time = performance.now();
			},
			success: function(data){
				url_token = data.url_token;
				unm_token = data.unm_token;
				seq_num = data.seq_num;
				init_key = data.init_key;
				
				
				$.ajax({
					//url: 'http://10.4.3.37/authenticater/web/index.php/auth/inisialisasi',
					url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/inisialisasi',
					method: 'POST',
					cache: false,  // prevent caching response
					data: 'urltoken='+url_token+'&unmtoken='+unm_token+'&seqnum='+seq_num+'&initkey='+init_key,
					//dataType: 'json',
					success: function(data){
						$.ajax({
							//url: 'http://10.4.3.36/client/web/index.php/auth/saveseqnum',
							url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/saveseqnum',
							method: 'GET',
							cache: false,  // prevent caching response
							dataType: 'json',
							data: 'seq_num=0',
							success: function(data){
								//var end_time = new Date().getTime();
								var end_time = performance.now();
								time = time + (end_time - start_time);
								
								if(i<$('#iterasi').val())
								{
									$.ajax({
										//url: 'http://10.4.3.37/authenticater/web/test.php/statusinisialisasi',
										url: 'http://192.168.182.136/authenticationserver/authenticater/web/test.php/statusinisialisasi',
										method: 'GET',
										dataType: 'json',
										success: function(data){
											inisialisasi();
											i++;
											
										},
										error: function (jqXHR, textStatus, errorThrown) {
											inisialisasi();
											i++;
										}
									});
									
									
									
								}
								else 
								{	
									$.ajax({
										//url: 'http://10.4.3.37/authenticater/web/test.php/statusinisialisasi',
										url: 'http://192.168.182.136/authenticationserver/authenticater/web/test.php/statusinisialisasi',
										method: 'GET',
										dataType: 'json',
										success: function(data){
											//alert(time);
											$('#tabel-hasil').append('<tr><td>'+$('#iterasi').val()+'</td><td>'+time+'</td></tr>');
										},
										error: function (jqXHR, textStatus, errorThrown) {
											//alert(time);
											$('#tabel-hasil').append('<tr><td>'+$('#iterasi').val()+'</td><td>'+time+'</td></tr>');
										}
									});
									
									
								}
							},
							error: function (jqXHR, textStatus, errorThrown) {
							}
						});
					},
					error: function (jqXHR, textStatus, errorThrown) {
						// error proses inisialisasi
						
						//var end_time = new Date().getTime();
						var end_time = performance.now();
						time = time + (end_time - start_time);
						if(i<$('#iterasi').val())
						{
							inisialisasi();
							
							i++;
						}
						else 
						{	
							alert(time);
						}
					}
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
	}
	
	function stateAwal()
	{
		$.ajax({
			//url: 'http://10.4.3.37/authenticater/web/test.php/statusinisialisasi',
			url: 'http://192.168.182.136/authenticationserver/authenticater/web/test.php/statusinisialisasi',
			method: 'GET',
			dataType: 'json',
			success: function(data){
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
	}

	$(document).ready(function(){
		$('#btn-submit').click(function(){
			time = 0;
			i = 1;
			inisialisasi();
			//stateAwal();
		});
	});
</script>