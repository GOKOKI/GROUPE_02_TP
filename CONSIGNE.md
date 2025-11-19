# Évaluation Pratique : Gestion des Documents de Soutenance

## 📝 Contexte
Vous travaillez sur une application Laravel existante de gestion de documents de soutenance (Secretary Management System). Votre mission est d'améliorer l'application en ajoutant des fonctionnalités critiques tout en respectant strictement la méthodologie **TDD (Test Driven Development)**.

**Durée :** 2h30.

## 🛠️ Règles du Jeu
1.  **TDD Obligatoire** : Pour chaque fonctionnalité, vous **devez** écrire le test **avant** le code.
    *   🔴 **RED** : Écrire un test qui échoue.
    *   🟢 **GREEN** : Écrire le code minimum pour faire passer le test.
    *   🔵 **REFACTOR** : Améliorer le code sans casser le test.
2.  **Git** : Vos commits doivent refléter cette démarche. Exemple de messages de commit :
    *   `test: add failing test for feature X`
    *   `feat: implement feature X`
    *   `refactor: clean up code`
3.  **Autonomie** : À vous de définir les noms des classes, des tables et l'architecture technique la plus pertinente pour répondre au besoin.
4.  **IA & Internet** : Autorisés.

## ⚠️ Avertissement Important
Vous êtes tenus entièrement responsables du code que vous produisez.
**Une note de ZÉRO sera attribuée si vous n'êtes pas en mesure d'expliquer votre implémentation lors de la revue de code.**
L'utilisation de l'IA est un outil, pas une fin en soi. Vous devez comprendre chaque ligne commise.

## 🚀 Missions

### Mission 1 : Système d'Audit (Log des Notes)
L'administration souhaite garder une trace (Audit Log) fiable et persistante chaque fois qu'une note finale est attribuée ou modifiée pour une soutenance.

**Objectif fonctionnel :**
Vous devez concevoir et implémenter un système permettant d'historiser ces actions.
Les informations essentielles à conserver pour chaque modification de note sont :
*   L'utilisateur à l'origine de l'action.
*   L'action effectuée.
*   Le détail du changement (ex: la nouvelle note attribuée).
*   La date et l'heure de l'action.

**Exigence TDD :**
Vous devez prouver par un test automatisé que l'enregistrement se fait correctement lors de l'attribution d'une note via l'application. À vous de structurer la base de données et le code en conséquence.

### Mission 2 : Notification par Email
Lorsqu'une soutenance est programmée (création d'une soutenance avec une date future), l'étudiant concerné doit automatiquement recevoir un email de confirmation contenant la date et l'heure du rendez-vous.

**Objectif fonctionnel :**
*   L'email doit être envoyé uniquement à l'étudiant concerné.
*   L'email doit contenir explicitement la date et l'heure de la soutenance.

**Exigence TDD :**
*   Vous devez écrire un test qui garantit que l'email est bien déclenché lors de la création de la soutenance.
*   Le contenu de l'email doit être validé par le test.

## 📦 Livraison
À la fin des 2h30 :
1.  Assurez-vous que **tous** les tests (anciens et nouveaux) passent.
2.  Poussez votre code sur la branche rendue.
3.  Le dernier commit doit être : `final: evaluation submission`.

Bon courage !
