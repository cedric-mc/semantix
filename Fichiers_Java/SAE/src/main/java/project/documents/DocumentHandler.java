package project.documents;

import project.branch.Branch;
import project.tree.Tree;

import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;

public class DocumentHandler {
    private final String documentEntryPath;
    private final String documentExitPath;
    private final String documentDeletedBranchesPath;
    private static final String WORDS_SECTION_HEADER = "Liste des mots :";
    private static final String OFFSETS_SECTION_HEADER = "Offsets dans le dictionnaire :";
    private static final String DISTANCES_SECTION_HEADER = "Distances entre les paires de mots :";
    private static final String WORD_OFFSET_PATTERN = "\\w+: \\d+";
    private static final String WORD_DISTANCE_PATTERN = "\\w+ - \\w+ : \\d+\\.\\d+";

    public DocumentHandler(String documentEntryPath) {
        validateDocument(documentEntryPath);
        this.documentEntryPath = String.valueOf(Paths.get(documentEntryPath));
        this.documentExitPath = "exit.txt";
        this.documentDeletedBranchesPath = "deletedbranches.txt";
    }

    // Méthode de validation d'un document
    private void validateDocument(String document) {
        // Document non vide
        if (document == null || document.trim().isEmpty()) {
            throw new IllegalArgumentException("The document cannot be empty or null.");
        }

        // On le sépare en lignes
        String[] lines = document.split("\\r?\\n");

        // On vérifie la première partie du document...
        validateSectionHeader(lines, 0, WORDS_SECTION_HEADER);
        validateWordsSection(lines, 1);

        // On vérifie la deuxième ...
        int offsetsSectionStartIndex = findSectionStartIndex(lines, OFFSETS_SECTION_HEADER);
        validateSectionHeader(lines, offsetsSectionStartIndex, OFFSETS_SECTION_HEADER);
        validateWordOffsets(lines, offsetsSectionStartIndex + 1);
        // Et la troisième !
        int distancesSectionStartIndex = findSectionStartIndex(lines, DISTANCES_SECTION_HEADER);
        validateSectionHeader(lines, distancesSectionStartIndex, DISTANCES_SECTION_HEADER);
        validateWordDistances(lines, distancesSectionStartIndex + 1);
    }

    // Méthode de validation de l'en-tête d'une section du document
    private void validateSectionHeader(String[] lines, int index, String expectedHeader) {
        if (index >= lines.length || !lines[index].trim().equals(expectedHeader)) {
            throw new IllegalArgumentException("Invalid document format: Missing or incorrect section header.");
        }
    }

    // Méthode de validation de la section "Liste des mots :"
    private void validateWordsSection(String[] lines, int startIndex) {
        for (int i = startIndex; i < lines.length; i++) {
            String word = lines[i].trim();
            if (word.isEmpty()) {
                break; // Fin de la section "Liste des mots :"
            }
            // Validation supplémentaire des mots si nécessaire
        }
    }

    // Méthode de recherche de l'index de début d'une section
    private int findSectionStartIndex(String[] lines, String sectionHeader) {
        for (int i = 0; i < lines.length; i++) {
            if (lines[i].trim().equals(sectionHeader)) {
                return i;
            }
        }
        throw new IllegalArgumentException("Invalid document format: Missing section header - " + sectionHeader);
    }

    // Méthode de validation des offsets des mots
    private void validateWordOffsets(String[] lines, int startIndex) {
        for (int i = startIndex; i < lines.length; i++) {
            String line = lines[i].trim();
            if (line.isEmpty()) {
                break; // Fin de la section "Offsets dans le dictionnaire :"
            }
            if (!line.matches(WORD_OFFSET_PATTERN)) {
                throw new IllegalArgumentException("Invalid document format: Incorrect word offset format.");
            }
        }
    }

    // Méthode de validation des distances entre les paires de mots
    private void validateWordDistances(String[] lines, int startIndex) {
        for (int i = startIndex; i < lines.length; i++) {
            String line = lines[i].trim();
            if (line.isEmpty()) {
                break; // Fin de la section "Distances entre les paires de mots :"
            }
            if (!line.matches(WORD_DISTANCE_PATTERN)) {
                throw new IllegalArgumentException("Invalid document format: Incorrect word distance format.");
            }
        }
    }

    // Méthode pour ajouter des branches à l'arbre depuis un document
    public void addBranchesFromDocumentInTree(Tree tree) throws IOException {
        // Extrait les distances du document et crée des branches
        String[] lines = documentEntryPath.split("\\r?\\n");
        int distancesSectionStartIndex = findSectionStartIndex(lines, DISTANCES_SECTION_HEADER);

        for (int i = distancesSectionStartIndex + 1; i < lines.length; i++) {
            String line = lines[i].trim();
            if (line.isEmpty()) {
                break; // Fin de la section "Distances entre les paires de mots :"
            }
            String[] parts = line.split(" - ");
            String[] scoreParts = parts[1].split(" : ");
            String word1 = parts[0].trim();
            String word2 = scoreParts[0].trim();
            float score = Float.parseFloat(scoreParts[1].trim());

            // Créer une nouvelle branche et l'ajouter à l'arbre
            Branch thisBranch = new Branch(word1, word2, score);
            String deletedBranchContent = Files.readString(Paths.get(documentDeletedBranchesPath));
            if (!deletedBranchContent.contains(line)) {
                tree.addBranch(thisBranch);
            }
        }
    }

    // Méthode pour écrire toutes les branches d'un arbre dans un document
    private String writeAllBranchesInDocument(Tree tree) {
        StringBuilder documentBuilder = new StringBuilder();

        // Section "Distances entre les paires de mots :"
        documentBuilder.append(DISTANCES_SECTION_HEADER).append("\n");
        for (Branch branch : tree.getBranches()) {
            documentBuilder.append(branch.word1()).append(" - ").append(branch.word2()).append(" : ").append(branch.score()).append("\n");
        }

        return documentBuilder.toString();
    }

    // Méthode pour écrire une seule branche dans un document
    private String writeSingleBranchInDocument(Branch branch) {
        return branch.word1() + " - " + branch.word2() + " : " + branch.score() + "\n";
    }

    // Méthode pour écrire le document résultant dans un fichier
    public void writeDocumentToFile(Tree tree, Branch branch) {
        String documentContent;
        Path filePath;
        if (tree != null) {
            documentContent = writeAllBranchesInDocument(tree);
            filePath = Paths.get(documentExitPath);
        } else if (branch != null) {
            documentContent = writeSingleBranchInDocument(branch);
            filePath = Paths.get(documentDeletedBranchesPath);
        } else {
            throw new IllegalArgumentException("Both tree and branch cannot be null.");
        }

        try (BufferedWriter writer = new BufferedWriter(new FileWriter(String.valueOf(filePath), true))) {
            writer.write(documentContent);
        } catch (IOException e) {
            e.printStackTrace(); // Gérer les exceptions d'écriture de fichier
        }
    }

}
