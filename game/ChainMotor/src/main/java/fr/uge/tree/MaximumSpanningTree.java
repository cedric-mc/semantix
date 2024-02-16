package fr.uge.tree;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.nio.file.StandardOpenOption;
import java.util.*;

/**
 * Classe pour représenter un arbre recouvrant maximal (MST) et effectuer des opérations sur cet arbre.
 *
 * lang = fr
 * @author Mamadou BA
 * @author Cédric MARIYA CONSTANTINE
 * @author Abdelrahim RICHE
 * @author Vincent SOUSA
 * @author Yacine ZEMOUCHE
 */
public class MaximumSpanningTree {
    private final Word startWord; // Mot de départ
    private final Word endWord; // Mot de fin
    private final List<Edge> edgesMST; // Arêtes de l’arbre de recouvrement minimal
    private final Set<Word> bannedWords; // Mots interdits

    /**
     * @param startWord Mot de départ
     * @param endWord  Mot de fin
     * @param edgesMST Arêtes de l’arbre de recouvrement minimal
     * @param bannedWords Mots interdits
     *
     * Constructeur pour initialiser un arbre recouvrant maximal
     */
    public MaximumSpanningTree(Word startWord, Word endWord, List<Edge> edgesMST, Set<Word> bannedWords) {
        this.edgesMST = edgesMST;
        this.startWord = startWord;
        this.endWord = endWord;
        this.bannedWords = bannedWords;
    }

    /**
     * @param startWord Mot de départ
     * @param endWord Mot de fin
     *
     * Constructeur pour initialiser un arbre recouvrant maximal sans arêtes
     */
    public MaximumSpanningTree(Word startWord, Word endWord) {
        this(startWord, endWord, new ArrayList<>(), new HashSet<>());
    }

    /**
     * @param file Chemin du fichier
     * @return MaximumSpanningTree
     * @throws IOException
     *
     * Charge un arbre recouvrant maximal à partir d’un fichier
     */
    public static MaximumSpanningTree loadMaximumSpanningTree(String file) throws IOException {
        List<Edge> edgesMST = new ArrayList<>();
        Set<Word> bannedWords = new HashSet<>();
        Path filePath = Path.of(file);
        BufferedReader br = Files.newBufferedReader(filePath);
        String line;
        br.readLine(); // Ligne 1 : "MaximumSpanningTree :"
        String startWord = br.readLine().split(":")[1].trim(); // Ligne 2 : "startWord : word1"
        String endWord = br.readLine().split(":")[1].trim(); // Ligne 3 : "endWord : word2"

        br.readLine(); // Ligne 4 : "edgesMST :"

        while (!Objects.equals(line = br.readLine(), "bannedWords")) {
            divideParts(edgesMST, line);
        }

        while (!Objects.equals(line = br.readLine(), "EOF")) {
            bannedWords.add(new Word(line));
        }
        br.close();
        return new MaximumSpanningTree(new Word(startWord), new Word(endWord), edgesMST, bannedWords);
    }

    /**
     * @param edgesMST Liste d’arêtes de l’arbre de recouvrement minimal
     * @param line Ligne à diviser
     *
     * Divise une ligne en parties et ajoute les arêtes à la liste
     */
    public static void divideParts(List<Edge> edgesMST, String line) {
        String[] parts = line.split(", distance:");
        splitWordsAndSimilarity(edgesMST, parts);
    }

    /**
     * @return Word Mot de départ
     */
    public Word getStartWord() {
        return startWord;
    }

    /**
     * @return Word Mot de fin
     */
    public Word getEndWord() {
        return endWord;
    }

    /**
     * @return List<Edge> Arêtes de l’arbre de recouvrement minimal
     */
    public List<Edge> getEdgesMST() {
        return edgesMST;
    }

    /**
     * @return Set<Word> Mots interdits
     */
    public Set<Word> getBannedWords() {
        return bannedWords;
    }

    /**
     * @param edge Arête à ajouter
     */
    public void addEdge(Edge edge) {
        edgesMST.add(edge);
    }

    /**
     * @param edge Arête à supprimer
     */
    public void removeEdge(Edge edge) {
        edgesMST.remove(edge);
    }

