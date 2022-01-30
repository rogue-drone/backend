<?php

namespace App\Command;

use App\Entity\Guild;
use App\Repository\GuildRepository;
use App\Service\Restcord;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\{
    Attribute\AsCommand,
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface,
    Style\SymfonyStyle
};

#[AsCommand(
    name: 'discord:sync-guilds',
    description: 'Synchronizes guilds info from Discord api',
)]
class DiscordSyncGuildsCommand extends Command
{
    public function __construct(
        private Restcord $restcord,
        private GuildRepository $guildRepository,
        private EntityManagerInterface $em,
        private ?string $name = null
    )
    {
        parent::__construct($name);
    }


    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $guilds = $this->restcord->getUserGuilds();

        foreach ($guilds as $guild) {
            /** @var Guild $guildEntity */
            $guildEntity = $this->guildRepository->findOneBy(['discordId' => $guild->id]);
            if ($guildEntity->getIcon() !== $guild->getIcon()) {
                $guildEntity->setIcon($guild->getIcon());
            }

            if ($guildEntity->getName() !== $guild->name) {
                $guildEntity->setName($guild->name);
            }
        }
        $this->em->flush();


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
