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
        return [];
    }
}
