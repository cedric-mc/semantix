#include <stdio.h>
#include "fonctions.h"
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>

const long long max_size = 2000;  // longueur maximale des chaînes
const long long max_w = 50;       // longueur maximal des mots
const long long N = 40;

const int NONE = -1 ;

CSTree newCSTree(Element elem, int offset, CSTree firstChild, CSTree nextSibling) {
    CSTree t = malloc(sizeof(Node));
    if (t == NULL) {
        exit(EXIT_FAILURE);
    }
    t->elem = elem;
    t->offset = offset;
    t->firstChild = firstChild;
    t->nextSibling = nextSibling;
    return t;
}

int size(CSTree t) {
    if (t == NULL) {
        return 0;
    }
    return 1 + size(t->firstChild) + size(t->nextSibling);
}

int nbChildren(CSTree t) {
    if (t == NULL) {
        return 0;
    }
    return 1 + nbChildren(t->nextSibling);
}

int nChildren(CSTree t) {
    if (t == NULL) {
        return 0;
    }
    if (t->firstChild == NULL) {
        return 0;
    }
    return nbChildren(t->firstChild);
}

void fill_array_cells(StaticTree* st, CSTree t, unsigned int index_for_t, int nSiblings, int* reserved_cells) {
    unsigned int firstChild_index;
    if (t->firstChild != NULL) {
        firstChild_index = *reserved_cells;
    } else {
        firstChild_index = NONE;
    }

    st->nodeArray[index_for_t].elem = t->elem;
    st->nodeArray[index_for_t].offset = t->offset;
    st->nodeArray[index_for_t].nSiblings = nSiblings;

    if (t->offset != -1) {
        // Pour les nœuds avec un offset, lier correctement en tant que premier enfant
        st->nodeArray[index_for_t].firstChild = *reserved_cells;
    } else {
        st->nodeArray[index_for_t].firstChild = firstChild_index;
    }

    st->nodeArray[index_for_t].nextSibling = NONE;  // Ajustement pour commencer à NONE

    *reserved_cells += nChildren(t);

    if (t->nextSibling != NULL) {
        st->nodeArray[index_for_t].nextSibling = index_for_t + 1;
        fill_array_cells(st, t->nextSibling, index_for_t + 1, nSiblings - 1, reserved_cells);
    }
    if (t->firstChild != NULL) {
        fill_array_cells(st, t->firstChild, firstChild_index, nChildren(t) - 1, reserved_cells);
    }
}

// Crée un arbre statique avec le même contenu que t.
StaticTree exportStaticTree(CSTree t) {
    StaticTree st = {NULL, 0};
    int reserved_cells = 0;

    st.nNodes = size(t);
    st.nodeArray = malloc(st.nNodes * sizeof(ArrayCell));
    reserved_cells = nbChildren(t);

    fill_array_cells(&st, t, 0, reserved_cells - 1, &reserved_cells);

    if (reserved_cells != st.nNodes && t != NULL) {
        printf("Erreur lors de la création de l'arbre statique, taille finale incorrecte\n");
        exit(EXIT_FAILURE);
    }

    return st;
}


void insertWordWithOffset(CSTree* root, const char* word, int offset) {
    CSTree currentNode = *root;

    for (int i = 0; i <= strlen(word); i++) {
        // Vérifie si le caractère existe déjà comme premier enfant du nœud actuel
        CSTree child = currentNode->firstChild;
        while (child != NULL && child->elem != word[i]) {
            child = child->nextSibling;
        }

        if (child == NULL) {
            // Le caractère n'existe pas comme premier enfant, l'ajouter
            CSTree newChild = newCSTree(word[i], -1, NULL, NULL);
            newChild->nextSibling = currentNode->firstChild;
            currentNode->firstChild = newChild;
            currentNode = newChild;
        } else {
            // Le caractère existe déjà, passer au prochain nœud
            currentNode = child;
        }
    }

    // Atteint la fin du mot, mettre à jour l'offset
    currentNode->offset = offset;
}

void exportToFile(const char* filename, StaticTree* st) {
    FILE* file = fopen(filename, "wb");
    if (file == NULL) {
        perror("Erreur lors de l'ouverture du fichier");
        exit(EXIT_FAILURE);
    }

    // Écrire le header avec la taille du tableau
    fwrite(&(st->nNodes), sizeof(unsigned int), 1, file);

    // Écrire le tableau complet
    fwrite(st->nodeArray, sizeof(ArrayCell), st->nNodes, file);

    fclose(file);
}

