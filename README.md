GitLab-2-YouTrack-PHP-WebHook
=============================

GitLab will send request with commits information on every push and execute command on linked (#ABC-123) YouTrack issue.

Submodule requires PHP 5.4, if your PHP is 5.3 - replace at `connection.php:185` following: `$params = []` => `$params = Array()`, also you need curl php module.