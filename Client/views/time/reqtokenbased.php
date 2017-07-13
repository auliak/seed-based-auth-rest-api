<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Request Data - Token Based';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-reqdatatokenbased">
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
	function reqData()
	{
		var start_time;
		var user = '';
		var pass = '';
		var token = '';
		
		$.ajax({
			//url: 'http://10.4.3.36/client/web/index.php/auth/getauthtokenbased',
			url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/getauthtokenbased',
			method: 'GET',
			cache: false,  // prevent caching response
			dataType: 'json',
			beforeSend: function (request, settings) {
				start_time = performance.now();
			},
			success: function(data){
				user = data.user;
				pass = data.pass;
				token = data.token;
		
				$.ajax({
					//url: 'http://10.4.3.41/tokenbased/web/index.php/data',
					url: 'http://192.168.182.143/tokenbased/web/index.php/data',
					headers: {
						'X-Token':token,
					},
					method: 'GET',
					cache: false,  // prevent caching response
					data: 'user='+user+'&pass='+pass,
					crossDomain: true,
					dataType: 'json',
					success: function(data){
						var end_time = performance.now();
						time = time + (end_time - start_time);
						if(i<$('#iterasi').val())
						{
							reqData();
							i++;
						}
						else 
						{
							$('#tabel-hasil').append('<tr><td>'+$('#iterasi').val()+'</td><td>'+time+'</td></tr>');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {				
						//if(jqXHR.status = 403) getNewAccessToken();
					}
					
				});
		
					},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
	}

	$(document).ready(function(){
		$('#btn-submit').click(function(){
			time = 0;
			i = 1;
			reqData();
		});
	});
</script>