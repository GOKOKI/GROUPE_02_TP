# Système de Gestion de Secrétariat 🖥️

Une application pour la gestion administrative des documents de soutenance et des dossiers des professeurs.

## Installation Rapide

Suivez ces étapes simples pour démarrer le projet sur votre machine :

### 1. Installation
Clonez le projet et installez les dépendances :

```bash
# Installation des dépendances PHP
composer install

# Installation des dépendances JavaScript
npm install
```

### 2. Configuration
Configurez votre environnement :

```bash
# Copiez le fichier d'exemple
cp .env.example .env

# Générez la clé d'application
php artisan key:generate
```

Modifiez le fichier `.env` pour configurer votre base de données (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

### 3. Base de données
Préparez la base de données :

```bash
# Lancez les migrations et les seeders
php artisan migrate --seed
```

### 4. Lancement
Démarrez les serveurs :

```bash
# Serveur Laravel
php artisan serve

# Compilation des assets (dans un autre terminal)
npm run dev
```

Accédez à l'application via `http://localhost:8000`.

---

## 📊 Comprendre les Relations de la Base de Données

👉 **[Voir le diagramme interactif de la base de données](https://dbdiagram.io/d/Soutenance-691e4174228c5bbc1aa07c61)**

Voici une explication simplifiée pour vous aider à naviguer dans les données :

### 1. Utilisateurs & Rôles
*   **Users** : C'est la table centrale pour l'authentification.
*   Chaque `User` a un rôle spécifique : **Admin**, **Professeur** ou **Étudiant**.
*   Les tables `professors` et `students` sont liées à la table `users` (clé étrangère `user_id`) pour étendre les informations de profil.

### 2. Organisation Académique
*   **Departments** : Un département contient plusieurs **Professeurs** et **Étudiants**.
    *   *Relation* : Un Professeur **appartient à** un Département.
    *   *Relation* : Un Étudiant **appartient à** un Département.

### 3. Les Soutenances (Cœur du sujet)
*   **ThesisDefenseReports** : Représente le dossier de soutenance.
    *   Elle est liée à un unique **Étudiant**.
*   **JuryMembers** : C'est la table pivot entre une Soutenance et les Professeurs.
    *   Une soutenance a plusieurs jurés (Professeurs).
    *   Chaque juré a un rôle spécifique dans cette soutenance (Président, Rapporteur, Examinateur...).

### 4. Documents
*   Les documents sont gérés de façon **polymorphique**.
*   Un `Document` peut appartenir soit à un **Professeur** (CV, diplômes...), soit à un **Étudiant** (Mémoire, rapport...).

## Comptes de Démonstration
- **Admin**: admin@example.com / password
- **Professeur**: professor@example.com / password
- **Étudiant**: student@example.com / password
