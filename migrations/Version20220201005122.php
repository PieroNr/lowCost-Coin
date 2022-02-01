<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201005122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, product_id_id INT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045FDE18E50B ON image (product_id_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tag_product (tag_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(tag_id, product_id))');
        $this->addSql('CREATE INDEX IDX_E17B2907BAD26311 ON tag_product (tag_id)');
        $this->addSql('CREATE INDEX IDX_E17B29074584665A ON tag_product (product_id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_product ADD CONSTRAINT FK_E17B2907BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_product ADD CONSTRAINT FK_E17B29074584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answer ADD seller_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25DF4C85EA FOREIGN KEY (seller_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DADD4A25DF4C85EA ON answer (seller_id_id)');
        $this->addSql('ALTER TABLE question ADD product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD buyer_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E881D19F2 FOREIGN KEY (buyer_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B6F7494EDE18E50B ON question (product_id_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E881D19F2 ON question (buyer_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tag_product DROP CONSTRAINT FK_E17B2907BAD26311');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_product');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494EDE18E50B');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E881D19F2');
        $this->addSql('DROP INDEX IDX_B6F7494EDE18E50B');
        $this->addSql('DROP INDEX IDX_B6F7494E881D19F2');
        $this->addSql('ALTER TABLE question DROP product_id_id');
        $this->addSql('ALTER TABLE question DROP buyer_id_id');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A25DF4C85EA');
        $this->addSql('DROP INDEX IDX_DADD4A25DF4C85EA');
        $this->addSql('ALTER TABLE answer DROP seller_id_id');
    }
}
