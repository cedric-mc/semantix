package project.tree;

import org.assertj.core.api.Assertions;
import org.junit.jupiter.api.Test;
import project.branch.Branch;
import project.documents.DocumentHandler;

import java.io.IOException;

public class TreeTest {
    @Test
    void should_return_optimize_tree_by_deleting() throws IOException {
        Tree testTree = new Tree();
        testTree.addBranch(new Branch("A", "B", 68.22f));
        testTree.addBranch(new Branch("A", "C", 83.33f));
        testTree.addBranch(new Branch("A", "D", 67.38f));
        testTree.addBranch(new Branch("B", "C", 52.92f));
        testTree.addBranch(new Branch("B", "D", 57.12f));
        testTree.addBranch(new Branch("C", "D", 58.05f));

        Tree optitree = testTree.createMaxScoreTree();

        Tree wishedTree = new Tree();
        wishedTree.addBranch(new Branch("A", "B", 68.22f));
        wishedTree.addBranch(new Branch("A", "C", 83.33f));
        wishedTree.addBranch(new Branch("A", "D", 67.38f));

        Assertions.assertThat(optitree.isEqual(wishedTree)).isTrue();
    }

    @Test
    void should_return_score_of_the_weakest_branch_between_two_words() {
        Tree treeTest = new Tree();
        treeTest.addBranch(new Branch("A", "B", 68.22f));
        treeTest.addBranch(new Branch("B", "C", 83.33f));
        treeTest.addBranch(new Branch("A", "D", 67.38f));

        float scoreTest = treeTest.getTreeScore("A","B");

        float expectedScore = 68.22f;

        Assertions.assertThat(scoreTest == expectedScore).isTrue();
    }

}
