package project;

import project.documents.DocumentHandler;
import project.tree.Tree;

import java.io.IOException;

public class Main {
    public static void main(String[] args) throws IOException {

        //Arguments : fonction entrée mot1 mot2
        if (args.length == 0) {
            System.out.println("You need at least one argument.");
        } else if (args[0].equals("optimize")) {
            //initialise le fichier avec deux mots
            Tree tree = new Tree();
            DocumentHandler dh = new DocumentHandler(args[1]);
            dh.addBranchesFromDocumentInTree(tree);
            tree.removeWeakestBranchesInLoops(dh);
            dh.writeDocumentToFile(tree, null);
            System.out.println(tree);
        } else if (args[0].equals("score")) {
            //calcule le score du mst
            Tree tree = new Tree();
            DocumentHandler dh = new DocumentHandler(args[1]);
            dh.addBranchesFromDocumentInTree(tree);
            tree.removeWeakestBranchesInLoops(dh);
            float treeScore = tree.getTreeScore(args[2], args[3]);
            System.out.println(treeScore);
        } else {
            System.out.println("Cannot recognize command.");


        }
//        Tree tree = new Tree();
//        System.out.println(tree);
//        DocumentHandler dh = new DocumentHandler("entry.txt");
//        dh.addBranchesFromDocumentInTree(tree);
//        tree.removeWeakestBranchUntilNoCycle(dh);
//        dh.writeDocumentToFile(tree, null);
//        System.out.println(tree);
    }
}
