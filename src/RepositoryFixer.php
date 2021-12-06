<?php
declare(strict_types=1);

namespace Quartetcom\CircleCiImageFixer;

use Github\Client;

class RepositoryFixer
{
    public function __construct(
        private Client $githubClient,
        private array $additionalReplacements = [],
    ) {
    }

    public function fix(string $organization, string $repositoryName, string $fromImage, string $toImage): void
    {
        $contentsApi = $this->githubClient->repo()->contents();
        $contentInfo = $contentsApi->show($organization, $repositoryName, '.circleci/config.yml', 'master');
        $circleYaml = base64_decode(
            $contentInfo['content']
        );

        $updated = str_replace('image: '.$fromImage, 'image: '.$toImage, $circleYaml);
        foreach ($this->additionalReplacements as $before => $after) {
            $updated = str_replace($before, $after, $updated);
        }

        $refApi = $this->githubClient->git()->references();
        $masterRef = $refApi->show($organization, $repositoryName, 'heads/master');
        $branchName = 'fix-circleci';
        $refApi->create($organization, $repositoryName, [
            'ref' => 'refs/heads/' . $branchName,
            'sha' => $masterRef['object']['sha'],
        ]);

        $commitMessage = implode('→', [$fromImage, $toImage]);
        $contentsApi->update($organization, $repositoryName, '.circleci/config.yml', $updated, $commitMessage, $contentInfo['sha'], $branchName);

        $this->githubClient->pullRequests()->create($organization, $repositoryName, [
            'title' => $commitMessage,
            'body' => 'refs https://github.com/quartetcom/general/issues/349',
            'base' => 'master',
            'head' => $branchName,
        ]);
    }
}
