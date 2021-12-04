<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=	, initial-scale=	">
	<title></title>
	<link rel="stylesheet" type="text/css" href="./CSS/style1.css">
</head>
<body>
	<?php $result = include './undone.php';?>
	<div class="container">
	
	<table class="tab" border="2">
		<tr>
			<td colspan="4"><b>青年大学习观看率连续三次小于<span class="stress">100%</span>，班级的评优评先将<span class="stress">全部取消!</span></b></td>
		</tr>
		<tr>
			<td colspan="4">
				<b>未观看青年大学习的团员 ||</b>
				<span class="rate"><b>目前观看率：</b><?php $rate = round((100*(32-count($result))/32), 2).'%';
            if ($rate<100) {
                echo '<span class="error">'.$rate.'</span>';
            } elseif ($rate = 100) {
                echo '<span class="success">'.$rate.'</span>';
            }
            ?></span><b> || 望各位团员互相监督</b></td>
		</tr>
		<tr>
			<td>编号</td>
			<td>姓名</td>
			<td>status</td>
			<td>剩余时间</td>
		</tr>
		<?php
        $i=1;
        foreach ($result as $key => $value) {
            ?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $value; ?></td>
			<td class="status">未看</td>
			<td>
				<?php
                date_default_timezone_set('PRC');
            //先定义一个数组
            $weekarray=array("7","1","2","3","4","5","6");
            // 剩余天数
            $lastDays =  6 - $weekarray[date("w")];
            if ($lastDays >= 4 && $lastDays<=6) {
                // 当剩余天数大于等于4小于等于6时显示浅蓝色
                echo '还剩<span class="relax">'.$lastDays.'</span>天';
            } elseif ($lastDays<4) {
                // 当剩余天数小于等于3时显示红色
                echo '<span class="
				stress">还剩'.$lastDays.'天</span>';
            } ?> 
			</td>
		</tr>
		<?php $i++;
        };?>
	</table>
	<div class="send">
		<button><a href="./vendor/phpmailer/phpmailer/sendEmail.php" onclick="return confirmAct();">发送邮件</a></button>
		<p id="demo"></p>
	</div>
	</div>
	<script src="./JS/myFuncs.js"></script>
</body>
</html>					