int findOffsetInStaticTree(StaticTree* st, const char* word) {
    unsigned int currentIndex = 1;
    unsigned int childIndex = st->nodeArray[currentIndex].firstChild;
    int x = 1;
    for (int i = 0; i <= strlen(word); i++) {
        char currentChar = word[i];
        x = 1;
        // Recherche du caractère dans les enfants du nœud actuel
        while (x) {
            if (st->nodeArray[currentIndex].elem == currentChar) {
                // Si le caractère est trouvé et le mot est terminé, retourner l'offset
                if ( st->nodeArray[childIndex].offset != -1) {
                    return st->nodeArray[childIndex].offset;
                // Si on arrive à la fin du mot et qu'il est correct on va checher son offset parmi les
                //frères de son enfant
                }else if (i == strlen(word) - 1){
                    currentIndex = childIndex;
                    childIndex = st->nodeArray[currentIndex].firstChild;
                    //tant qu'on ne trouve pas l'offset du mot on passe au frère
                    while (st->nodeArray[currentIndex].offset == -1){
                        currentIndex = st->nodeArray[currentIndex].nextSibling;
                        childIndex = st->nodeArray[currentIndex].firstChild;
                    }
                    //on retourne l'offset du mot trouvé
                    return st->nodeArray[currentIndex].offset;
                }else{
                    currentIndex = childIndex;
                    childIndex = st->nodeArray[currentIndex].firstChild;
                    x = 0;
                }
            }else{
                if (st->nodeArray[currentIndex].nSiblings != 0) {
                    currentIndex = st->nodeArray[currentIndex].nextSibling;
                    childIndex = st->nodeArray[currentIndex].firstChild;
                }else{
                    return -1;
                }
            }
        }
    }
    // Le mot n'a pas été trouvé
    return -1;
}

//fonction qui crée l'arbre avec les index dans le fichier en paramètre
CSTree build_lex_index(const char* filename) {
    FILE* file = fopen(filename, "r");
    if (file == NULL) {
        perror("Erreur lors de l'ouverture du fichier");
        exit(EXIT_FAILURE);
    }

    CSTree root = newCSTree('\0', -1, NULL, NULL);
    char word[256];  // Ajustez la taille selon vos besoins
    int offset;

    while (fscanf(file, "%s %d", word, &offset) == 2) {
        //printf("Mot: %s, Offset: %d\n", word, offset);
        insertWordWithOffset(&root, word, offset);
    }

    fclose(file);
    return root;
}

//fonction qui verifie si un mot est dans un CSTree
int verifyCSTree(CSTree root, const char* word) {
    CSTree currentNode = root;
    for (int i = 0; i <= strlen(word); i++) {
        CSTree child = currentNode->firstChild;
        while (child != NULL && child->elem != word[i]) {
            child = child->nextSibling;
        }
        if (child == NULL) {
            return 0;
        }
        currentNode = child;
    }
    return 1;
}

//fonction qui cherche un mot dans un fichier
int dictionary_lookup(const char* lexFileName, const char* word) {
    FILE* lexFile = fopen(lexFileName, "rb");
    if (lexFile == NULL) {
        perror("Erreur lors de l'ouverture du fichier .lex");
        exit(EXIT_FAILURE);
    }

    // Lire la taille du tableau depuis le fichier
    unsigned int nNodes;
    fread(&nNodes, sizeof(unsigned int), 1, lexFile);

    // Allouer de la mémoire pour le tableau
    ArrayCell* nodeArray = malloc(nNodes * sizeof(ArrayCell));
    if (nodeArray == NULL) {
        perror("Erreur d'allocation de mémoire");
        fclose(lexFile);
        exit(EXIT_FAILURE);
    }

    // Lire le tableau depuis le fichier
    fread(nodeArray, sizeof(ArrayCell), nNodes, lexFile);

    fclose(lexFile);

    // Recherche de l'offset du mot dans le tableau
    int offset = findOffsetInStaticTree(&(StaticTree){nodeArray, nNodes}, word);

    // Libérer la mémoire allouée pour le tableau
    free(nodeArray);

    return offset;
}

