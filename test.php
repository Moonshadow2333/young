<?php

include('./funcs.php');

$page1_url = './page1/青年大学习后台管理系统_files/record.html';
$page2_url = './page2/青年大学习后台管理系统_files/record.html';
$page1_contents = file_get_contents($page1_url);
$page2_contents = file_get_contents($page2_url);
// 匹配期数；（第十一季第X期）
$no_pattarn = '/(?<=<option value="C\d{4}">).*?(?=<\/option>)/';
preg_match_all($no_pattarn, $page1_contents, $page1_matches);
$page2_no = preg_match_all($no_pattarn, $page2_contents, $page2_matches);
// page1中的期数。
$page1_no = $page1_matches[0][0];
// page2中的期数。
$page2_no = $page2_matches[0][0];
if ($page1_no === $page2_no) {
    $page1_done_users = getDoneUsers($page1_url);
    $page2_done_users = getDoneUsers($page2_url);
    $all_done_users = array_merge($page1_done_users, $page2_done_users);
	$all_done_users = solveException($all_done_users);
	$all_users_info = getAllUsersInfo();
	$all_undone_users=array_diff($all_users_info, $all_done_users);
	// var_dump($all_undone_users);
    filePutContents($all_undone_users);
} else {
	// 如果两期的内容不相同则意味着page1里面的内容是新一期的青年大学习，page2里面的内容还是上一期的青年大学习。所以只要获取新一期的内容，也就是page1里面的内容。
	$page1_done_users = getDoneUsers($page1_url);
	// 排除异常；
	$page1_done_users = solveException($page1_done_users);
	$all_users_info = getAllUsersInfo();
	$all_undone_users=array_diff($all_users_info, $page1_done_users);
	filePutContents($all_undone_users);
}
function getAllUsersInfo(){
	$all_users_info = include('info.php');
	return $all_users_info;
}
function getDoneUsers($url)
{
    $contents = file_get_contents($url);
    $pattern = '/(?<=<div class="layui-table-cell laytable-cell-\d-cardNo">\s{2}<span>).*?(?=<\/span>\s{2}<\/div>)/';
    $re = preg_match_all($pattern, $contents, $matches);
    return $matches[0];
}
function filePutContents($contents)
{
    file_put_contents('./undone.php', "<?php\r\n return ".var_export($contents, true).";\r\n");
}
function solveException($lat)
{
    // 解决异常，高如彤和徐升泽填错了信息。
    $exception = ['142181923','徐升泽/1420181940'];
    $normal = ['高如彤','徐升泽'];
    for ($i=0; $i < count($exception); $i++) {
        if (member($exception[$i], $lat)) {
            $lat = replace($normal[$i], $exception[$i], $lat);
        }
    }
	return $lat;
}

echo '<a href="./index.php">点我</a>';
