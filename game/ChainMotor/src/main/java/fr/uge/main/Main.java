package fr.uge.main;

import fr.uge.tree.MaximumSpanningTree;

import java.io.BufferedReader;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;

/**
 * Classe principale d’éxécution
 * @see Main
 * lang = fr
 * @author Mamadou BA
 * @author Cédric MARIYA CONSTANTINE
 * @author Abdelrahim RICHE
 * @author Vincent SOUSA
 * @author Yacine ZEMOUCHE
 */
public class Main {
    /**
     * Méthode principale :
     * @param args
     * args[0] : Nom du fichier C : game_data_[pseudo].txt
     * args[1] : Nom du fichier Java : mst_[pseudo].txt
     * args[2] : Nom du fichier de sortie : best_path_[pseudo].txt
     * @throws IOException
     *
     * Récupèration ou création d’un arbre recouvrant maximal et recherche du meilleur chemin entre le mot de départ et de cible.
     * Les fichiers doivent être dans le dossier `partie`.
     */
    public static void main(String[] args) throws IOException {
        if (args.length == 0) { // S'il n'y a pas d'arguments
            System.out.println("Auteurs :\nMamadou BA\nCédric MARIYA CONSTANTINE\nAbdelrahim RICHE\nVincent SOUSA\nYacine ZEMOUCHE");
            return;
        }
        // Si le nombre d’arguments est différent de 3
        if (args.length != 3) {
            System.out.println("Utilisation : java -cp ChainMotor/target/classes fr.uge.main.Main partie/game_data_[pseudo].txt partie/mst_[pseudo].txt partie/best_path_[pseudo].txt");
            return;
        }

        String fileNameC = args[0]; // Nom du fichier C : game_data_[pseudo].txt
        String fileNameJava = args[1]; // Nom du fichier Java : mst_[pseudo].txt
        String fileNameOutput = args[2]; // Nom du fichier de sortie : best_path_[pseudo].txt
        MaximumSpanningTree maximumSpanningTree;
//        if (!Files.exists(Path.of(fileNameJava))) { // On vérifie que les fichiers existent
            // Création de l’arbre recouvrant maximal et exportation dans un fichier
            maximumSpanningTree = MaximumSpanningTree.createMaximumSpanningTree(fileNameC);
//        } else { // Sinon, on charge l’arbre recouvrant maximal
//            maximumSpanningTree = MaximumSpanningTree.loadMaximumSpanningTree(fileNameJava);
//            maximumSpanningTree.loadAddEdges(fileNameC); // On ajoute les arêtes du nouveau mot à l'arbre recouvrant maximal
//        }
        // On exporte l’arbre recouvrant maximal dans un fichier
        maximumSpanningTree.exportMaximumSpanningTreeToFile(fileNameJava);
        // Trouver le chemin entre deux mots
        BestPath bestPath = new BestPath(maximumSpanningTree);

        // Écrire le meilleur chemin dans un fichier
        // Récupérer un pseudo pour le nom du fichier
        String pseudo = fileNameJava.split("_")[1].split("\\.")[0];
        bestPath.printPathAndScore();
        bestPath.writeBestPathToFile(fileNameOutput);
    }
}