// Fonction pour charger le modèle en mémoire
WordModel* load_model(const char *file_name) {
  FILE *f;
  WordModel *model = (WordModel *)malloc(sizeof(WordModel));
  if (model == NULL) {
    printf("Memory allocation error\n");
    return NULL;
  }

  f = fopen(file_name, "rb");
  if (f == NULL) {
    printf("Input file not found\n");
    free(model);
    return NULL;
  }

  fscanf(f, "%lld", &model->words);
  fscanf(f, "%lld", &model->size);

  model->vocab = (char *)malloc((long long)model->words * max_w * sizeof(char));
  model->M = (float *)malloc((long long)model->words * (long long)model->size * sizeof(float));

  if (model->vocab == NULL || model->M == NULL) {
    printf("Memory allocation error\n");
    fclose(f);
    free(model->vocab);
    free(model->M);
    free(model);
    return NULL;
  }

  for (long long b = 0; b < model->words; b++) {
    long long a = 0;
    while (1) {
      model->vocab[b * max_w + a] = fgetc(f);
      if (feof(f) || (model->vocab[b * max_w + a] == ' ')) break;
      if ((a < max_w) && (model->vocab[b * max_w + a] != '\n')) a++;
    }
    model->vocab[b * max_w + a] = 0;
    for (a = 0; a < model->size; a++) fread(&model->M[a + b * model->size], sizeof(float), 1, f);
    float len = 0;
    for (a = 0; a < model->size; a++) len += model->M[a + b * model->size] * model->M[a + b * model->size];
    len = sqrt(len);
    for (a = 0; a < model->size; a++) model->M[a + b * model->size] /= len;
  }
  fclose(f);

  return model;
}

// Fonction pour calculer la distance cosinus entre deux mots
float sem_similarity(const WordModel *model, const char *word1, const char *word2) {
  long long idx1 = -1;
  long long idx2 = -1;

  // Recherche des indices des mots dans le vocabulaire
  for (long long a = 0; a < model->words; a++) {
    if (!strcmp(&model->vocab[a * max_w], word1)) {
      idx1 = a;
      break;
    }
  }
  for (long long a = 0; a < model->words; a++) {
    if (!strcmp(&model->vocab[a * max_w], word2)) {
      idx2 = a;
      break;
    }
  }

  if (idx1 == -1 || idx2 == -1) {
    printf("One or both words not found in the vocabulary\n");
    return -1;
  }

  // Calcul de la distance cosinus entre les deux mots
  float dist = 0;
  for (long long a = 0; a < model->size; a++) {
    dist += model->M[a + idx1 * model->size] * model->M[a + idx2 * model->size];
  }

  return dist*100;
}

// Fonction pour libérer la mémoire du modèle
void free_model(WordModel *model) {
  free(model->vocab);
  free(model->M);
  free(model);
}


//minimum de deux entiers
int min(int a, int b) {
    return a < b ? a : b;
}

//initialiser un tableau pour des chaînes d'une taille donnée
LevArray init(int lenS, int lenT) {
    LevArray a;
    //on stocke les dimensions
    a.lenS = lenS;
    a.lenT = lenT;
    //allocation d'un tableau (1D) de lenS*lenT entiers
    a.tab = malloc(lenS * lenT * sizeof(int));
    //on vérifie que l'allocation s'est bien passée
    assert(a.tab != NULL);
    return a;

}

//set: insérer une valeur dans le tableau
void set(LevArray a, int indexS, int indexT, int val) {
    //vérification des indices
    assert(indexS >= 0 && indexS < a.lenS && indexT >= 0 && indexT < a.lenT);
    assert(a.tab!=NULL);
    a.tab[indexT * a.lenS + indexS] = val;
}

int get(LevArray a, int indexS, int indexT) {
    if (indexS == -1){
        return indexT + 1;
    }
    if (indexT == -1){
        return indexT + 1;
    }
    return a.tab[indexT * a.lenS + indexS];
}

