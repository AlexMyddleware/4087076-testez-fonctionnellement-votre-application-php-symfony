<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220419015341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user SELECT id, username, fullname, email, avatar_url, profile_html_url, github_id, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, avatar_url VARCHAR(255) NOT NULL, profile_html_url VARCHAR(255) NOT NULL, github_id VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT \'0\' NOT NULL, roles TEXT DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, username, fullname, email, avatar_url, profile_html_url, github_id, password) SELECT id, username, fullname, email, avatar_url, profile_html_url, github_id, password FROM __temp__user');
        $this->addSql('DROP TEMPORARY TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user SELECT id, username, fullname, email, avatar_url, profile_html_url, github_id, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, avatar_url VARCHAR(255) NOT NULL, profile_html_url VARCHAR(255) NOT NULL, github_id VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT \'0\' NOT NULL)');
        $this->addSql('INSERT INTO user (id, username, fullname, email, avatar_url, profile_html_url, github_id, password) SELECT id, username, fullname, email, avatar_url, profile_html_url, github_id, password FROM __temp__user');
        $this->addSql('DROP TEMPORARY TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
