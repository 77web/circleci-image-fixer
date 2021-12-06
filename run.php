<?php

declare(strict_types=1);

use Github\Client as GithubClient;
use GuzzleHttp\Client as HttpClient;
use Dotenv\Dotenv;
use Quartetcom\CircleCiImageFixer\RepositoryChecker;
use Quartetcom\CircleCiImageFixer\RepositoryFetcher;
use Quartetcom\CircleCiImageFixer\RepositoryFixer;
use Quartetcom\CircleCiImageFixer\CircleCiImageFixer;

require __DIR__.'/vendor/autoload.php';

const DOTENV_DIR = __DIR__ . '/';
const DOTENV_FILE = DOTENV_DIR . '.env';
if (file_exists(DOTENV_FILE)) {
    Dotenv::createImmutable(DOTENV_DIR)->load();
}

$pat = $_ENV['GITHUB_PAT'];
$githubClient = GithubClient::createWithHttpClient(new HttpClient());
$githubClient->authenticate($pat, null, GithubClient::AUTH_ACCESS_TOKEN);
$app = new CircleCiImageFixer(
    new RepositoryFetcher($githubClient),
    new RepositoryChecker($githubClient),
    new RepositoryFixer($githubClient, ['node-browsers' => 'node']),
);
$fixed = $app->execute($_ENV['GITHUB_ORG'], $_ENV['FROM_IMAGE'], $_ENV['TO_IMAGE']);
echo 'Fixed '.$fixed.' Repositories.'.PHP_EOL;
