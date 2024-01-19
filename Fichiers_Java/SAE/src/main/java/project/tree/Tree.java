package project.tree;

import project.branch.Branch;

import java.util.ArrayList;
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

        allCycles.removeIf(cycle -> cycle.size() < 2);
        uniqueCycles.removeIf(cycle -> cycle.size() < 2);

        return allCycles;
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
                    if (uniqueCycles.add(cycleSet)) {
                        allCycles.add(cycleSet);
                    }
                } else if (!visitedBranches.contains(nextBranch)) {
                    cycleDetectorDFS(nextBranch, visitedBranches, currentCycle, allCycles, uniqueCycles);
                }
            }
        }

        currentCycle.remove(currentBranch);
        visitedBranches.remove(currentBranch);
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
