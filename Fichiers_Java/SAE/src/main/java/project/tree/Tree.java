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

    public ArrayList<Branch> getBranches() {
        return branches;
    }

    public void addBranch(Branch branch) {
        branches.add(branch);
    }

    private void cycleDetectorDFS(Branch currentBranch, Set<Branch> visitedBranches, Set<Branch> currentCycle,
                                  Set<Set<Branch>> allCycles, Set<Set<Branch>> uniqueCycles) {
        visitedBranches.add(currentBranch);
        currentCycle.add(currentBranch);

        String nextWord = currentBranch.getOtherWord(currentBranch.getWord1());
        for (Branch nextBranch : branches) {
            if (nextBranch.getWord1().equals(nextWord) || nextBranch.getWord2().equals(nextWord)) {
                if (currentCycle.contains(nextBranch)) {
                    // Cycle détecté
                    Set<Branch> cycleSet = new HashSet<>(currentCycle);
                    allCycles.add(cycleSet);
                    uniqueCycles.add(cycleSet);
                } else if (!visitedBranches.contains(nextBranch)) {
                    cycleDetectorDFS(nextBranch, visitedBranches, currentCycle, allCycles, uniqueCycles);
                }
            }
        }

        currentCycle.remove(currentBranch);
        visitedBranches.remove(currentBranch);
    }

    public Set<Set<Branch>> detectAllCycles() {
        Set<Set<Branch>> allCycles = new HashSet<>();
        Set<Branch> visitedBranches = new HashSet<>();
        Set<Set<Branch>> uniqueCycles = new HashSet<>();

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
                if (branch.getScore() < minScore) {
                    minScore = branch.getScore();
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

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder("Tree:\n");

        for (Branch branch : branches) {
            sb.append(branch.toString()).append("\n");
        }

        return sb.toString();
    }
}
