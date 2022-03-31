<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220330082909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610A76ED395');
        $this->addSql('DROP INDEX IDX_8C9F3610A76ED395 ON file');
        $this->addSql('ALTER TABLE file ADD owner_file_id INT NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36101FBDFFB3 FOREIGN KEY (owner_file_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_8C9F36101FBDFFB3 ON file (owner_file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36101FBDFFB3');
        $this->addSql('DROP INDEX IDX_8C9F36101FBDFFB3 ON file');
        $this->addSql('ALTER TABLE file ADD user_id INT DEFAULT NULL, DROP owner_file_id');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8C9F3610A76ED395 ON file (user_id)');
    }
}
