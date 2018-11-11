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
final class Version20181111133654 extends AbstractMigration implements ContainerAwareInterface
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

        foreach ($repo->findAll() as $textBlock) {
            $em->remove($textBlock);
        }
        $em->flush();

        $newTextBlocks = [
            'welcome' => "Bienvenue !",
            'introduction' => "Présentation du site",
            'home_textarea' => "Texte de la page d'accueil",
            'legend_blog' => "",
            'legend_photos' => "",
            'legend_access' => "",
            'legend_carpool' => "",
            'legend_accommodations' => "",
            'legend_contact' => "",
            'legend_answer' => "",
            'legend_wedding_list' => "",
            'groom.phone' => '01-23-45-67-89',
            'groom.email' => 'marie@mail.fr',
            'bride.phone' => '01-23-45-67-89',
            'bride.email' => 'mariee@mail.fr',
            'groomsparents.phone' => '01-23-45-67-89',
            'groomsparents.email' => 'marie@mail.fr',
            'bridesparents.phone' => '01-23-45-67-89',
            'bridesparents.email' => 'mariee@mail.fr',
            'witness1.name' => "Témoin 1",
            'witness1.phone' => '01-23-45-67-89',
            'witness1.email' => 'temoin@mail.fr',
            'witness2.name' => "Témoin 2",
            'witness3.name' => "Témoin 3",
            'witness4.name' => "Témoin 4",
            'witness4.phone' => '01-23-45-67-89',
            'witness4.email' => 'temoin@mail.fr',
            'witness5.name' => "Témoin 5",
            'witness5.phone' => '01-23-45-67-89',
            'witness5.email' => 'temoin@mail.fr',
            'witness6.name' => "Témoin 6",
            'witness7.name' => "Témoin 7",
            'witness8.name' => "Témoin 8",
            'witness8.phone' => '01-23-45-67-89',
            'witness8.email' => 'temoin@mail.fr',
            'witness9.name' => "Témoin 9",
            'witness10.name' => "Témoin 10",
            'witness11.name' => "Témoin 11",
            'witness12.name' => "Témoin 12",
        ];

        foreach ($newTextBlocks as $name => $content) {
            $textBlock = new TextBlock($name, $content);
            $em->persist($textBlock);
        }
        $em->flush();

    }

    public function down(Schema $schema) : void
    {

    }
}
