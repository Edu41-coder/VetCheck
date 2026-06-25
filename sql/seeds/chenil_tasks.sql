-- Seed data for 'chenil' checklist and its tasks (transcribed from provided image)

-- Roles (id fixed for seed simplicity)
INSERT INTO roles (id, name) VALUES
(1, 'admin'),
(2, 'veto'),
(3, 'asv')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Checklist: chenil
INSERT INTO checklists (id, slug, name, description) VALUES
(1, 'chenil', 'Chenil', 'Tâches quotidiennes du chenil')
ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description);
-- Sections for 'Chenil'
INSERT INTO checklist_sections (checklist_id, title, sort_order) VALUES
(1, 'Le matin avant d\'ouvrir', 1),
(1, 'Avant de partir le midi', 2),
(1, 'Avant de partir le soir', 3)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Retrieve section ids (assuming no DB execution here; seed ordering uses known ids if empty DB)
-- For portability in SQL seeds, we insert tasks referencing the section row order via subqueries.

INSERT INTO checklist_tasks (checklist_id, section_id, sort_order, title) VALUES
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Le matin avant d\'ouvrir' LIMIT 1), 1, 'Aller voir les animaux en hospitalisation et ceux de chirurgie de la veille'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Le matin avant d\'ouvrir' LIMIT 1), 2, 'Ouvrir les volets du chenil puis allumer la radio et l\'écho'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Le matin avant d\'ouvrir' LIMIT 1), 3, 'Mettre les vaccins et vérifier la température des petits frigos (en été)'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Le matin avant d\'ouvrir' LIMIT 1), 4, 'Ouvrir les volets en consultation, descendre les chaises et faire le plein'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Le matin avant d\'ouvrir' LIMIT 1), 5, 'Retirer le cathéter de ceux qui sortent le matin'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Le matin avant d\'ouvrir' LIMIT 1), 6, 'Faire le plein du chariot consultation et hospitalisation chat'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Le matin avant d\'ouvrir' LIMIT 1), 7, 'Donner des nouvelles aux propriétaires des animaux en hospitalisation'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le midi' LIMIT 1), 8, 'Mettre les cages au propre, gamelles et litières propres et rangées'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le midi' LIMIT 1), 9, 'Fermer la porte sur le côté entre les consultation'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le midi' LIMIT 1), 10, 'Éteindre les lumières en pharmacie, chenil et radio'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le midi' LIMIT 1), 11, 'Noter sur la fiche hospitalisation et GMVET tout ce qui a été facturé'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le soir' LIMIT 1), 12, 'Éteindre radio, écho, vérifier que les sondes soient propres et que le gel écho soit plein'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le soir' LIMIT 1), 13, 'Fermer les volets chenil, les portes ainsi (celle du milieu surtout)'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le soir' LIMIT 1), 14, 'Pied de perf et lampe chauffante rangées'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le soir' LIMIT 1), 15, 'Rien ne doit traîner (RENTRER les caillebotis)'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le soir' LIMIT 1), 16, 'Ranger les vaccins et les remplir et fermer les volets en consultation'),
(1, (SELECT id FROM checklist_sections WHERE checklist_id=1 AND title='Avant de partir le soir' LIMIT 1), 17, 'Noter sur la fiche hospitalisation et GMVET tout ce qui a été facturé')
;
