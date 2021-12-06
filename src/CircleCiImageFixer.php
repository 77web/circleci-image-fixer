<?php

declare(strict_types=1);

namespace Quartetcom\CircleCiImageFixer;

class CircleCiImageFixer
{
    public function __construct(
        private RepositoryFetcher $fetcher,
        private RepositoryChecker $checker,
        private RepositoryFixer $fixer,
    ) {
    }

    public function execute(string $organization, string $fromImage, string $toImage): int
    {
        $fixed = 0;
        foreach ($this->fetcher->fetch($organization) as $repo) {
            if ($this->checker->check($organization, $repo['name'], $fromImage)) {
                $this->fixer->fix($organization, $repo['name'], $fromImage, $toImage);
                $fixed++;
            }
        }

        return $fixed;
    }
}
