# Le blog insoumis.
Un projet Symfony hors du commun, précurseur du web 4.0, doté de fonctionnalités coloss...
Ah non, juste un blog.

# Procédure d'installation

- Pull le projet
- Taper, au choix (dans le répertoire de l'installation) :
    - composer update
    - php composer.phar update
- Mettre à jour le schéma de la BDD :
    - php bin/console doctrine:schema:update --force
- Mettre à jour les données de test (si besoin)
    - php bin/console doctrine:fixtures:load
- Démarrer le serveur
