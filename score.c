#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <limits.h>

typedef char Element;

typedef struct node {
    Element elem;
    int offset;
    struct node* firstChild;
    struct node* nextSibling;
} Node;
typedef Node* CSTree;

typedef struct {
    Element elem;
    int offset;
    unsigned int firstChild;
    unsigned int nSiblings;
    unsigned int nextSibling; 
} ArrayCell;

typedef struct {
    ArrayCell* nodeArray;
    unsigned int nNodes;
} StaticTree;

#define NONE -1 
#define max_size 2000

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

void printCSTree(CSTree root, int depth) {
    if (root == NULL) {
        return;
    }

    for (int i = 0; i < depth; i++) {
        printf("  ");
    }

    printf("%c (Offset: %d)\n", root->elem, root->offset);

    printCSTree(root->firstChild, depth + 1);
    printCSTree(root->nextSibling, depth);
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

void printStaticTree(StaticTree* st) {
    printf("Arbre Statique :\n");

    for (unsigned int i = 0; i < st->nNodes; i++) {
        printf("Index: %u, Element: %c, Offset: %d, First Child: %u, Siblings: %u\n",
               i, st->nodeArray[i].elem, st->nodeArray[i].offset,
               st->nodeArray[i].firstChild, st->nodeArray[i].nSiblings);
    }
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

CSTree createCSTreeFromFile(const char* filename) {
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

void verifyCSTree(CSTree root, const char* word) {
    CSTree currentNode = root;
    for (int i = 0; i <= strlen(word); i++) {
        CSTree child = currentNode->firstChild;
        while (child != NULL && child->elem != word[i]) {
            child = child->nextSibling;
        }
        if (child == NULL) {
            printf("Le mot \"%s\" n'est pas dans l'arbre.\n", word);
            return;
        }
        currentNode = child;
    }

    printf("Le mot \"%s\" est présent dans l'arbre avec l'offset %d.\n", word, currentNode->offset);
}

int findOffsetInFile(const char* lexFileName, const char* word) {
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

int main() {
    CSTree root = newCSTree('\0', -1, NULL, NULL);

    //insertWordWithOffset(&root, "word", 42);
    //insertWordWithOffset(&root, "work", 87);
    //insertWordWithOffset(&root, "walou", 50);
    char *mot = "températures";
    root = createCSTreeFromFile("words.txt");
    verifyCSTree(root, mot);
    //printCSTree(root,0);
    StaticTree root2 = exportStaticTree(root);
    exportToFile("arbre.lex", &root2);

    //printStaticTree(&root2);
     //Recherche de l'offset d'un mot dans le fichier .lex
    int offset = findOffsetInFile("arbre.lex", mot);
    if (offset != -1) {
        printf("Le mot a un offset de %d\n", offset);
    } else {
        printf("Le mot n'est pas présent dans l'arbre\n");
    }
    

    return 0;
}
