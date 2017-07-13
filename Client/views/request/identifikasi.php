<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Tahap Identifikasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <div class="request-identifikasi">
		
		<h1><?= Html::encode($this->title) ?></h1>
		<div id="hasil"></div>

    </div>
</div>

<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
	function reqIdentifikasi()
	{
		$.ajax({
			url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/identifikasi/<?php echo $url_token;?>/<?php echo $unm_token;?>/<?php echo $seq_num;?>',
			type: 'POST',
			dataType: 'json',
			success: function(data){
				$.ajax({
					url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/saveseqnum',
					method: 'GET',
					dataType: 'json',
					data: 'seq_num=<?php echo $seq_num+1;?>',
					success: function(data){
						alert('Proses identifikasi berhasil.');
					},
					error: function (jqXHR, textStatus, errorThrown) {
					}
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Proses identifikasi gagal.\n'+jqXHR.responseText);
			}
		});
	}

	$(document).ready(function(){
		reqIdentifikasi();
	});
</script>