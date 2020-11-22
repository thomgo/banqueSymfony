# Application bancaire sous Symfony

Il s'agit d'une application développée dans le cadre de mon poste de formateur en développement web. L'objectif est que les apprenants produisent une application bancaire permettant à un utilisateur de gérer ses comptes à l'aide du framework Symfony.

Au travers de ce projet, ils apprennent à :
- Comprendre le modèle requêtes/réponses
- Démarrer un projet Symfony via la CLI
- Gérer le routing de l’application
- Développer des controllers et associer des routes à des méthodes
- Utiliser le moteur de template twig pour l’affichage des vues
- Gérer sa base de données et ses entités avec l’orm Doctrine
- Créer des formulaires et gérer leur soumission
- Gérer ses utilisateurs et la sécurité de l’application
- Valider ses données
- Afficher des messages flash
- Peupler sa base à l’aide de fixtures
- Test son application à l’aide des tests unitaires

## Consignes

Si l’évolution de votre application vers un modèle objet avec intégration du pattern MVC a permis de corriger les bugs remontés précédemment et d’améliorer la maintenabilité de l’application, trop soucis de maintenance sont encore présents.

En effet de nombreux développeurs se sont succédés sur le projet, apportant chacun leur style de code et aujourd’hui le code source a perdu en cohérence rendant son évolution difficile. De même des problèmes de performances lors des grosses montées en charge se font sentir.

C’est pour toutes ces raisons que votre chef de projet a décidé de migrer l’application vers sur le framework Symfony.

Rappel des spécifications fonctionnelles :

- L’application n’est accessible qu’aux seuls utilisateurs connectés
- Quand un utilisateur non connecté va sur l’application il est redirigé vers une page de connexion
avec un formulaire
- Un utilisateur se connecte à l’aide d’une adresse mail et d’un mot de passe
- Un utilisateur connecté peut se déconnecter
- Une fois connecté, l’utilisateur voit uniquement ses comptes en banque personnels.
- Quand l’utilisateur clique sur un compte en banque, il arrive sur une page dédié au compte où il
voit les informations du compte mais aussi les dernières opérations effectuées sur le compte
- Via une page dédiée un utilisateur peut créer un nouveau compte personnel à l’aide d’un formulaire. Une fois créé le compte apparaît sur la page d’accueil. Attention le compte doit respecter les conditions minimum de création de compte (bon type et bon montant)
- L’utilisateur peut effectuer des dépôts ou des retraits sur le compte de son choix.  Le montant du compte est alors mis à jour et une nouvelle opération est enregistrée sur le compte.

En plus de ces spécifications, vous essaierez de :
- peupler la base de données à l’aide de fixtures
- valider les données rentrées dans les formulaires à l’aide du validator

## Pour aller plus loin

- Ecrire quelques tests unitaires simples pour vérifier le fonctionnement de l’application
- Déployer votre application sur le service cloud Heroku afin d’en avoir une version en ligne
- L’utilisateur peut effectuer des transfères d’un compte à l’autre, ce qui crée deux nouvelles opération
- Des messages d’erreur sont éventuellement affichés sur les différents formulaires quand ceux-ci sont mal remplis
- L’utilisateur peut supprimer les comptes qui lui appartiennent
- Sur la page d’accueil, pour chaque compte l’utilisateur voit la dernière opération sur ce compte
