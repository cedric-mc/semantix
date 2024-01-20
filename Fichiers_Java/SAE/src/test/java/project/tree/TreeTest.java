package project.tree;

import org.junit.jupiter.api.Test;
import project.branch.Branch;

public class TreeTest {
    @Test
    void should_return_optimize_tree_by_deleting() {
        Tree testTree = new Tree();
        testTree.addBranch(new Branch("A", "B", 68.22f));
        testTree.addBranch(new Branch("A", "C", 83.33f));
        testTree.addBranch(new Branch("A", "D", 67.38f));
        testTree.addBranch(new Branch("B", "C", 52.92f));
        testTree.addBranch(new Branch("B", "D", 57.12f));
        testTree.addBranch(new Branch("C", "D", 58.05f));

        testTree.removeWeakestBranchUntilNoCycle();

        Tree wishedTree = new Tree();


        Assertions.assertThat(testTree).isEqualTo(wishedTree);
    }

}
