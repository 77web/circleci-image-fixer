<?php
declare(strict_types=1);

namespace Quartetcom\CircleCiImageFixer;

use Github\Client;

/**
 * レポジトリに.circleci/config.ymlがあり、image: circleci/phpを含むかどうかチェックするクラス想定
 */
class RepositoryChecker
{
    public function __construct(
        private Client $githubClient,
    ) {
    }

    public function check(string $organization, string $repositoryName, string $fromImage): bool
    {
        $contentsApi = $this->githubClient->repo()->contents();
        if (!$contentsApi->exists($organization, $repositoryName, '.circleci/config.yml', 'master')) {
            return false;
        }

        $refApi = $this->githubClient->gitData()->references();
        try {
            $refApi->show($organization, $repositoryName, 'heads/fix-circleci');
            return false;
        } catch (\Exception $e) {
            if (!str_contains('Not Found', $e->getMessage())) {
                return false;
            }
        }

        $contentInfo = $contentsApi->show($organization, $repositoryName, '.circleci/config.yml', 'master');

        return str_contains(base64_decode($contentInfo['content']), 'image: '.$fromImage);
    }
}
