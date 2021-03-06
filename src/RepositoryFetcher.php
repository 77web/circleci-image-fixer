<?php
declare(strict_types=1);

namespace Quartetcom\CircleCiImageFixer;

use Github\Client;

class RepositoryFetcher
{
    public function __construct(
        private Client $githubClient,
    ) {
    }

    public function fetch(string $organization): array
    {
        $allRepos = [];
        $page = 1;
        do {
            $res = $this->githubClient->repositories()->org($organization, ['page' => $page, 'per_page' => 100]);
            foreach ($res as $repo) {
                if ($repo['archived'] === false) {
                    $allRepos[] = $repo;
                }
            }
            $page++;
        } while(count($res) > 0);

        return $allRepos;
    }
}
