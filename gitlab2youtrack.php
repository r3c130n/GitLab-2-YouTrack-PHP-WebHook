<?php
/**
 * @author Mikhail Medvedev aka r3c130n <i@r3c130n.ru>
 * @link http://www.r3c130n.ru/
 * @license BSD (see LICENSE for details)
 * @date: 27.01.13
 */

$url = "http://my-youtrack-address.com";
$login = 'youtracklogin';
$password = "youtrackpassword";

# Search a given text message for a string of the form "comment #ABC-123 command"
function find_issues ($message) {
    $pattern = ('/([\s\wА-Яа-я\(\)\.\,\-\s\/]+)?#(\w+-\d+)(\s+[\s\wА-Яа-я\(\)\.\,\-\s\/]+)?/u');
	preg_match_all($pattern, $message, $matches);

	if (!empty($matches[0][0]) && !empty($matches[1][0]) && !empty($matches[2][0])) {
		return Array (
			'message' => $matches[0][0],
			'issue_id' => $matches[2][0],
			'command' => $matches[3][0],
			'comment' => $matches[1][0]
		);
	}
    return null;
}

$JSON = json_decode($HTTP_RAW_POST_DATA);
if ($JSON->total_commits_count > 0) {

	require_once("./lib/src/youtrackclient.php");
	$youtrack = new \YouTrack\Connection($url, $login, $password);

	foreach ($JSON->commits as $obCommit) {
		$commit = find_issues($obCommit->message);
		if ($commit !== null) {
			$true_issue = $youtrack->get_issue($commit['issue_id']);
			if ($true_issue) {
				$youtrack->execute_command($commit['issue_id'], $commit['command'], $commit['comment']);
			}
		}
	}
}