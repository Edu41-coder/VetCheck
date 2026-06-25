<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Security;
use App\Models\Checklist;
use App\Models\ChecklistInstance;
use App\Models\ChecklistSection;
use App\Models\ChecklistTask as TaskModel;
use App\Models\TaskCheck;
use App\Models\User;

class ChecklistController extends Controller
{
    public function index(Request $request): void
    {
        Security::requireAuth();

        $model = new Checklist();
        $checklists = $model->all();

        $this->render('checklists/index', [
            'title' => 'Checklists',
            'checklists' => $checklists,
            'user' => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Checklists', 'url' => '/Vet_Check/public/checklists'],
            ],
        ]);
    }

    public function create(Request $request): void
    {
        Security::requireRole(['admin']);

        $this->render('checklists/form', [
            'title' => 'Nouvelle checklist',
            'checklist' => null,
            'action' => 'store',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Checklists', 'url' => '/Vet_Check/public/checklists'],
                ['label' => 'Créer', 'url' => '/Vet_Check/public/checklists/create'],
            ],
        ]);
    }

    public function store(Request $request): void
    {
        Security::requireRole(['admin']);

        $name = trim((string) $request->input('name', ''));
        $slug = trim((string) $request->input('slug', ''));
        $description = trim((string) $request->input('description', ''));

        if ($name === '' || $slug === '') {
            Flash::set('error', 'Le nom et le slug sont obligatoires.');
            header('Location: /Vet_Check/public/checklists/create');
            exit;
        }

        $model = new Checklist();
        $model->create([ 'name' => $name, 'slug' => $slug, 'description' => $description ]);

        Flash::set('success', 'Checklist créée.');
        header('Location: /Vet_Check/public/checklists');
        exit;
    }

    public function edit(Request $request): void
    {
        Security::requireRole(['admin']);

        $id = (int) $request->input('id', 0);
        $model = new Checklist();
        $checklist = $model->findById($id);

        if (!$checklist) {
            Flash::set('error', 'Checklist introuvable.');
            header('Location: /Vet_Check/public/checklists');
            exit;
        }

        $sectionModel = new ChecklistSection();
        $sections = $sectionModel->findByChecklistId($id);

        $taskModel = new TaskModel();
        $tasks = $taskModel->findByChecklistId($id);

        $this->render('checklists/form', [
            'title' => 'Modifier checklist',
            'checklist' => $checklist,
            'sections' => $sections,
            'tasks' => $tasks,
            'action' => 'update',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Checklists', 'url' => '/Vet_Check/public/checklists'],
                ['label' => 'Modifier', 'url' => '/Vet_Check/public/checklists/edit?id=' . $id],
            ],
        ]);
    }

    public function update(Request $request): void
    {
        Security::requireRole(['admin']);

        $id = (int) $request->input('id', 0);
        $name = trim((string) $request->input('name', ''));
        $slug = trim((string) $request->input('slug', ''));
        $description = trim((string) $request->input('description', ''));

        if ($name === '' || $slug === '') {
            Flash::set('error', 'Le nom et le slug sont obligatoires.');
            header('Location: /Vet_Check/public/checklists/edit?id=' . $id);
            exit;
        }

        $model = new Checklist();
        $model->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
        ]);

        Flash::set('success', 'Checklist mise à jour.');
        header('Location: /Vet_Check/public/checklists');
        exit;
    }

    public function delete(Request $request): void
    {
        Security::requireRole(['admin']);

        $id = (int) $request->input('id', 0);
        $model = new Checklist();
        $model->delete($id);

        Flash::set('success', 'Checklist supprimée.');
        header('Location: /Vet_Check/public/checklists');
        exit;
    }

    public function taskStore(Request $request): void
    {
        Security::requireRole(['admin']);

        $checklistId = (int) $request->input('checklist_id', 0);
        $sectionId = $request->input('section_id') ? (int) $request->input('section_id') : null;
        $title = trim((string) $request->input('title', ''));
        $sortOrder = (int) $request->input('sort_order', 0);

        if ($title === '') {
            Flash::set('error', 'Le titre de la tâche est requis.');
            header('Location: /Vet_Check/public/checklists/edit?id=' . $checklistId);
            exit;
        }

        $taskModel = new TaskModel();
        $taskModel->create([
            'checklist_id' => $checklistId,
            'section_id' => $sectionId,
            'sort_order' => $sortOrder,
            'title' => $title,
            'description' => null,
        ]);

        Flash::set('success', 'Tâche ajoutée.');
        header('Location: /Vet_Check/public/checklists/edit?id=' . $checklistId);
        exit;
    }

    public function taskUpdate(Request $request): void
    {
        Security::requireRole(['admin']);

        $taskId = (int) $request->input('id', 0);
        $sectionId = $request->input('section_id') ? (int) $request->input('section_id') : null;
        $title = trim((string) $request->input('title', ''));
        $sortOrder = (int) $request->input('sort_order', 0);

        if ($title === '') {
            Flash::set('error', 'Le titre de la tâche est requis.');
            header('Location: /Vet_Check/public/checklists');
            exit;
        }

        $taskModel = new TaskModel();
        $task = $taskModel->findById($taskId);
        if (!$task) {
            Flash::set('error', 'Tâche introuvable.');
            header('Location: /Vet_Check/public/checklists');
            exit;
        }

        $taskModel->update($taskId, [
            'section_id' => $sectionId,
            'title' => $title,
            'sort_order' => $sortOrder,
            'description' => null,
        ]);

        Flash::set('success', 'Tâche mise à jour.');
        header('Location: /Vet_Check/public/checklists/edit?id=' . $task['checklist_id']);
        exit;
    }

    public function taskDelete(Request $request): void
    {
        Security::requireRole(['admin']);

        $taskId = (int) $request->input('id', 0);
        $taskModel = new TaskModel();
        $task = $taskModel->findById($taskId);

        if ($task) {
            $taskModel->delete($taskId);
            Flash::set('success', 'Tâche supprimée.');
            header('Location: /Vet_Check/public/checklists/edit?id=' . $task['checklist_id']);
            exit;
        }

        Flash::set('error', 'Tâche introuvable.');
        header('Location: /Vet_Check/public/checklists');
        exit;
    }

    public function sectionStore(Request $request): void
    {
        Security::requireRole(['admin']);

        $checklistId = (int) $request->input('checklist_id', 0);
        $title = trim((string) $request->input('title', ''));
        $sortOrder = (int) $request->input('sort_order', 0);

        if ($title === '') {
            Flash::set('error', 'Le titre de la section est requis.');
            header('Location: /Vet_Check/public/checklists/edit?id=' . $checklistId);
            exit;
        }

        $sectionModel = new ChecklistSection();
        $sectionModel->create([
            'checklist_id' => $checklistId,
            'title' => $title,
            'sort_order' => $sortOrder,
        ]);

        Flash::set('success', 'Section ajoutée.');
        header('Location: /Vet_Check/public/checklists/edit?id=' . $checklistId);
        exit;
    }

    public function run(Request $request): void
    {
        Security::requireAuth();

        $checklistId = (int) $request->input('id', 0);
        $date = (string) $request->input('date', date('Y-m-d'));

        $checklistModel = new Checklist();
        $checklist = $checklistModel->findById($checklistId);
        if (!$checklist) {
            Flash::set('error', 'Checklist introuvable.');
            header('Location: /Vet_Check/public/checklists');
            exit;
        }

        $instanceModel = new ChecklistInstance();
        $instance = $instanceModel->getOrCreate($checklistId, $date, (int) (Auth::user()['id'] ?? 0));

        $taskModel = new TaskModel();
        $tasks = $taskModel->findByChecklistId($checklistId);

        $taskCheckModel = new TaskCheck();
        $checksMap = $taskCheckModel->getChecksMapByInstance((int) $instance['id']);

        $this->render('checklists/run', [
            'title' => 'Exécution checklist',
            'checklist' => $checklist,
            'instance' => $instance,
            'tasks' => $tasks,
            'checksMap' => $checksMap,
            'selectedDate' => $date,
            'user' => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Checklists', 'url' => '/Vet_Check/public/checklists'],
                ['label' => 'Exécution', 'url' => '/Vet_Check/public/checklists/run?id=' . $checklistId . '&date=' . urlencode($date)],
            ],
        ]);
    }

    public function checkTask(Request $request): void
    {
        Security::requireAuth();

        $instanceId = (int) $request->input('instance_id', 0);
        $taskId = (int) $request->input('task_id', 0);
        $user = Auth::user();
        $userId = (int) ($user['id'] ?? 0);

        if ($instanceId <= 0 || $taskId <= 0 || $userId <= 0) {
            Flash::set('error', 'Paramètres invalides pour cocher la tâche.');
            header('Location: /Vet_Check/public/checklists');
            exit;
        }

        $instanceModel = new ChecklistInstance();
        $instance = $instanceModel->findById($instanceId);
        if (!$instance) {
            Flash::set('error', 'Instance de checklist introuvable.');
            header('Location: /Vet_Check/public/checklists');
            exit;
        }

        $taskCheckModel = new TaskCheck();
        $ok = $taskCheckModel->checkTask($instanceId, $taskId, $userId, null);

        if ($ok) {
            Flash::set('success', 'Tâche cochée.');
        } else {
            Flash::set('warning', 'Cette tâche est déjà cochée par un autre utilisateur.');
        }

        header('Location: /Vet_Check/public/checklists/run?id=' . (int) $instance['checklist_id'] . '&date=' . urlencode((string) $instance['date']));
        exit;
    }

    public function history(Request $request): void
    {
        Security::requireAuth();

        $filters = [
            'user_id' => $request->input('user_id'),
            'checklist_id' => $request->input('checklist_id'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];

        $taskCheckModel = new TaskCheck();
        $rows = $taskCheckModel->history($filters);

        $userModel = new User();
        $users = $userModel->all();

        $checklistModel = new Checklist();
        $checklists = $checklistModel->all();

        $this->render('checklists/history', [
            'title' => 'Historique',
            'rows' => $rows,
            'users' => $users,
            'checklists' => $checklists,
            'filters' => $filters,
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Checklists', 'url' => '/Vet_Check/public/checklists'],
                ['label' => 'Historique', 'url' => '/Vet_Check/public/checklists/history'],
            ],
        ]);
    }
}