int levenshtein(char * S, char * T) {
    //utiliser strlen pour obtenir la longueur des chaˆınes
    int sizeS = strlen( S );
    int sizeT = strlen( T );
    //cr ́eer un tableau du type LevArray
    LevArray a = init(sizeS, sizeT);
    //parcourir le tableau en le remplissant au fur et `a mesure
    for (int i=0; i<sizeS; i++) {
        for (int j=0; j<sizeT; j++) {
            int val;
            if (S[i]==T[j]) {
                val = get(a, i-1, j-1);
            } else {
                val = get(a, i-1, j-1) + 1;
            }
            val = min(val, get(a, -1, j) +1);

            val = min(val, get(a, i, j-1) + 1);

            set(a, i, j, val);
        }
    }
    //r ́ecup ́erer la distance `a la fin du tableau
    return get(a, sizeS-1, sizeT-1);
}

//fonction qui calcule la similarité orthographique
double lev_similarity(char *S, char *T) {
    int levenshtein_distance = levenshtein(S, T);
    int max_length = (strlen(S) > strlen(T)) ? strlen(S) : strlen(T);
    double similarity = (1.0 - ((double)levenshtein_distance / max_length)) * 100.0;
    return similarity;
}

//fonction qui prend un modèle binaire et renvoie un fichier txt des mots et de leur offset
void extractWordsAndOffsets(const char *inputFileName, const char *outputFileName) {
    FILE *inputFile, *outputFile;

    inputFile = fopen(inputFileName, "rb");

    if (inputFile == NULL) {
        printf("Input file not found\n");
        return;
    }

    outputFile = fopen(outputFileName, "w");

    if (outputFile == NULL) {
        printf("Cannot create output file\n");
        fclose(inputFile);
        return;
    }

    long long words, size;
    char *vocab;
    float *M;

    // Lecture du nombre total de mots et de la dimension de l'espace vectoriel
    fscanf(inputFile, "%lld", &words);
    fscanf(inputFile, "%lld", &size);

    // Allocation de mémoire pour le vocabulaire et la matrice
    vocab = (char *)malloc((long long)words * max_w * sizeof(char));
    M = (float *)malloc((long long)words * (long long)size * sizeof(float));

    // Ignorer le reste de la ligne après la lecture de size
    while (fgetc(inputFile) != '\n');

    // Lecture du vocabulaire et des vecteurs depuis le fichier binaire
    for (long long b = 0; b < words; b++) {
        // Lecture du mot
        fscanf(inputFile, "%s%c", &vocab[b * max_w], &M[0]);

        // Lecture du vecteur et normalisation
        for (long long a = 0; a < size; a++) fread(&M[a + b * size], sizeof(float), 1, inputFile);

        // Écriture du mot et de son offset dans le fichier de sortie
        fprintf(outputFile, "%s %lld\n", &vocab[b * max_w], ftell(inputFile));
    }

    // Libération de la mémoire
    free(vocab);
    free(M);

    // Fermeture des fichiers
    fclose(inputFile);
    fclose(outputFile);
}

// Fonction pour créer un nouveau fichier de partie
void new_game(const char *modelFile, int numWords, char *words[], int isFirstExecution) {
    static WordModel *model = NULL;
    static CSTree root = NULL;
    static StaticTree root2 = {0};

    char *indexFile = "arbre.lex";
    // Charger le modèle word2vec

    model = load_model(modelFile);
    if (model == NULL) {
        exit(EXIT_FAILURE);
    }

    extractWordsAndOffsets(modelFile, "words.txt");

    // Exécuter cette partie de code uniquement lors de la première exécution
    if (isFirstExecution) {
        root = newCSTree('\0', -1, NULL, NULL);
        root = build_lex_index("words.txt");
        root2 = exportStaticTree(root);
        exportToFile(indexFile, &root2);
    }else{
        root = root = build_lex_index("words.txt");
    }

    //Mettre les mots dans un fichier
    FILE *file_word = fopen("word_game.txt", "w");
    if (file_word == NULL) {
        perror("Erreur lors de la création du fichier de mots");
        exit(EXIT_FAILURE);
    }
    for (int i = 0; i < numWords; ++i) {
        fprintf(file_word, "%s\n", words[i]);
    }

    // Créer le fichier de partie
    mode_t old_umask = umask(000);

    int fd = open("fichier_du_jeu.txt", O_WRONLY | O_CREAT | O_TRUNC, 0666);

    if (fd == -1) {
        perror("Failed to open file for writing");
        umask(old_umask);
        exit(EXIT_FAILURE);
    }

    FILE *gameFile = fdopen(fd, "w");
    if (gameFile == NULL) {
        perror("Erreur lors de la création du fichier de partie");
        close(fd);
        exit(EXIT_FAILURE);
    }

    // Écrire la liste des mots de départ dans le fichier de partie
    fprintf(gameFile, "Liste des mots :\n");
    for (int i = 0; i < numWords; ++i) {
        fprintf(gameFile, "%s\n", words[i]);
    }

    // Écrire l'offset de chaque mot dans le dictionnaire
    int offset = -1;
    fprintf(gameFile, "\nOffsets dans le dictionnaire :\n");
    for (int i = 0; i < numWords; ++i) {
        // Utiliser l'index lexicographique pour trouver l'offset
        // (à adapter selon votre structure d'index)
        if (verifyCSTree(root, words[i]) != 0){
            offset = dictionary_lookup(indexFile, words[i]);
        }else{
            offset = -1;
        }
        fprintf(gameFile, "%s: %d\n", words[i], offset);
    }
    // Calculer et écrire la distance entre chaque paire de mots
    fprintf(gameFile, "\nDistances entre les paires de mots :\n");
    for (int i = 0; i < numWords; ++i) {
        for (int j = i + 1; j < numWords; ++j) {
            if (sem_similarity(model, words[i], words[j]) == -1){
                float distance = NONE;
            }else{
                float distance = fmaxf(sem_similarity(model, words[i], words[j]), lev_similarity(words[i], words[j]));
                fprintf(gameFile, "%s - %s : %.2f\n", words[i], words[j], distance);
            }
        }
    }

    // Fermer le fichier de partie
    free_model(model);
    fclose(gameFile);
    umask(old_umask);
}

