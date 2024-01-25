package project.tree;

import project.branch.Branch;
import project.documents.DocumentHandler;

import java.util.*;

public class Tree {
    private final ArrayList<Branch> branches;
    private Set<String> writtenBranches = new HashSet<>();

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

    public List<List<Branch>> detectLoops() {
        List<List<Branch>> loops = new ArrayList<>();
        Set<String> words = getAllWords();
        Set<String> uniqueLoops = new HashSet<>();

        for (String word : words) {
            findLoops(word, word, new HashSet<>(), new ArrayList<>(), loops, uniqueLoops);
        }

        return loops;
    }

    private void findLoops(String startWord, String currentWord, Set<Branch> visitedBranches, List<Branch> currentPath, List<List<Branch>> loops, Set<String> uniqueLoops) {
        for (Branch branch : branches) {
            if (visitedBranches.contains(branch)) {
                continue; // Éviter de répéter une branche
            }

            if ((branch.word1().equals(currentWord) || branch.word2().equals(currentWord)) && !branch.getOtherWord(currentWord).equals(startWord)) {
                visitedBranches.add(branch);
                currentPath.add(branch);
                findLoops(startWord, branch.getOtherWord(currentWord), visitedBranches, currentPath, loops, uniqueLoops);
                currentPath.remove(currentPath.size() - 1);
                visitedBranches.remove(branch);
            } else if (branch.getOtherWord(currentWord).equals(startWord) && currentPath.size() > 1) {
                // Une boucle a été trouvée et elle a plus d'une branche
                visitedBranches.add(branch);
                List<Branch> loop = new ArrayList<>(currentPath);
                loop.add(branch);

                String loopSignature = getLoopSignature(loop);
                if (uniqueLoops.add(loopSignature)) {
                    loops.add(loop);
                }

                visitedBranches.remove(branch);
            }
        }
    }

    private String getLoopSignature(List<Branch> loop) {
        // Créer une chaîne de caractères unique pour la boucle
        List<String> words = new ArrayList<>();
        for (Branch branch : loop) {
            words.add(branch.word1());
            words.add(branch.word2());
        }
        Collections.sort(words);
        return String.join("-", words);
    }

    private Set<String> getAllWords() {
        Set<String> words = new HashSet<>();
        for (Branch branch : branches) {
            words.add(branch.word1());
            words.add(branch.word2());
        }
        return words;
    }

    public void removeWeakestBranchesInLoops(DocumentHandler dh) {
        List<List<Branch>> loops = detectLoops();
        if (!loops.isEmpty()) {
            List<Branch> longestLoop = findLongestLoop(loops);
            removeWeakestBranchInLoop(longestLoop, dh);
        }
    }

    private List<Branch> findLongestLoop(List<List<Branch>> loops) {
        List<Branch> longestLoop = loops.get(0);
        for (List<Branch> loop : loops) {
            if (loop.size() > longestLoop.size()) {
                longestLoop = loop;
            }
        }
        return longestLoop;
    }

    private void removeWeakestBranchInLoop(List<Branch> loop, DocumentHandler dh) {
        Branch weakestBranch = loop.get(0);
        float lowestScore = weakestBranch.score();

        for (Branch branch : loop) {
            if (branch.score() < lowestScore) {
                weakestBranch = branch;
                lowestScore = branch.score();
            }
        }

        branches.remove(weakestBranch);
        String branchString = dh.writeSingleBranchInDocument(weakestBranch);

        if (!writtenBranches.contains(branchString)) {
            dh.writeDocumentToFile(null, weakestBranch);
            writtenBranches.add(branchString);
        }
    }

//    // Méthode pour supprimer la branche la plus faible jusqu'à ce qu'aucun cycle ne soit détecté
//    public void removeWeakestBranchUntilNoCycle(DocumentHandler documentHandler) {
//        while (!detectAllCycles().isEmpty()) {
//            Set<Set<Branch>> allCycles = detectAllCycles();
//
//            // Rechercher le cycle le plus long
//            Set<Branch> longestCycle = Collections.emptySet();
//            for (Set<Branch> cycle : allCycles) {
//                if (cycle.size() > longestCycle.size()) {
//                    longestCycle = cycle;
//                }
//            }
//
//            // Trouver la branche la plus faible dans le cycle le plus long
//            Branch weakestBranch = null;
//            float minScore = Float.MAX_VALUE;
//            for (Branch branch : longestCycle) {
//                if (branch.score() < minScore) {
//                    minScore = branch.score();
//                    weakestBranch = branch;
//                }
//            }
//
//            // Supprimer la branche la plus faible du cycle le plus long
//            if (weakestBranch != null) {
//                branches.remove(weakestBranch);
//                documentHandler.writeDocumentToFile(null, weakestBranch);
//            }
//        }
//    }

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
