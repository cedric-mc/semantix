package project.tree;

import project.branch.Branch;

public class DocumentReaderForTree {
    private final String documentEntry;
    private static final String WORDS_SECTION_HEADER = "Liste des mots :";
    private static final String OFFSETS_SECTION_HEADER = "Offsets dans le dictionnaire :";
    private static final String DISTANCES_SECTION_HEADER = "Distances entre les paires de mots :";
    private static final String WORD_OFFSET_PATTERN = "\\w+: \\d+";
    private static final String WORD_DISTANCE_PATTERN = "\\w+ - \\w+ : \\d+\\.\\d+";

    public DocumentReaderForTree(String documentEntry) {
        validateDocument(documentEntry);
        this.documentEntry = documentEntry;
    }

    private void validateDocument(String document) {
        // Document validations

        // Document non vide
        if (document == null || document.trim().isEmpty()) {
            throw new IllegalArgumentException("Le document ne peut pas être vide ou nul.");
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

    private void validateSectionHeader(String[] lines, int index, String expectedHeader) {
        if (index >= lines.length || !lines[index].trim().equals(expectedHeader)) {
            throw new IllegalArgumentException("Invalid document format: Missing or incorrect section header.");
        }
    }

    private void validateWordsSection(String[] lines, int startIndex) {
        for (int i = startIndex; i < lines.length; i++) {
            String word = lines[i].trim();
            if (word.isEmpty()) {
                break; // Fin de la section "Liste des mots :"
            }
            // Validation supplémentaire des mots si nécessaire
        }
    }

    private int findSectionStartIndex(String[] lines, String sectionHeader) {
        for (int i = 0; i < lines.length; i++) {
            if (lines[i].trim().equals(sectionHeader)) {
                return i;
            }
        }
        throw new IllegalArgumentException("Invalid document format: Missing section header - " + sectionHeader);
    }

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

    public void addBranchesFromDocumentInTree(Tree tree) {
        // Extract distances from the document and create branches
        String[] lines = documentEntry.split("\\r?\\n");
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
            tree.addBranch(thisBranch);
        }

    }
}
