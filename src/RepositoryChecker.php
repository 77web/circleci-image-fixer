<?php
declare(strict_types=1);

namespace Quartetcom\CircleCiImageFixer;

/**
 * レポジトリに.circleci/config.ymlがあり、image: circleci/phpを含むかどうかチェックするクラス想定
 */
class RepositoryChecker
{
    public function check(string $repositoryName, string $fromImage): bool
    {

    }
}
