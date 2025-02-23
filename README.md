# Backoffice PHP Symfony
 
## Documentation

### Installation 

* Copier-coller le fichier `.env` et le renommer en `.env.local` 
* Changer `DATABASE_URL`
* Changer les informations de l'objet PDO dans le service `ProductService.php` pour pouvoir exporter les produits
* `composer install`
* `php bin/console d:d:c`
* `php bin/console make:migration`
* `php bin/console d:m:migrate`
* `php bin/console d:f:l`
* `php bin/console tailwind:build`
* `symfony serve:start`

### Lancement des tests

* Afin de lancer les tests, il suffit de lancer la commande : `.\vendor\bin\phpunit tests\Service`
* Cela permet de lancer deux tests :
  * Le premier permet de tester la fonction de création d'un client se trouvant dans le service `ClientService`. Cette fonction est utilisée par la commande symfony `php bin/console add:client` qui permet d'ajouter des clients à la base de données. Le test utilise un Mock de l'EntityManager.
  * Le second teste la fonction exportCSV du service `ProductService`. Elle sert à exporter l'ensemble des produits au format CSV. Le test vérifie que la fonction renvoie bien la réponse attendue.

### Fonctionnalités
* Connexion et déconnexion
* Le backoffice permet de gérer des produits, des utilisateurs et des clients selon le rôle de l'utilisateur connecté
* Produits : ajout, modification, suppression (administrateur)
* Utilisateurs : ajout, modification, suppression (administrateur)
* Clients : ajout, modification (administrateur et gestionnaire)
* Exportation des produits dans un fichier CSV
* Importation de produits à l'aide d'un fichier CSV par ligne de commande Symfony
* Ajout de nouveau client par ligne de commande Symfony

### Autres informations

* Les prix des produits sont filtrés de base dans le tableau
* Voici une courte [vidéo](https://www.youtube.com/watch?v=MkWOs4EM_HI) de l'application.
* La vidéo de présentation ne montre pas toutes les vérifications des actions possibles selon le rôle de l'utilisateur (par ex : accès à la liste des clients par l'URL, ajout d'un utilisateur en tant que Manager...). Cependant la sécurité et les vérifications sont bels et bien implémentées dans l'application à l'aide des Voters.
