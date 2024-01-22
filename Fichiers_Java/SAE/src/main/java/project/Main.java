package project;

import project.branch.Branch;
import project.documents.DocumentHandler;
import project.tree.Tree;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;

public class Main {
    public static void main(String[] args) {
        // Chemin du fichier d'entrée
        String filePath = String.valueOf(Paths.get("entry.txt"));

        try {
            // Lecture du contenu du document depuis le fichier
            String documentContent = readDocument(filePath);

            // Création d'un gestionnaire de document avec le contenu lu
            DocumentHandler documentHandler = new DocumentHandler(documentContent);

            // Création d'un arbre pour stocker les branches
            Tree tree = new Tree();

            // Ajoute les branches extraites du document à l'arbre
            documentHandler.addBranchesFromDocumentInTree(tree);

            // Affiche l'arbre
            System.out.println("Arbre avant la suppresion des branches :\n");
            System.out.println(tree);

            // Supprime la branches les plus faibles
            tree.removeWeakestBranchUntilNoCycle(documentHandler);

            // Affiche l'arbre
            System.out.println("Arbre après la suppresion des branches :\n");
            System.out.println(tree);

            // Ecrit l'arbre dans le document
            documentHandler.writeDocumentToFile(tree, null);


        } catch (IOException e) {
            System.err.println("Erreur lors de la lecture du document : " + e.getMessage());
        } catch (IllegalArgumentException e) {
            System.err.println("Document invalide : " + e.getMessage());
        }
    }

    // Méthode pour lire le contenu d'un document à partir d'un fichier
    private static String readDocument(String filePath) throws IOException {
        Path path = Paths.get(filePath);
        byte[] encoded = Files.readAllBytes(path);
        return new String(encoded);
    }
}