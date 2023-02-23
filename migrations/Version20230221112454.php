<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221112454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE don (id INT AUTO_INCREMENT NOT NULL, id_cat_id INT DEFAULT NULL, id_ben INT NOT NULL, titre VARCHAR(20) NOT NULL, qte INT NOT NULL, type VARCHAR(30) NOT NULL, date DATE NOT NULL, id_local INT NOT NULL, INDEX IDX_F8F081D9C09A1CAE (id_cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9C09A1CAE FOREIGN KEY (id_cat_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE dont DROP FOREIGN KEY FK_7A9DC059C09A1CAE');
        $this->addSql('DROP TABLE dont');
        $this->addSql('ALTER TABLE categorie ADD nom VARCHAR(20) NOT NULL, CHANGE type_cat type_cat VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dont (id INT AUTO_INCREMENT NOT NULL, id_cat_id INT DEFAULT NULL, id_ben INT NOT NULL, titre VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, qte INT NOT NULL, date DATE NOT NULL, id_local VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_7A9DC059C09A1CAE (id_cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE dont ADD CONSTRAINT FK_7A9DC059C09A1CAE FOREIGN KEY (id_cat_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9C09A1CAE');
        $this->addSql('DROP TABLE don');
        $this->addSql('ALTER TABLE categorie DROP nom, CHANGE type_cat type_cat VARCHAR(20) NOT NULL');
    }
}
