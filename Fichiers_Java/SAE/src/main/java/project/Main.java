package project;

import project.branch.Branch;
import project.documents.DocumentHandler;
import project.tree.Tree;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.Set;

//TIP To <b>Run</b> code, press <shortcut actionId="Run"/> or
// click the <icon src="AllIcons.Actions.Execute"/> icon in the gutter.
public class Main {
    public static void main(String[] args) {
        String filePath = "C:\\Users\\elyas\\OneDrive\\Documents\\coursinfo\\SAE-S2\\projet-sae\\Fichiers_C\\fichier_du_jeu.txt";

        try {
            String documentContent = readDocument(filePath);

            DocumentHandler documentHandler = new DocumentHandler(documentContent, "", "");
            Tree tree = new Tree();

            // Ajoute les branches extraites du document à l'arbre
            documentHandler.addBranchesFromDocumentInTree(tree);

            // Affiche l'arbre
            System.out.println(tree);

            // Détection des cycles
            Set<Set<Branch>> cycles = tree.detectAllCycles();

//            // Affichage des cycles détectés
//            if (cycles.isEmpty()) {
//                System.out.println("Aucun cycle détecté dans l'arbre.");
//            } else {
//                System.out.println("Cycles détectés dans l'arbre:");
//                for (Set<Branch> cycle : cycles) {
//                    System.out.println(cycle);
//                }
//            }
            tree.removeWeakestBranchUntilNoCycle(documentHandler);

            System.out.println(tree);




        } catch (IOException e) {
            System.err.println("Erreur lors de la lecture du document : " + e.getMessage());
        } catch (IllegalArgumentException e) {
            System.err.println("Document invalide : " + e.getMessage());
        }
    }

    private static String readDocument(String filePath) throws IOException {
        Path path = Paths.get(filePath);
        byte[] encoded = Files.readAllBytes(path);
        return new String(encoded);
    }
}