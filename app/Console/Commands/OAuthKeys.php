<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use ParagonIE\EasyRSA\KeyPair;

class OAuthKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:keys {--overwrite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate keys for use with oauth';

    /**
     * Execute the console command.
     *
     * @return mixed
     *
     * @throws \ParagonIE\EasyRSA\Exception\InvalidKeyException
     */
    public function handle(FilesystemManager $filesystem)
    {
        $this->output->title('OAuth Key Generation');

        $drive = $filesystem->drive();

        if ($drive->exists('keys/oauth.key') && false === $this->option('overwrite')) {
            $this->output->error('Keys already exist.');

            die(1);
        }

        $this->comment('Generating Keys');
        $keypair = KeyPair::generateKeyPair();

        $this->comment('Saving Keys');
        $drive->makeDirectory('keys');
        
        $drive->put('keys/oauth.pub', $this->crnl2nl($keypair->getPublicKey()->getKey()));
        $drive->put('keys/oauth.key', $this->crnl2nl($keypair->getPrivateKey()->getKey()));

        $this->output->success('Done');
    }

    private function crnl2nl($value)
    {
        return \str_replace("\r\n", "\n", $value);
    }
}
