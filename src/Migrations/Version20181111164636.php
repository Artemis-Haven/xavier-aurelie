<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\TextBlock;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181111164636 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema) : void
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $repo = $em->getRepository('App:TextBlock');

        $textBlocksToRemove = [
            'groom.email',
            'bride.email',
            'groomsparents.phone',
            'groomsparents.email',
            'bridesparents.phone',
            'bridesparents.email',
        ];

        $newTextBlocks = [
            'email' => 'contact@mail.fr',
            'address' => '! Rue de la Paix, 75001 PARIS',
        ];

        foreach ($textBlocksToRemove as $name) {
            $textBlock = $repo->findOneByName($name);
            $em->remove($textBlock);
        }

        foreach ($newTextBlocks as $name => $content) {
            $textBlock = new TextBlock($name, $content);
            $em->persist($textBlock);
        }
        $em->flush();

    }

    public function down(Schema $schema) : void
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $repo = $em->getRepository('App:TextBlock');

        $textBlocksToRemove = [
            'email',
            'address',
        ];

        $newTextBlocks = [
            'groom.email' => "contact@mail.fr",
            'bride.email' => "contact@mail.fr",
            'groomsparents.phone' => "01-23-45-67-89",
            'groomsparents.email' => "contact@mail.fr",
            'bridesparents.phone' => "01-23-45-67-89",
            'bridesparents.email' => "contact@mail.fr",
        ];

        foreach ($textBlocksToRemove as $name) {
            $textBlock = $repo->findOneByName($name);
            $em->remove($textBlock);
        }

        foreach ($newTextBlocks as $name => $content) {
            $textBlock = new TextBlock($name, $content);
            $em->persist($textBlock);
        }
        $em->flush();
    }
}
