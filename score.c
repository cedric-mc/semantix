#include <stdio.h>
#include <stdlib.h>
#include <string.h>

typedef char Element;
typedef struct node {
    Element elem;
    int offset;
    struct node* firstChild;
    struct node* nextSibling;
} Node;

typedef Node* CSTree;

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

void insertWordWithOffset(CSTree* root, const char* word, int offset) {
    CSTree currentNode = *root;
    int i;

    for (i = 0; word[i] != '\0'; i++) {
        char currentChar = word[i];
        CSTree child = currentNode->firstChild;

        while (child != NULL && child->elem != currentChar) {
            child = child->nextSibling;
        }

        if (child == NULL) {
            currentNode->firstChild = newCSTree(currentChar, -1, NULL, currentNode->firstChild);
            currentNode = currentNode->firstChild;
        } else {
            currentNode = child;
        }
    }

    currentNode->offset = offset;
}

void insertWordsFromFile(CSTree* root, const char* filename) {
    FILE* file = fopen(filename, "r");
    if (file == NULL) {
        fprintf(stderr, "Erreur lors de l'ouverture du fichier %s.\n", filename);
        exit(EXIT_FAILURE);
    }

    char word[100];  // Assumption: Maximum word length is 100 characters
    int offset = 0;

    while (fscanf(file, "%99s", word) == 1) {
        insertWordWithOffset(root, word, offset);
        offset++;
    }

    fclose(file);
}

void exportCSTreeToFile(CSTree root, FILE* file) {
    if (root == NULL) {
        return;
    }

    fprintf(file, "%c %d\n", root->elem, root->offset);

    exportCSTreeToFile(root->firstChild, file);
    exportCSTreeToFile(root->nextSibling, file);
}

CSTree findNodeByWord(CSTree root, const char* word) {
    CSTree currentNode = root;
    int i;

    for (i = 0; word[i] != '\0'; i++) {
        char currentChar = word[i];
        CSTree child = currentNode->firstChild;

        while (child != NULL && child->elem != currentChar) {
            child = child->nextSibling;
        }

        if (child == NULL) {
            // Le mot n'est pas trouvé dans l'arbre
            return NULL;
        } else {
            currentNode = child;
        }
    }

    // Vérifier si le mot complet a été trouvé
    if (currentNode->elem == '\0') {
        return currentNode;
    } else {
        // Le mot n'est pas trouvé dans l'arbre
        return NULL;
    }
}

int main() {
    CSTree root = newCSTree('\0', -1, NULL, NULL);

    // Exemple d'insertion de mots avec leurs offsets à partir d'un fichier texte
    insertWordsFromFile(&root, "liste_mots.txt");

    // Exemple d'exportation de l'arbre dans un fichier .lex
    FILE* lexFile = fopen("arbre.lex", "w");
    if (lexFile == NULL) {
        fprintf(stderr, "Erreur lors de l'ouverture du fichier .lex.\n");
        return EXIT_FAILURE;
    }
    exportCSTreeToFile(root, lexFile);
    fclose(lexFile);

    // Libérer la mémoire utilisée par l'arbre

    return 0;
}
