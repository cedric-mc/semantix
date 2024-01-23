# 🐒 Moteur de chaine de MonkeyGame 🐒

Voici le moteur de chaîne de MonkeyGame ! Vous retrouverez ici toutes informations sur la création et le fonctionnement
de notre jeu.

## Fonctionnalités

_Pour la partie Java du jeu, voici les différentes fonctionnalités :_

- [ ] Création d'arbre à partir d'un fichier :
    - Récupération et lecture d'un fichier .txt.
    - Vérification de la validité du fichier afin d'être sûr que ce soit bien le fichier résultant du moteur de score.
    - Vérification de l'absence des branches supprimées auparavant.
    - Ajout des branches à garder dans un arbre ➡️ classe **Tree**
- [ ] Détection des cycles dans un arbre :
    - Détection des cycles à l'aide d'un algorithme DFS
        - Un algorithme de DFS (Depth-First Search, Recherche en profondeur en français) permet d'effectuer une
          recherche qui explore l'arbre en profondeur de manière récurssive de branche en branche puis en revenant en
          arrière à chaque fois.
        - On commence par choisir un noeud de départ...
        - ...En démarrant de ce noeud, on explore ensuite au long de chacunes des branches en marquant chaque noeud
          visité pour pour éviter une boucle infinie...
        - ...Quand on atteint un noeud sans voisin qui n'a pas été pas été visité, on revient en arrière pour visiter
          les autres noeuds...
        - ...Enfin, l'algorithme se termine quand tous les noeuds ont été visités.
        - Complexité 🔁 : La complexité peut être exprimée en fonction du nombre de noeuds (V) et d'arrêtes (E), c'est à
          dire de l'ordre de O(V + E)
    - Récupération à chaque cycle détecté des branches concernées qui sont mises dans un ensemble contenant tous les
      cycles.
- [ ] Suppéression des branches les plus faibles des cycles :
    - Recherche du cycle le plus long dans l'ensemble des cycles détectés.
    - Recherche de la branche la plus faible dans ce cycle, c'est à dire celle avec le score plus faible.
    - Suppression de cette branche de l'arbre.
    - Ecrire dans le document des branches supprimées la branche en question.
    - Itération jusqu'à ce qu'il n'y ai plus aucun cycle.
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