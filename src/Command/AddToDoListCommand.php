<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Model\Todolist\TodoListCover;
use Doctrine\DBAL\Connection;

/**
 * Description of AddToDoListCommand
 *
 * @author Ferhat KUS
 */
class AddToDoListCommand extends Command {

    protected static $defaultName = 'app:add-todo-list';
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure() {
        $this->setHelp('app:add-todo-list https://example_provider_url.com/some-argumants');
        $this->addArgument('provider',
                null,
                InputArgument::REQUIRED,
                null,
                null);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $io = new SymfonyStyle($input, $output);
        $providerUrl = $input->getArgument('provider');
        if ($providerUrl == null) {
            throw new InvalidArgumentException(
                    'Bir provider url girilmeli! for example:'
                    . $this->getHelp());
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $providerUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: json"));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        $io->success('Json providerdan alındı...');
        $output->writeln('...');
        TodoListCover::insertDBFromJSON($result, $output, $this->connection);
        $io->success('Json Veri tabanına eklendi...');
    }

}
