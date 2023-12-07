#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <limits.h>

typedef char Element;

typedef struct node{
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
} ArrayCell;
typedef struct {
    ArrayCell* nodeArray;
    unsigned int nNodes;
} StaticTree;

#define NONE -1 


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

int size(CSTree t){
    if (t == NULL){
        return 0;
    }
    return 1+size(t->firstChild)+size(t->nextSibling);
}

int nbChildren(CSTree t) {
    if (t == NULL){
        return 0;
    }
    return 1+nbChildren(t->nextSibling);
}

int nChildren(CSTree t) {
    if (t == NULL){
        return 0;
    }
    if (t->firstChild == NULL){
        return 0;
    }
    return nbChildren(t->firstChild);
}

void fill_array_cells(StaticTree* st, CSTree t, int index_for_t, int nSiblings, int* reserved_cells) {
    int firstChild_index;
    if (t->firstChild != NULL) {
        firstChild_index = *reserved_cells;
    } else {
        firstChild_index = NONE;
    }

    st->nodeArray[index_for_t].elem = t->elem;
    st->nodeArray[index_for_t].offset = t->offset; // Nouveau champ pour stocker l'offset
    st->nodeArray[index_for_t].nSiblings = nSiblings;
    st->nodeArray[index_for_t].firstChild = firstChild_index;

    *reserved_cells += nChildren(t);

    if (t->nextSibling != NULL) {
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


int main() {
    CSTree root = newCSTree('\0', -1, NULL, NULL);

    insertWordWithOffset(&root, "word", 42);
    insertWordWithOffset(&root, "work", 87);
    insertWordWithOffset(&root, "walou", 50);


    StaticTree root2 = exportStaticTree(root);


    printf("Arbre Lexicographique :\n");
    printCSTree(root, 0);

    return 0;
}