<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Security;
use App\Models\Counter;
use App\Models\CounterEntry;
use App\Models\CounterInstance;
use App\Models\CounterItem;
use App\Models\CounterSection;
use App\Models\User;

class CounterController extends Controller
{
    // -------------------------------------------------------------------------
    // Liste
    // -------------------------------------------------------------------------

    public function index(Request $request): void
    {
        Security::requireAuth();

        $model = new Counter();
        $counters = $model->all();

        $this->render('counters/index', [
            'title'      => 'Compteurs',
            'counters'   => $counters,
            'user'       => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Compteurs',        'url' => '/Vet_Check/public/counters'],
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // CRUD admin
    // -------------------------------------------------------------------------

    public function create(Request $request): void
    {
        Security::requireRole(['admin']);

        $this->render('counters/form', [
            'title'   => 'Nouveau compteur',
            'counter' => null,
            'action'  => 'store',
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Compteurs',        'url' => '/Vet_Check/public/counters'],
                ['label' => 'Créer',            'url' => '/Vet_Check/public/counters/create'],
            ],
        ]);
    }

    public function store(Request $request): void
    {
        Security::requireRole(['admin']);

        $name        = trim((string) $request->input('name', ''));
        $slug        = trim((string) $request->input('slug', ''));
        $description = trim((string) $request->input('description', ''));
        $eventLabel  = trim((string) $request->input('event_label', 'Événement'));

        if ($name === '' || $slug === '') {
            Flash::set('error', 'Le nom et le slug sont obligatoires.');
            header('Location: /Vet_Check/public/counters/create');
            exit;
        }

        $model = new Counter();
        $model->create([
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
            'event_label' => $eventLabel,
        ]);

        Flash::set('success', 'Compteur créé.');
        header('Location: /Vet_Check/public/counters');
        exit;
    }

    public function edit(Request $request): void
    {
        Security::requireRole(['admin']);

        $id    = (int) $request->input('id', 0);
        $model = new Counter();
        $counter = $model->findById($id);

        if (!$counter) {
            Flash::set('error', 'Compteur introuvable.');
            header('Location: /Vet_Check/public/counters');
            exit;
        }

        $sectionModel = new CounterSection();
        $sections = $sectionModel->findByCounterId($id);

        $itemModel = new CounterItem();
        $items = $itemModel->findByCounterId($id);

        $this->render('counters/form', [
            'title'    => 'Modifier le compteur',
            'counter'  => $counter,
            'sections' => $sections,
            'items'    => $items,
            'action'   => 'update',
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Compteurs',        'url' => '/Vet_Check/public/counters'],
                ['label' => 'Modifier',         'url' => '/Vet_Check/public/counters/edit?id=' . $id],
            ],
        ]);
    }

    public function update(Request $request): void
    {
        Security::requireRole(['admin']);

        $id          = (int) $request->input('id', 0);
        $name        = trim((string) $request->input('name', ''));
        $slug        = trim((string) $request->input('slug', ''));
        $description = trim((string) $request->input('description', ''));
        $eventLabel  = trim((string) $request->input('event_label', 'Événement'));

        if ($name === '' || $slug === '') {
            Flash::set('error', 'Le nom et le slug sont obligatoires.');
            header('Location: /Vet_Check/public/counters/edit?id=' . $id);
            exit;
        }

        $model = new Counter();
        $model->update($id, [
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
            'event_label' => $eventLabel,
        ]);

        Flash::set('success', 'Compteur mis à jour.');
        header('Location: /Vet_Check/public/counters');
        exit;
    }

    public function delete(Request $request): void
    {
        Security::requireRole(['admin']);

        $id = (int) $request->input('id', 0);
        $model = new Counter();
        $model->delete($id);

        Flash::set('success', 'Compteur supprimé.');
        header('Location: /Vet_Check/public/counters');
        exit;
    }

    // -------------------------------------------------------------------------
    // Sections
    // -------------------------------------------------------------------------

    public function sectionStore(Request $request): void
    {
        Security::requireRole(['admin']);

        $counterId = (int) $request->input('counter_id', 0);
        $title     = trim((string) $request->input('title', ''));
        $sortOrder = (int) $request->input('sort_order', 0);

        if ($title === '') {
            Flash::set('error', 'Le titre de la section est requis.');
            header('Location: /Vet_Check/public/counters/edit?id=' . $counterId);
            exit;
        }

        $model = new CounterSection();
        $model->create([
            'counter_id' => $counterId,
            'title'      => $title,
            'sort_order' => $sortOrder,
        ]);

        Flash::set('success', 'Section ajoutée.');
        header('Location: /Vet_Check/public/counters/edit?id=' . $counterId);
        exit;
    }

    // -------------------------------------------------------------------------
    // Items
    // -------------------------------------------------------------------------

    public function itemStore(Request $request): void
    {
        Security::requireRole(['admin']);

        $counterId = (int) $request->input('counter_id', 0);
        $sectionId = $request->input('section_id') ? (int) $request->input('section_id') : null;
        $title     = trim((string) $request->input('title', ''));
        $sortOrder = (int) $request->input('sort_order', 0);

        if ($title === '') {
            Flash::set('error', 'Le titre de l\'événement est requis.');
            header('Location: /Vet_Check/public/counters/edit?id=' . $counterId);
            exit;
        }

        $model = new CounterItem();
        $model->create([
            'counter_id' => $counterId,
            'section_id' => $sectionId,
            'sort_order' => $sortOrder,
            'title'      => $title,
        ]);

        Flash::set('success', 'Événement ajouté.');
        header('Location: /Vet_Check/public/counters/edit?id=' . $counterId);
        exit;
    }

    public function itemUpdate(Request $request): void
    {
        Security::requireRole(['admin']);

        $itemId    = (int) $request->input('id', 0);
        $sectionId = $request->input('section_id') ? (int) $request->input('section_id') : null;
        $title     = trim((string) $request->input('title', ''));
        $sortOrder = (int) $request->input('sort_order', 0);

        if ($title === '') {
            Flash::set('error', 'Le titre est requis.');
            header('Location: /Vet_Check/public/counters');
            exit;
        }

        $model = new CounterItem();
        $item  = $model->findById($itemId);
        if (!$item) {
            Flash::set('error', 'Événement introuvable.');
            header('Location: /Vet_Check/public/counters');
            exit;
        }

        $model->update($itemId, [
            'section_id' => $sectionId,
            'title'      => $title,
            'sort_order' => $sortOrder,
        ]);

        Flash::set('success', 'Événement mis à jour.');
        header('Location: /Vet_Check/public/counters/edit?id=' . (int) $item['counter_id']);
        exit;
    }

    public function itemDelete(Request $request): void
    {
        Security::requireRole(['admin']);

        $itemId = (int) $request->input('id', 0);
        $model  = new CounterItem();
        $item   = $model->findById($itemId);

        if ($item) {
            $model->delete($itemId);
            Flash::set('success', 'Événement supprimé.');
            header('Location: /Vet_Check/public/counters/edit?id=' . (int) $item['counter_id']);
            exit;
        }

        Flash::set('error', 'Événement introuvable.');
        header('Location: /Vet_Check/public/counters');
        exit;
    }

    // -------------------------------------------------------------------------
    // Exécution (comptage)
    // -------------------------------------------------------------------------

    public function run(Request $request): void
    {
        Security::requireAuth();

        $counterId = (int) $request->input('id', 0);
        $date      = (string) $request->input('date', date('Y-m-d'));

        $counterModel = new Counter();
        $counter = $counterModel->findById($counterId);
        if (!$counter) {
            Flash::set('error', 'Compteur introuvable.');
            header('Location: /Vet_Check/public/counters');
            exit;
        }

        $instanceModel = new CounterInstance();
        $instance = $instanceModel->getOrCreate($counterId, $date, (int) (Auth::user()['id'] ?? 0));

        $itemModel = new CounterItem();
        $items = $itemModel->findByCounterId($counterId);

        $entryModel    = new CounterEntry();
        $dailyCountsMap = $entryModel->getDailyCountsMap((int) $instance['id']);
        $totalCountsMap = $entryModel->getTotalCountsMap($counterId);
        $dailyEntries   = $entryModel->getEntriesByInstance((int) $instance['id']);

        $entriesByItem = [];
        foreach ($dailyEntries as $entry) {
            $entriesByItem[(int) $entry['item_id']][] = $entry;
        }

        $this->render('counters/run', [
            'title'          => 'Compteur : ' . htmlspecialchars($counter['name'], ENT_QUOTES, 'UTF-8'),
            'counter'        => $counter,
            'instance'       => $instance,
            'items'          => $items,
            'dailyCountsMap' => $dailyCountsMap,
            'totalCountsMap' => $totalCountsMap,
            'entriesByItem'  => $entriesByItem,
            'selectedDate'   => $date,
            'user'           => Auth::user(),
            'breadcrumbs'    => [
                ['label' => 'Tableau de bord',                                         'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Compteurs',                                               'url' => '/Vet_Check/public/counters'],
                ['label' => htmlspecialchars($counter['name'], ENT_QUOTES, 'UTF-8'),   'url' => '/Vet_Check/public/counters/run?id=' . $counterId . '&date=' . urlencode($date)],
            ],
        ]);
    }

    public function addCount(Request $request): void
    {
        Security::requireAuth();

        $instanceId = (int) $request->input('instance_id', 0);
        $itemId     = (int) $request->input('item_id', 0);
        $user       = Auth::user();
        $userId     = (int) ($user['id'] ?? 0);

        if ($instanceId <= 0 || $itemId <= 0 || $userId <= 0) {
            Flash::set('error', 'Paramètres invalides.');
            header('Location: /Vet_Check/public/counters');
            exit;
        }

        $instanceModel = new CounterInstance();
        $instance = $instanceModel->findById($instanceId);
        if (!$instance) {
            Flash::set('error', 'Instance introuvable.');
            header('Location: /Vet_Check/public/counters');
            exit;
        }

        $entryModel = new CounterEntry();
        $entryModel->addCount($instanceId, $itemId, $userId);

        Flash::set('success', '+1 enregistré.');
        header('Location: /Vet_Check/public/counters/run?id=' . (int) $instance['counter_id'] . '&date=' . urlencode((string) $instance['date']));
        exit;
    }

    // -------------------------------------------------------------------------
    // Historique
    // -------------------------------------------------------------------------

    public function history(Request $request): void
    {
        Security::requireAuth();

        $filters = [
            'user_id'    => $request->input('user_id'),
            'counter_id' => $request->input('counter_id'),
            'date_from'  => $request->input('date_from'),
            'date_to'    => $request->input('date_to'),
            'sort_by'    => (string) ($request->input('sort_by') ?? 'counted_at'),
            'sort_dir'   => strtolower((string) ($request->input('sort_dir') ?? 'desc')),
        ];

        $entryModel = new CounterEntry();
        $rows = $entryModel->history($filters);

        $userModel    = new User();
        $users        = $userModel->all();
        $counterModel = new Counter();
        $counters     = $counterModel->all();

        $chartUrl = '/Vet_Check/public/counters/chart?' . http_build_query([
            'user_id'    => $filters['user_id']    ?? '',
            'counter_id' => $filters['counter_id'] ?? '',
            'date_from'  => $filters['date_from']  ?? '',
            'date_to'    => $filters['date_to']    ?? '',
        ]);

        $this->render('counters/history', [
            'title'    => 'Historique des comptages',
            'rows'     => $rows,
            'users'    => $users,
            'counters' => $counters,
            'filters'  => $filters,
            'chartUrl' => $chartUrl,
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Compteurs',        'url' => '/Vet_Check/public/counters'],
                ['label' => 'Historique',       'url' => '/Vet_Check/public/counters/history'],
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // Graphiques
    // -------------------------------------------------------------------------

    public function chart(Request $request): void
    {
        Security::requireAuth();

        $filters = [
            'user_id'    => $request->input('user_id'),
            'counter_id' => $request->input('counter_id'),
            'date_from'  => $request->input('date_from'),
            'date_to'    => $request->input('date_to'),
        ];

        $entryModel = new CounterEntry();
        $byItem = $entryModel->countsByItem($filters);
        $byDay  = $entryModel->countsByDay($filters);
        $byUser = $entryModel->countsByUser($filters);

        $userModel    = new User();
        $users        = $userModel->all();
        $counterModel = new Counter();
        $counters     = $counterModel->all();

        $historyUrl = '/Vet_Check/public/counters/history?' . http_build_query([
            'user_id'    => $filters['user_id']    ?? '',
            'counter_id' => $filters['counter_id'] ?? '',
            'date_from'  => $filters['date_from']  ?? '',
            'date_to'    => $filters['date_to']    ?? '',
        ]);

        $this->render('counters/chart', [
            'title'          => 'Graphiques — Compteurs',
            'byItem'         => $byItem,
            'byDay'          => $byDay,
            'byUser'         => $byUser,
            'users'          => $users,
            'counters'       => $counters,
            'filters'        => $filters,
            'historyUrl'     => $historyUrl,
            'includeChartJs' => true,
            'breadcrumbs'    => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Compteurs',        'url' => '/Vet_Check/public/counters'],
                ['label' => 'Historique',       'url' => $historyUrl],
                ['label' => 'Vue graphique',    'url' => '/Vet_Check/public/counters/chart?' . http_build_query($filters)],
            ],
        ]);
    }
}
