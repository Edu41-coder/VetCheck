-- Schema SQL for VetCheck
-- Tables: roles, users, checklists, checklist_tasks, checklist_instances, task_checks

CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  is_admin TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS checklists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS checklist_sections (
  id INT AUTO_INCREMENT PRIMARY KEY,
  checklist_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (checklist_id) REFERENCES checklists(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS checklist_tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  checklist_id INT NOT NULL,
  section_id INT NULL,
  sort_order INT DEFAULT 0,
  title VARCHAR(1000) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (checklist_id) REFERENCES checklists(id) ON DELETE CASCADE,
  FOREIGN KEY (section_id) REFERENCES checklist_sections(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- A checklist instance represents a checklist used on a specific date (e.g., daily)
CREATE TABLE IF NOT EXISTS checklist_instances (
  id INT AUTO_INCREMENT PRIMARY KEY,
  checklist_id INT NOT NULL,
  date DATE NOT NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (checklist_id) REFERENCES checklists(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
  UNIQUE (checklist_id, date)
) ENGINE=InnoDB;

-- Records which user checked which task on a specific checklist instance
CREATE TABLE IF NOT EXISTS task_checks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  instance_id INT NOT NULL,
  task_id INT NOT NULL,
  user_id INT NOT NULL,
  checked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  note VARCHAR(1000) DEFAULT NULL,
  FOREIGN KEY (instance_id) REFERENCES checklist_instances(id) ON DELETE CASCADE,
  FOREIGN KEY (task_id) REFERENCES checklist_tasks(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE (instance_id, task_id, user_id)
) ENGINE=InnoDB;

-- Indexes to speed queries for history
CREATE INDEX idx_task_checks_instance ON task_checks(instance_id);
CREATE INDEX idx_task_checks_user ON task_checks(user_id);
