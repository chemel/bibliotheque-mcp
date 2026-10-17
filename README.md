# Bibliothèque — projet de test MCP

Ce projet est une application Symfony qui expose un petit catalogue de livres au
moyen du [Model Context Protocol (MCP)](https://modelcontextprotocol.io/). Il
sert à tester la connexion d'un client MCP à un serveur Symfony, ainsi que
l'appel d'outils et la lecture d'une ressource.

## Technologies

- PHP `>= 8.4` et Symfony `8.1`
- `symfony/mcp-bundle` pour exposer le serveur MCP
- Doctrine ORM pour stocker les livres
- SQLite par défaut, configuré avec `DATABASE_URL` dans `.env`

## Capacités MCP

Le serveur porte le nom `my-bibliotheque`. Il expose les outils suivants :

| Outil | Fonction |
| --- | --- |
| `add-book` | Ajoute un livre à partir de son titre et de son auteur. Les deux valeurs doivent être renseignées. |
| `list-books` | Renvoie tous les livres avec leur identifiant, leur titre et leur auteur. |
| `search-books` | Recherche des livres par titre ou par auteur. |
| `current-time` | Renvoie l'heure actuelle au format demandé (par défaut `Y-m-d H:i:s`), en UTC. |

La ressource `books://catalog` fournit le catalogue sous forme de données JSON.
Les outils de catalogue et cette ressource lisent les livres enregistrés en
base ; `add-book` les y ajoute.

Les implémentations se trouvent dans `src/Mcp/Tool/` et
`src/Mcp/Resource/`. L'entité `Book` et son dépôt Doctrine se trouvent dans
`src/Entity/Book.php` et `src/Repository/BookRepository.php`.

## Installation et préparation des données

Depuis la racine du dépôt :

```bash
composer install
php bin/console doctrine:schema:create
```

Le projet utilise SQLite par défaut et stocke ses bases dans `var/`. La commande
`doctrine:schema:create` crée le schéma initial dans la base de l'environnement
courant (`dev` par défaut). Elle est à exécuter une seule fois pour une base
vierge.

Pour charger les livres d'exemple (fixtures) :

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

Cette commande vide les données existantes avant de charger les exemples.

## Connexion d'un client MCP en STDIO

Pour connecter VS Code, créez le fichier `.vscode/mcp.json` à la racine du
dépôt avec cette configuration, puis adaptez le nom du serveur selon vos
besoins :

```json
{
  "servers": {
    "bibliotheque": {
      "type": "stdio",
      "command": "php",
      "args": ["bin/console", "mcp:server", "default"],
      "cwd": "${workspaceFolder}"
    }
  }
}
```

Le client lance alors le processus MCP avec `php bin/console mcp:server
default` et échange avec lui via l'entrée et la sortie standard. Le champ `cwd`
doit désigner la racine du projet. Une fois la configuration ajoutée, rechargez
la fenêtre ou les serveurs MCP dans VS Code ; les outils et la ressource doivent
apparaître dans le client.

La commande peut aussi être lancée manuellement pour vérifier qu'elle est
disponible :

```bash
php bin/console mcp:server default
```

Elle attend un échange MCP sur STDIO ; elle n'est pas destinée à afficher une
page web dans le terminal.

## Transport HTTP

La configuration MCP active également le transport HTTP sur le chemin `/mcp`.
Pour démarrer l'application en local avec Symfony CLI :

```bash
symfony serve
```

Le point d'accès MCP est alors disponible à l'adresse `http://127.0.0.1:8000/mcp`
(ou à l'adresse indiquée par Symfony CLI). Un client MCP compatible HTTP peut
se connecter à cette URL.

## Tests

Les tests unitaires des outils et de la ressource sont dans `tests/Mcp/`. Pour
les exécuter :

```bash
php bin/phpunit
```

## Configuration de la base

La variable `DATABASE_URL` de `.env` utilise SQLite par défaut. Une base ou un
serveur différent peut être configuré via `.env.local`, sans modifier les
valeurs par défaut versionnées. `compose.yaml` fournit également un service
PostgreSQL de développement, mais l'application reste configurée pour SQLite
tant que `DATABASE_URL` n'est pas remplacée.
