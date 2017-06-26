<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use ParagonIE\EasyRSA\KeyPair;
use phpseclib\Crypt\RSA;
use phpseclib\File\X509;

class CertKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cert:keys {--overwrite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate keys for use with SAML 2.0';

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

        if ($drive->exists('keys/saml.key') && false === $this->option('overwrite')) {
            $this->output->error('Keys already exist.');

            die(1);
        }

        //

        $this->info('Generating Keys');

        $keypair = KeyPair::generateKeyPair();
        $publicKey = $keypair->getPublicKey()->getKey();
        $privateKey = $keypair->getPrivateKey()->getKey();

        //

        $this->info('Saving Keys');

        $drive->makeDirectory('keys');
        $drive->put('keys/saml.pub', $this->crnl2nl($publicKey));
        $drive->put('keys/saml.key', $this->crnl2nl($privateKey));

        //

        $this->output->newLine();

        $this->info('Generating x509 cert');

        $privKey = new RSA();
        $privKey->setPrivateKey($privateKey);

        $pubKey = new RSA();
        $pubKey->setPublicKey($publicKey);

        $subject = new X509();
        $subject->setDNProp('id-at-organizationName', config('saml.cert.orgName'));
        $subject->setPublicKey($pubKey);

        $issuer = new X509();
        $issuer->setPrivateKey($privKey);
        $issuer->setDN($subject->getDN());

        $x509 = new X509();
        $result = $x509->sign($issuer, $subject);

        $cert = $x509->saveX509($result);

        $this->info('Saving Certificate');

        $drive->put('keys/saml.crt', $this->crnl2nl($cert));

        $this->output->success('Done');
    }

    private function crnl2nl($value)
    {
        return \str_replace("\r\n", "\n", $value);
    }
}
