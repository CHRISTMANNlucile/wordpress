# client github - lecture des commits dans un repo

Test de https://github.com/KnpLabs/php-github-api

## Configuration
```
$ mkdir github_commit
$ cd github_commit/
$ git init
$ git remote add origin https://github.com/fabrice1618/github_commit.git
$ git status
$ git add .
$ git commit -m "Commit initial"
$ git push origin master

$ composer --version
Composer version 1.8.4 2019-02-11 10:52:10

$ composer require knplabs/github-api php-http/guzzle6-adapter "^1.1"

```
## Utilisation
```
$ php get_repos.php
$ php get_commit_brut.php
$ php get_commit.php
```