package projetsae.tree;

import projetsae.branch.Branch;

import java.util.ArrayList;

public class Tree {
    private final ArrayList<Branch> branches;

    public Tree(ArrayList<Branch> branches) {
        this.branches = branches;
    }

    public ArrayList<Branch> getBranches() {
        return branches;
    }

    public void addBranch(Branch branch) {
        branches.add(branch);
    }

    public ArrayList<Branch> detectAllCycles(){
        ArrayList<Branch> cycle = new ArrayList<Branch>();
        
    }

    public void deleteWeakestBranchFromCycle() {

    }
}