    /**
     * @return String Représentation textuelle de l’arbre recouvrant maximal
     */
    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder(); // Créer un objet StringBuilder pour construire la chaîne
        sb.append("MaximumSpanningTree :").append(System.lineSeparator()); // Ajouter le nom de l'Objet
        // Ajouter le mot de départ et le mot de fin
        sb.append("startWord : ").append(startWord).append(System.lineSeparator());
        sb.append("endWord : ").append(endWord).append(System.lineSeparator());
        // Ajouter les arêtes de l’arbre
        sb.append("edgesMST :").append(System.lineSeparator());
        for (Edge edge : edgesMST) {
            sb.append(edge.sourceWord()).append("_").append(edge.targetWord()).append(",").append(edge.similarity()).append(System.lineSeparator());
        }
        // Ajouter les mots interdits
        sb.append("bannedWords :").append(System.lineSeparator());
        for (Word word : bannedWords) {
            sb.append(word).append(System.lineSeparator());
        }
        sb.append("EOF"); // Ajouter la fin du fichier
        return sb.toString();
    }

    /**
     * @param edges Liste d’arêtes
     * @param parts Parties de la ligne
     */
    private static void splitWordsAndSimilarity(List<Edge> edges, String[] parts) {
        String[] words = parts[0].split("_"); // Diviser les mots de l’arête
        // Créer les mots source et cible
        Word sourceWord = new Word(words[0]);
        Word targetWord = new Word(words[1]);
        double similarity = Double.parseDouble(parts[1]); // Récupérer la similarité
        edges.add(new Edge(sourceWord, similarity, targetWord)); // Ajouter l’arête à la liste
    }

    /**
     * @param fileC Chemin du fichier
     * @return MaximumSpanningTree
     * @throws IOException Charge un arbre recouvrant maximal à partir d’un fichier
     * Méthode pour le premier fichier et le MaximumSpanningTree du premier tour (soit mot de départ et mot de fin et la seule arête)
     */
    public static MaximumSpanningTree createMaximumSpanningTree(String fileC) throws IOException {
        List<Edge> edges = new ArrayList<>(); // Créer une liste pour stocker les arêtes
        Path readerPath = Path.of(fileC); // Créer un objet Path pour le fichier
        BufferedReader br = Files.newBufferedReader(readerPath); // Créer un objet BufferedReader pour lire le fichier
        br.readLine(); // Ligne 1 : "Mots de départ :"
        Word startWord = new Word(br.readLine().split(",")[0].trim()); // Ligne 2 : "voiture,561464"
        Word endWord = new Word(br.readLine().split(",")[0].trim()); // Ligne 3 : "bus,1715044"
        br.readLine(); // Ligne 4 : "Liste des mots :"
        br.readLine(); // Ligne 5 : "voiture, offset: 561464"
        br.readLine(); // Ligne 6 : "bus, offset: 1715044"
        br.readLine(); // Ligne 7 : "Distance entre les mots :"
        String[] parts = br.readLine().split(", distance:"); // Ligne 8 : "voiture_bus,0.5"
        splitWordsAndSimilarity(edges, parts); // Ajouter l’arête à la liste
        return new MaximumSpanningTree(startWord, endWord, edges, new HashSet<>()); // Retourner un nouvel objet MaximumSpanningTree
    }

    /**
     * @param file Chemin du fichier
     * @throws IOException Charge et ajoute les arêtes d’un mot à l’arbre recouvrant maximal
     *
     * Méthode pour ajouter les arêtes d’un mot à l’arbre recouvrant maximal
     */
    public void loadAddEdges(String file) throws IOException {
        Word addWord = null; // Créer un mot pour stocker le mot à ajouter
        List<Edge> edges = new ArrayList<>(); // Créer une liste pour stocker les arêtes
        Path filePath = Path.of(file); // Créer un objet Path pour le fichier
        BufferedReader br = Files.newBufferedReader(filePath); // Créer un objet BufferedReader pour lire le fichier
        String line;
        br.readLine(); // Ligne 1 : "Mots de départ :"
        br.readLine(); // Ligne 2 : "voiture,561464"
        br.readLine(); // Ligne 3 : "bus,1715044"
        br.readLine(); // Ligne 4 : "Liste des mots :"
        // Parcourir les lignes jusqu’à la ligne "Distance entre les mots :"
        while (!Objects.equals(line = br.readLine(), "Distance entre les mots :")) {
            // On récupère les mots uniquement
            String[] words = line.split(",");
            addWord = new Word(words[0]);
        }
        br.readLine(); // Ligne 5 : "Distance entre les mots :"
        // Parcourir les lignes jusqu’à la fin du fichier
        while ((line = br.readLine()) != null) {
            assert addWord != null;
            if (line.contains(addWord.word())) {
                MaximumSpanningTree.divideParts(edges, line);
            }
        }
        assert addWord != null;
        // Créer une carte pour stocker le mot à ajouter et les arêtes et appeler la méthode addWord
        Map<Word, List<Edge>> wordMap = new HashMap<>(Map.of(addWord, edges));
        addWord(wordMap);
    }

    /**
     * @param file Nom du fichier
     * @throws IOException Exporte l’arbre recouvrant maximal dans un fichier
     *
     * Méthode pour exporter l’arbre recouvrant maximal dans un fichier
     */
    public void exportMaximumSpanningTreeToFile(String file) {
        Path path = Paths.get(file); // Créer un objet Path pour le fichier
        // Créer un objet BufferedWriter pour écrire dans le fichier avec les options de création et d’écriture
        try (BufferedWriter bw = Files.newBufferedWriter(path, StandardOpenOption.CREATE, StandardOpenOption.WRITE)) {
            bw.write("MaximumSpanningTree :\n");
            bw.write("startWord : " + startWord);
            bw.newLine();
            bw.write("endWord : " + endWord);
            bw.newLine();
            bw.write("edgesMST :");
            bw.newLine();
            for (Edge edge : edgesMST) { // Parcourir chaque arête de l’arbre
                bw.write(String.format("%s_%s,%.2f", edge.sourceWord().word(), edge.targetWord(), edge.similarity()));
                bw.newLine();
            }
            bw.write("bannedWords :");
            bw.newLine();
            for (Word word : bannedWords) { // Parcourir chaque mot interdit
                bw.write(word.word());
                bw.newLine();
            }
            bw.write("EOF"); // Ajouter la fin du fichier
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * @param current Mot actuel
     * @param parent Mot parent
     * @param visited Mots visités
     * @param cycleEdges Arêtes du cycle
     * @return boolean Méthode DFS pour détecter un cycle et recueillir les arêtes du cycle
     *
     * Méthode DFS pour détecter un cycle et recueillir les arêtes du cycle
     */
    private boolean depthFirstSearch(Word current, Word parent, Set<Word> visited, List<Edge> cycleEdges) {
        // Marquer le mot actuel comme visité
        visited.add(current);

        // Parcourir toutes les arêtes de l’arbre (MST)
        for (Edge edge : edgesMST) {
            Word next = null;
            // Vérifier si l’arête courante contient le mot actuel et trouver le mot suivant
            if (edge.sourceWord().equals(current)) {
                next = edge.targetWord();
            } else if (edge.targetWord().equals(current)) {
                next = edge.sourceWord();
            }
            // Si un mot suivant est trouvé
            if (next != null) {
                // Vérifier si le mot suivant n’a pas déjà été visité
                if (!visited.contains(next)) {
                    // Continuer la recherche en profondeur à partir du mot suivant
                    // Si un cycle est trouvé, ajouter l’arête actuelle à la liste des arêtes du cycle
                    // et retourner true pour indiquer la détection d’un cycle
                    if (depthFirstSearch(next, current, visited, cycleEdges)) {
                        cycleEdges.add(edge);
                        return true;
                    }
                } else if (!next.equals(parent)) {
                    // Si le mot suivant a déjà été visité et n’est pas le parent actuel,
                    // cela signifie qu’un cycle a été détecté, ajouté l’arête actuelle au cycle.
                    cycleEdges.add(edge);
                    return true;
                }
            }
        }
        // Si aucun cycle n’a été détecté à partir du mot actuel, retourner false
        return false;
    }

    /**
     * @return List<Edge> Trouve les arêtes formant un cycle dans l’arbre recouvrant maximal
     *
     * Méthode publique pour trouver les arêtes formant un cycle
     */
    private List<Edge> findCycleEdges() {
        // Créer un ensemble pour suivre les mots visités
        Set<Word> visited = new HashSet<>();

        // Créer une liste pour stocker les arêtes formant un cycle
        List<Edge> cycleEdges = new ArrayList<>();

        // Parcourir toutes les arêtes de l’arbre (MST)
        for (Edge edge : edgesMST) {
            Word word1 = edge.sourceWord();

            // Vérifier si le mot word1 n’a pas déjà été visité
            if (!visited.contains(word1)) {
                // Appeler la méthode dfs pour rechercher un cycle à partir de word1
                if (depthFirstSearch(word1, null, visited, cycleEdges)) {
                    // Si un cycle est trouvé, sortir de la boucle
                    break;
                }
            }
        }
        // Retourner la liste des arêtes formant un cycle
        return cycleEdges;
    }

    /**
     * Méthode pour supprimer l’arête avec la similarité la plus faible dans un cycle
     */
    private void removeLowestSimilarityEdgeInCycle() {
        List<Edge> cycleEdges = findCycleEdges(); // Trouver les arêtes formant un cycle
        if (!cycleEdges.isEmpty()) { // Si un cycle est trouvé
            // Trouver la similarité minimale parmi les arêtes du cycle
            double minSimilarity = cycleEdges.stream()
                    .mapToDouble(Edge::similarity)
                    .min()
                    .orElse(Double.MAX_VALUE);

            // Filtrer les arêtes avec cette similarité minimale
            List<Edge> lowestSimilarityEdges = cycleEdges.stream()
                    .filter(edge -> edge.similarity() == minSimilarity)
                    .toList();

            // Choisir et supprimer une arête au hasard parmi celles ayant la similarité la plus faible
            if (!lowestSimilarityEdges.isEmpty()) { // Dans le cas où il y a plusieurs arêtes avec la même similarité minimale
                Random random = new Random();
                Edge edgeToRemove = lowestSimilarityEdges.get(random.nextInt(lowestSimilarityEdges.size()));
                removeEdge(edgeToRemove);
            }
        }
    }

    /**
     * @param word Mot à ajouter
     * @return boolean Vérifie si un mot a été ajouté à l’arbre recouvrant maximal
     *
     * Méthode pour connaître si le mot à ajouter a été ajouter ou non dans le MST
     */
    private boolean isWordAdded(Word word) {
        // Retourne vrai si le mot a été ajouté à l’arbre
        return edgesMST.stream()
                .anyMatch(edge -> edge.sourceWord().equals(word) || edge.targetWord().equals(word));
    }

    /**
     * @param edge Arête à vérifier
     * @return boolean Vérifie si une arête contient un mot interdit
     *
     * Méthode pour vérifier si une arête contient un mot interdit
     */
    private boolean containsBannedWord(Edge edge) {
        // Vérifier si l’arête contient un mot interdit
        Word sourceWord = edge.sourceWord();
        Word targetWord = edge.targetWord();
        return bannedWords.contains(sourceWord) || bannedWords.contains(targetWord);
    }

    /**
     * @param addWordAndEdges Mot à ajouter et ses arêtes
     *
     * Ajoute un mot à l’arbre recouvrant maximal
     */
    public void addWord(Map<Word, List<Edge>> addWordAndEdges) {
        // Créer une liste pour stocker les nouvelles arêtes à ajouter
        List<Edge> addEdges = new ArrayList<>(addWordAndEdges.values().iterator().next());
        // Créer un mot pour stocker le mot à ajouter
        Word addingWord = addWordAndEdges.keySet().iterator().next();
        // Trier les nouvelles arêtes par similarité décroissante
        addEdges.sort(Comparator.comparingDouble(Edge::similarity).reversed());

        // Parcourir chaque nouvelle arête
        for (Edge edgeToAdd : addEdges) {
            // Vérifier si l’arête contient un mot interdit
            if (containsBannedWord(edgeToAdd)) {
                continue; // Ignorer cette arête et passer à la suivante
            }

            // Ajouter l’arête au graphe
            addEdge(edgeToAdd);

            // Vérifier si l’ajout de cette arête a créé un cycle
            List<Edge> cycleEdges = findCycleEdges();

            // Si un cycle est détecté
            if (!cycleEdges.isEmpty()) {
                // Supprimer l’arête de similarité minimale dans le cycle
                removeLowestSimilarityEdgeInCycle();
            }

            // Vérifier si l’arête ajoutée possède une similarité plus faible
            // que la similarité minimale entre les nœuds de l’arbre
            double minSimilarityInMST = this.getEdgesMST().stream()
                    .mapToDouble(Edge::similarity)
                    .min()
                    .orElse(Double.MIN_VALUE);

            if (edgeToAdd.similarity() < minSimilarityInMST) {
                // Inutile de continuer pour les arêtes restantes
                break;
            }
        }

        // Ajouter le mot à la liste des mots interdits
        if (!isWordAdded(addingWord)) {
            bannedWords.add(addingWord);
        }
    }
}
