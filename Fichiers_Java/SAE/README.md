# 🐒 Moteur de chaine de Semonkey 🐒

Voici le moteur de chaîne de MonkeyGame ! Vous retrouverez ici toutes informations sur la création et le fonctionnement
de notre jeu.

## Fonctionnalités

_Pour la partie Java du jeu, voici les différentes fonctionnalités :_

- [ ] Création d'arbre à partir d'un fichier :
    - Récupération et lecture d'un fichier .txt.
    - Vérification de la validité du fichier afin d'être sûr que ce soit bien le fichier résultant du moteur de score.
    - Vérification de l'absence des branches supprimées auparavant.
    - Ajout des branches à garder dans un arbre ➡️ classe **Tree**
- [ ] Suppéression des branches les plus faibles des cycles :
    - Parcourt de l'arbre pour détecter les cycles. Un cycle se produit lorsqu'un chemin partant d'un mot revient à ce
      même mot sans passer deux fois par la même branche.
    - Evaluation pour chaque cycle détecté du score de chaque branche dans le cycle. Le score reflète l'efficacité ou la
      pertinence de la branche dans le contexte du jeu.
    - Identification de la branche avec le score le plus faible dans chaque cycle. Cette branche est considérée comme la
      moins cruciale pour le parcours et est donc supprimée pour optimiser l'arbre.
    - Après la suppression des branches faibles, l'arbre est mis à jour. Cela implique la restructuration de l'arbre
      pour s'assurer qu'il reste cohérent et fonctionnel après la suppression des branches.
- [ ] Ecriture de l'arbre résultant des opération dans un fichier :
    - Ecriture de chaque branche de l'arbre dans un fichier identique à celui reçu.
- [ ] Récupération du score de l'arbre :
    - Parcours de tout l'arbre en partant du mot de départ jusqu'à arriver au mot d'arrivée.
    - A chaque changement de branche, on compare le score avec le dernier obtenu et on l'enrengistre s'il est inférieur
      au dernier.
    - Enfin, quand on est arrivé au mot de départ, on se retrouve avec le score de l'arrête la plus faible liant le mot
      de départ au mot d'arrivée.

## Comment Construire

### Prérequis

- Avoir un document .txt qui respecte les règles de présentation du fichier renvoyé par le moteur de score.

### Construction

1. **Étape 1** : Dans le fichier .txt d'entrée, ajouter tous les mots que l'on veut mettre dans l'arbre ainsi que leurs
   offsets dans le documents en respectant le nom des catégories.
2. **Étape 2** : Ecrire dans le fichier toutes les distances entre tous les mots en respectant la manière d'écrire.
3. **Étape 3** : Excécuter le Main.java en ligne de commande en mettant les arguments suivants (dans l'ordre) :
   **Fonction entry.txt mot1 mot2**
   Avec fonction étant soit "optimize" ou "score" selon ce que l'on veut récupérer, mot1 le mot de départ et mot2 celui
   d'arrivée