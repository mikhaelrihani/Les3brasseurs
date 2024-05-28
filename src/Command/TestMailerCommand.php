<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


#[AsCommand(
    name: 'app:test-mailer',
    description: 'Sends a test email.',
    hidden: false,
    aliases: ['app:test-mailer']
)]
class TestMailerCommand extends Command
{
  
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Sends a test email to verify the mailer configuration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = (new Email())
            ->from('contact@omika.fr')
            ->to('contact@omika.fr')
            ->subject('Test Email again');
            // ->text('This is a test email.');

        try {
            $this->mailer->send($email);
            $output->writeln('<info>Email sent successfully!</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to send email: ' . $e->getMessage() . '</error>');
        }

        return Command::SUCCESS;
    }
}
