-- Migration: remove business role 'admin' and keep admin rights through users.is_admin
-- Run on existing database once.

START TRANSACTION;

-- Ensure business roles exist
INSERT INTO roles (name)
SELECT 'veto'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'veto');

INSERT INTO roles (name)
SELECT 'asv'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE name = 'asv');

-- Reassign users currently on role=admin to role=veto while preserving is_admin flag
UPDATE users u
JOIN roles r_admin ON r_admin.id = u.role_id AND r_admin.name = 'admin'
JOIN roles r_veto ON r_veto.name = 'veto'
SET u.role_id = r_veto.id;

-- Remove admin role entry
DELETE FROM roles WHERE name = 'admin';

COMMIT;
