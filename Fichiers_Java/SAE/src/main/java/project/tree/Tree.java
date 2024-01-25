package project.tree;

import project.branch.Branch;

import java.util.*;

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

    public Tree createMaxScoreTree() {
        // Trier les branches par score décroissant
        branches.sort(Comparator.comparing(Branch::score).reversed());

        Set<String> visitedWords = new HashSet<>();
        Tree maxScoreTree = new Tree();

        while (visitedWords.size() < getAllWords().size()) {
            Branch maxBranch = null;
            float maxScore = Float.MIN_VALUE;

            for (Branch branch : branches) {
                System.out.println(branch);
                // Vérifie si l'un des mots de la branche n'est pas encore visité
                if (!visitedWords.contains(branch.word1()) || !visitedWords.contains(branch.word2())) {
                    // Vérifie si le score est supérieur au score maximal actuel
                    if (branch.score() > maxScore) {
                        maxScore = branch.score();
                        maxBranch = branch;
                    }
                }
            }

            if (maxBranch == null) {
                // Aucune branche supplémentaire à visiter, sortir de la boucle
                break;
            }

            // Ajoute les mots de la branche maxBranch à la liste des mots visités
            visitedWords.add(maxBranch.word1());
            visitedWords.add(maxBranch.word2());
            System.out.println(visitedWords);
            // Ajoute maxBranch à l'arbre résultant
            maxScoreTree.addBranch(maxBranch);
        }




        return maxScoreTree;
    }


    private Set<String> getAllWords() {
        Set<String> words = new HashSet<>();
        for (Branch branch : branches) {
            words.add(branch.word1());
            words.add(branch.word2());
        }
        return words;
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

    public float getTreeScore(String word1, String word2) {
        // Trouver le chemin entre word1 et word2
        List<Branch> path = findPath(word1, word2, new HashSet<>(), new ArrayList<>());
        if (path == null) {
            return -1; // Aucun chemin trouvé
        }

        // Trouver la branche avec le score le plus faible sur ce chemin
        float lowestScore = Float.MAX_VALUE;
        for (Branch branch : path) {
            if (branch.score() < lowestScore) {
                lowestScore = branch.score();
            }
        }

        return lowestScore;
    }

    private List<Branch> findPath(String currentWord, String targetWord, Set<String> visited, List<Branch> currentPath) {
        visited.add(currentWord);

        if (currentWord.equals(targetWord)) {
            return new ArrayList<>(currentPath);
        }

        for (Branch branch : branches) {
            if ((branch.word1().equals(currentWord) || branch.word2().equals(currentWord)) && !visited.contains(branch.getOtherWord(currentWord))) {
                currentPath.add(branch);
                List<Branch> resultPath = findPath(branch.getOtherWord(currentWord), targetWord, visited, currentPath);
                if (resultPath != null) {
                    return resultPath;
                }
                currentPath.remove(currentPath.size() - 1);
            }
        }

        return null;
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
