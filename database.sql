CREATE DATABASE IF NOT EXISTS blog;
USE blog;
CREATE TABLE users (
  id INT(255) AUTO_INCREMENT NOT NULL,
  name VARCHAR(255),
  surname VARCHAR(255),
  password VARCHAR(255),
  email VARCHAR(255),
  role VARCHAR(20),
  image VARCHAR(255),
  CONSTRAINT pk_users PRIMARY KEY(id)
)
  ENGINE = InnoDB;

CREATE TABLE categories(
  id INT(255) AUTO_INCREMENT NOT NULL,
  name VARCHAR(255),
  description TEXT,
  CONSTRAINT pk_categories PRIMARY KEY(id)
)
  ENGINE = InnoDB;

CREATE TABLE tags(
  id INT(255) AUTO_INCREMENT NOT NULL,
  name VARCHAR(255),
  description TEXT,
  CONSTRAINT pk_tags PRIMARY KEY(id)
)
  ENGINE = InnoDB;

CREATE TABLE entries(
  id INT(255) AUTO_INCREMENT NOT NULL,
  user_id INT(255) NOT NULL,
  category_id INT(255) NOT NULL,
  title VARCHAR(255),
  content TEXT,
  status VARCHAR(20),
  image VARCHAR(255),
  CONSTRAINT pk_entries PRIMARY KEY(id),
  CONSTRAINT fk_entries_users FOREIGN KEY(user_id) REFERENCES users(id),
  CONSTRAINT fk_entries_categories FOREIGN KEY(category_id) REFERENCES categories(id)
)
  ENGINE = InnoDB;

CREATE TABLE entry_tag(
  id INT(255) AUTO_INCREMENT NOT NULL,
  entry_id INT(255) NOT NULL,
  tag_id INT(255) NOT NULL,
  CONSTRAINT pk_entry_tag PRIMARY KEY(id),
  CONSTRAINT fk_entry_tag_entries FOREIGN KEY(entry_id) REFERENCES entries(id),
  CONSTRAINT fk_entry_tag_tags FOREIGN KEY(tag_id) REFERENCES tags(id)
)ENGINE = InnoDB;