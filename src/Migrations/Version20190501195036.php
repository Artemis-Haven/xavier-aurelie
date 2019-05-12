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
final class Version20190501195036 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE gallery ADD contest BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE gallery ADD code VARCHAR(255) DEFAULT NULL');


        $em = $this->container->get('doctrine')->getEntityManager();
        $textBlock = new TextBlock('legend_contest', '');
        $em->persist($textBlock);
        $em->flush();
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE gallery DROP contest');
        $this->addSql('ALTER TABLE gallery DROP code');
    }
}
