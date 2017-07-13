<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Request Data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <div class="body-content">
		
		<h1><?= Html::encode($this->title) ?></h1>
		<div id="hasil"></div>

    </div>
</div>

<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery-3.1.1.min.js"></script>

<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/md5.js"></script>
<script type="text/javascript">
	function getNewAccessToken()
	{
		$.ajax({
			url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/identifikasi/<?php echo $url_token;?>/<?php echo $unm_token;?>/<?php echo $seq_num;?>',
			type: 'POST',
			dataType: 'json',
			success: function(data){
				var n1 = data.info.n.n1;
				var n2 = data.info.n.n2;
				var n3 = data.info.n.n3;
				var n4 = data.info.n.n4;
				$.ajax({
					url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/saveseqnum',
					method: 'GET',
					dataType: 'json',
					data: 'seq_num=<?php echo $seq_num+1;?>',
					success: function(data){
						$.ajax({
							url: 'http://192.168.182.1/tesis/webservice/client/web/index.php/auth/gettokenhash',
							method: 'GET',
							dataType: 'json',
							data: 'n1='+ n1+'&n2='+ n2 +'&n3='+ n3+'&n4='+ n4,
							success: function(data){
								var hash_value = data.hash_value;
								var url_token = data.url_token;
								var unm_token = data.unm_token;
								var seq_num = data.seq_num;
								
								$.ajax({
									url: 'http://192.168.182.136/authenticationserver/authenticater/web/index.php/auth/autentikasi/'+url_token+'/'+unm_token+'/'+seq_num,
									method: 'POST',
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
												//location.reload(true);
											},
											error: function (jqXHR, textStatus, errorThrown) {
											}
										});
									},
									error: function (jqXHR, textStatus, errorThrown) {
										alert('Proses autentikasi gagal.\n'+jqXHR.responseText);
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
				alert('Proses identifikasi gagal.\n'+jqXHR.responseText);
			}
		});
	}
	
	function requestData()
	{	
		$.ajax({
			url: 'http://192.168.182.143/resourceserver/web/index.php/data/<?php echo $url_token;?>/<?php echo $unm_token;?>/<?php echo $seq_num;?>'+'?XDEBUG_PROFILE=1',
			headers: {
				'X-Token':'<?php echo $access_token;?>'
			},
			method: 'GET',
			crossDomain: true,
			dataType: 'json',
			success: function(data){
				var mydata = eval(data);
				var table = $.makeTable(mydata);
				$(table).appendTo("#hasil");
			},
			error: function (jqXHR, textStatus, errorThrown) {				
				if(jqXHR.status = 403) getNewAccessToken();
			}
			
		});
	}

	$(document).ready(function(){
		requestData();
		
		$.makeTable = function (mydata) {
			var table = $('<table class="table">');
			var tblHeader = "<tr>";
			for (var k in mydata[0]) tblHeader += "<th>" + k + "</th>";
			tblHeader += "</tr>";
			$(tblHeader).appendTo(table);
			$.each(mydata, function (index, value) {
				var TableRow = "<tr>";
				$.each(value, function (key, val) {
					TableRow += "<td>" + val + "</td>";
				});
				TableRow += "</tr>";
				$(table).append(TableRow);
			});
			return ($(table));
		};
	});
</script>