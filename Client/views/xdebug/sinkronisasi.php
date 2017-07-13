<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Tahap Sinkronisasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-sinkronisasi">

    <h1><?= Html::encode($this->title) ?></h1>

</div>


<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
	function sinkronisasi()
	{
		$.ajax({
			url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/sinkronisasi'+'?XDEBUG_PROFILE=1',
			method: 'POST',
			data: 'synckey=<?php echo $sync_key;?>',
			dataType: 'json',
			success: function(data){
				$.ajax({
					url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/gettokenhashforsync',
					method: 'GET',
					data: 'n1='+ data.info.n.n1+'&n2='+ data.info.n.n2+'&n3='+ data.info.n.n3+'&n4='+ data.info.n.n4,
					success: function(data){
						var hash_value = data;
						
						$.ajax({
							url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/sinkronisasi'+'?XDEBUG_PROFILE=1',
							method: 'POST',
							dataType: 'json',
							data: 'hashvalue='+ hash_value+'&synckey=<?php echo $sync_key;?>',
							success: function(data){
								var seq_num = data.info.seqnum;
								
								$.ajax({
									url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/saveseqnum',
									method: 'GET',
									dataType: 'json',
									data: 'seq_num='+ seq_num,
									success: function(data){
										alert('Proses sinkronisasi berhasil.');
									},
									error: function (jqXHR, textStatus, errorThrown) {
									}
								});
							},
							error: function (jqXHR, textStatus, errorThrown) {
								alert('Proses sinkronisasi gagal.\n'+jqXHR.responseText);
							}
						})
						
					},
					error: function (jqXHR, textStatus, errorThrown) {
					}
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Proses sinkronisasi gagal.\n'+jqXHR.responseText);
			}
		});
	}
	
	$(document).ready(function(){
		sinkronisasi();
	});
</script>