// Fonction principale pour ajouter un mot à un fichier de partie existant
void add_word(const char *modelFile, const char *newWord) {
    char *indexFile = "arbre.lex";
    mode_t old_umask = umask(000);
    int fd = open("word_game.txt", O_RDWR | O_CREAT, 0666);
    if (fd == -1) {
        perror("Failed to open file for writing");
        umask(old_umask);
        exit(EXIT_FAILURE);
    }

    FILE *fichier = fdopen(fd,"r");
    if (fichier == NULL) {
        perror("Erreur lors de l'ouverture du fichier");
        close(fd);
        exit(EXIT_FAILURE);
    }

    // Utilisation de malloc pour allouer dynamiquement la mémoire pour le tableau mots
    char **mots = (char **)malloc(max_size * sizeof(char *));
    if (mots == NULL) {
        perror("Erreur lors de l'allocation de mémoire");
        exit(EXIT_FAILURE);
    }

    for (int i = 0; i < max_size; i++) {
        mots[i] = (char *)malloc(max_w * sizeof(char));
        if (mots[i] == NULL) {
            perror("Erreur lors de l'allocation de mémoire");
            exit(EXIT_FAILURE);
        }
    }

    int nombreMots = 0;

    // Lire les mots du fichier et les stocker dans le tableau
    while (fgets(mots[nombreMots], max_w, fichier) != NULL) {
        mots[nombreMots][strlen(mots[nombreMots]) - 1] = '\0';  // Supprimer le caractère de saut de ligne
        nombreMots++;

        if (nombreMots >= max_size) {
            fprintf(stderr, "Trop de mots dans le fichier\n");
            exit(EXIT_FAILURE);
        }
    }
    fclose(fichier);
    umask(old_umask);
    // Ajouter le mot supplémentaire à la fin du tableau
    if (nombreMots < max_size) {
        strcpy(mots[nombreMots], newWord);
        nombreMots++;
    } else {
        fprintf(stderr, "Trop de mots pour ajouter un mot supplémentaire\n");
        exit(EXIT_FAILURE);
    }
    new_game(modelFile, nombreMots, mots, 0);

    // Libérer la mémoire allouée dynamiquement
    for (int i = 0; i < max_size; i++) {
        free(mots[i]);
    }
    free(mots);
}

// Fonction pour afficher les auteurs
void print_authors() {
    printf("Auteurs : BA Mamadou - REKKAB Abdelnour - SOUSA Vincent - MALOUM Elyas\n");
}

// Fonction de test minimaliste
void run_minimal_test() {
    printf("\nSimilarité orthographique entre singe et singes : \n");
    float distance = lev_similarity("singe", "singes");
    printf("%.2f\n", distance);
}