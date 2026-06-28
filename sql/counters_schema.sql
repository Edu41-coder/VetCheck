-- Module Compteurs — tables supplémentaires à importer après schema.sql

CREATE TABLE IF NOT EXISTS counters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  event_label VARCHAR(255) NOT NULL DEFAULT 'Événement',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS counter_sections (
  id INT AUTO_INCREMENT PRIMARY KEY,
  counter_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (counter_id) REFERENCES counters(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS counter_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  counter_id INT NOT NULL,
  section_id INT NULL,
  sort_order INT DEFAULT 0,
  title VARCHAR(1000) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (counter_id) REFERENCES counters(id) ON DELETE CASCADE,
  FOREIGN KEY (section_id) REFERENCES counter_sections(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Une instance par compteur par jour
CREATE TABLE IF NOT EXISTS counter_instances (
  id INT AUTO_INCREMENT PRIMARY KEY,
  counter_id INT NOT NULL,
  date DATE NOT NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (counter_id) REFERENCES counters(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
  UNIQUE (counter_id, date)
) ENGINE=InnoDB;

-- Chaque entrée = un comptage (+1). Pas de contrainte UNIQUE → accumulation possible.
-- Les entrées ne peuvent pas être supprimées.
CREATE TABLE IF NOT EXISTS counter_entries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  instance_id INT NOT NULL,
  item_id INT NOT NULL,
  user_id INT NOT NULL,
  counted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  note VARCHAR(1000) DEFAULT NULL,
  FOREIGN KEY (instance_id) REFERENCES counter_instances(id) ON DELETE CASCADE,
  FOREIGN KEY (item_id) REFERENCES counter_items(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_counter_entries_instance ON counter_entries(instance_id);
CREATE INDEX idx_counter_entries_user ON counter_entries(user_id);
CREATE INDEX idx_counter_entries_item ON counter_entries(item_id);
