package project.tree;

import project.branch.Branch;
import project.documents.DocumentHandler;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashSet;
import java.util.Set;

public class Tree {
    private final ArrayList<Branch> branches;

    public Tree() {
        branches = new ArrayList<>();
    }

    // Méthode pour obtenir la liste des branches
    public ArrayList<Branch> getBranches() {
        return branches;
    }

    // Méthode pour ajouter une nouvelle branche à l'arbre
    public void addBranch(Branch branch) {
        branches.add(branch);
    }

    // Méthode récursive pour détecter tous les cycles dans l'arbre
    private void cycleDetectorDFS(Branch currentBranch, Set<Branch> visitedBranches, Set<Branch> currentCycle,
                                  Set<Set<Branch>> allCycles, Set<Set<Branch>> uniqueCycles) {
        // Marquer la branche actuelle comme visitée et l'ajouter au cycle actuel
        visitedBranches.add(currentBranch);
        currentCycle.add(currentBranch);

        // Obtenir le mot suivant dans la branche actuelle
        String nextWord = currentBranch.getOtherWord(currentBranch.word1());

        // Parcourir toutes les branches pour trouver des cycles
        for (Branch nextBranch : branches) {
            if (nextBranch.word1().equals(nextWord) || nextBranch.word2().equals(nextWord)) {
                if (currentCycle.contains(nextBranch)) {
                    // Cycle détecté, ajout au set de tous les cycles et au set de cycles uniques
                    Set<Branch> cycleSet = new HashSet<>(currentCycle);
                    allCycles.add(cycleSet);
                    uniqueCycles.add(cycleSet);
                } else if (!visitedBranches.contains(nextBranch)) {
                    // Si la branche n'a pas été visitée, poursuivre la recherche de cycle
                    cycleDetectorDFS(nextBranch, visitedBranches, currentCycle, allCycles, uniqueCycles);
                }
            }
        }

        // Retirer la branche actuelle du cycle et la marquer comme non visitée
        currentCycle.remove(currentBranch);
        visitedBranches.remove(currentBranch);
    }

    // Méthode pour détecter tous les cycles dans l'arbre
    public Set<Set<Branch>> detectAllCycles() {
        Set<Set<Branch>> allCycles = new HashSet<>();
        Set<Branch> visitedBranches = new HashSet<>();
        Set<Set<Branch>> uniqueCycles = new HashSet<>();

        // Parcourir toutes les branches pour détecter les cycles
        for (Branch branch : branches) {
            if (!visitedBranches.contains(branch)) {
                Set<Branch> currentCycle = new HashSet<>();
                cycleDetectorDFS(branch, visitedBranches, currentCycle, allCycles, uniqueCycles);
            }
        }

        // Filtrer les cycles pour exclure ceux qui ne contiennent qu'une branche
        allCycles.removeIf(cycle -> cycle.size() < 2);
        uniqueCycles.removeIf(cycle -> cycle.size() < 2);

        return uniqueCycles;
    }

    // Méthode pour supprimer la branche la plus faible jusqu'à ce qu'aucun cycle ne soit détecté
    public void removeWeakestBranchUntilNoCycle(DocumentHandler documentHandler) {
        while (!detectAllCycles().isEmpty()) {
            Set<Set<Branch>> allCycles = detectAllCycles();

            // Rechercher le cycle le plus long
            Set<Branch> longestCycle = Collections.emptySet();
            for (Set<Branch> cycle : allCycles) {
                if (cycle.size() > longestCycle.size()) {
                    longestCycle = cycle;
                }
            }

            // Trouver la branche la plus faible dans le cycle le plus long
            Branch weakestBranch = null;
            float minScore = Float.MAX_VALUE;
            for (Branch branch : longestCycle) {
                if (branch.score() < minScore) {
                    minScore = branch.score();
                    weakestBranch = branch;
                }
            }

            // Supprimer la branche la plus faible du cycle le plus long
            if (weakestBranch != null) {
                branches.remove(weakestBranch);
                documentHandler.writeDocumentToFile(null, weakestBranch);
            }
        }
    }

    // Méthode pour vérifier l'égalité de deux arbres
    public boolean isEqual(Tree tree) {
        boolean trueFalser = false;
        if (tree.getBranches().size() == branches.size()) {
            trueFalser = true;
            for (Branch branch : tree.getBranches()) {
                if (!tree.getBranches().contains(branch)) {
                    trueFalser = false;
                }
            }
        }
        return trueFalser;
    }

    // Méthode récursive pour obtenir le score le plus faible entre deux mots
    private Branch getBranchWithLowerScoreBetweenWords(String currentWord, String targetWord, float currentScore, Set<Branch> visitedBranches) {
        Branch resultBranch = null;

        // Parcourir chaque branche de l'arbre
        for (Branch branch : branches) {
            // Vérifier si la branche n'a pas été visitée et connecte au mot actuel
            if (!visitedBranches.contains(branch) &&
                    (branch.word1().equals(currentWord) || branch.word2().equals(currentWord))) {

                // Mettre à jour le score actuel en ajoutant le score de la branche
                float updatedScore = currentScore + branch.score();

                // Si le mot cible est atteint, comparer le score avec le minimum actuel
                if (branch.word1().equals(targetWord) || branch.word2().equals(targetWord)) {
                    if (resultBranch == null || updatedScore < resultBranch.score()) {
                        resultBranch = branch;
                    }
                } else {
                    // Sinon, explorer récursivement la branche suivante
                    visitedBranches.add(branch);
                    Branch nextBranch = getBranchWithLowerScoreBetweenWords(
                            (branch.word1().equals(currentWord)) ? branch.word2() : branch.word1(),
                            targetWord,
                            updatedScore,
                            visitedBranches
                    );
                    visitedBranches.remove(branch);

                    // Mettre à jour le résultat si une branche avec un score inférieur est trouvée
                    if (nextBranch != null && (resultBranch == null || updatedScore < resultBranch.score())) {
                        resultBranch = nextBranch;
                    }
                }
            }
        }

        return resultBranch;
    }

    // Méthode pour obtenir le score le plus faible entre deux mots
    public float getTreeScore(String word1, String word2) {
        Branch resultBranch = getBranchWithLowerScoreBetweenWords(word1, word2, 0, new HashSet<>());
        // Renvoyer le score de la branche trouvée ou 0 si aucune branche n'est trouvée
        return (resultBranch != null) ? resultBranch.score() : 0;
    }

    // Redéfinition de la méthode toString pour obtenir une représentation textuelle de l'arbre
    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder("Tree:\n");

        // Ajouter chaque branche à la représentation textuelle
        for (Branch branch : branches) {
            sb.append(branch.toString()).append("\n");
        }

        return sb.toString();
    }
}
