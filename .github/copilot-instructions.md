ok je veux creer une application VetCheck en php (peut etre aussi un peut de javascript) pour gerer des checklist a niveau des clinqiues veterinaires
il y aura le role admin qui pourra creer des utilisateur, il y aura le role veto et asv aussi(l'admin sera aussi veterinaire mais pas tous les vetos sront des admin)
Il y aura un systeme d'authentification
il y aura des differentes checklists (ex:chenil, chirurgie), il seront associés toujours a une journée les admin pourront creer,modifier et supprimer et ils pourront aussi ajouter , modifer et suprimmer des taches
A niveau structure je vais un structure MVC basique avec modele vue et controlleur avec un systeme de routage avec un dossier core avec les classes neccessaires:database
 controller,database , autoloader,model, router, security
dans le dossier views je vais utiliser des partiels(dossier partials) reutilisables dans un main(dossier layout) :plutot un navbar adapté aux mobiles
A niveau style je veux que ça soit mobile-first et le baser sur de classes bootstrap existentes avec un style qui resemble a celui de facebook et ajouter du CSS si neccessaire
Le fonctionnement sera le suivant;
un utilisateur se connecte choisi un des checklist et il le coche(pourra cocher les cases qui n'ont pas eté cochées par autre utlisateur) de tel façon que dans le 
rendu finale on pourra voir les taches qui ont eté cochées et par quel utilisateur
les utilisateurs pourront consulters l'historique des checklists osu forme de tableau triable par utlisateur et par plage de dates
dans ce tableau je vais implementer la pagination a travers d'un composant pagination dans dossier partials
je veux trier aussi les colonnes de ce tableau a travers composant datatable.js et datatable-pagination.js
dans partials je veux aussi un composant breadcrumb.php pour implementer un fil d'arianne, et un composant pour les messages flash, flash.php
