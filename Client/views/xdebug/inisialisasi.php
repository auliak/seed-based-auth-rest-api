<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Tahap Inisialisasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-inisialisasi">

    <h1><?= Html::encode($this->title) ?></h1>
	<div id="hasil"></div>

</div>


<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
	function inisialisasi()
	{
		$.ajax({
			//url: 'http://10.4.3.37/authenticater/web/index.php/auth/inisialisasi',
			url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/inisialisasi'+'?XDEBUG_PROFILE=1',
			method: 'POST',
			data: 'urltoken=<?php echo $url_token;?>&unmtoken=<?php echo $unm_token;?>&seqnum=<?php echo $seq_num?>&initkey=<?php echo $init_key;?>',
			dataType: 'json',
			success: function(data){
				$.ajax({
					//url: 'http://10.4.3.36/client/web/index.php/auth/saveseqnum',
					url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/saveseqnum',
					method: 'GET',
					dataType: 'json',
					data: 'seq_num=1',
					success: function(data){
						alert('Proses inisialisasi berhasil.');
					},
					error: function (jqXHR, textStatus, errorThrown) {
						
					}
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Proses inisialisasi gagal.\n'+jqXHR.responseText);
			}
		});
	}

	$(document).ready(function(){
		inisialisasi();
	});
</script>