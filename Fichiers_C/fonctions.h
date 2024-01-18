#ifndef FONCTIONS_H
#define FONCTIONS_H

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <limits.h>
#include <math.h>
#include <assert.h>

extern const long long max_size;  // longueur maximale des chaînes
extern const long long max_w;       // longueur maximal des mots
extern const long long N;

extern const int NONE ;

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

CSTree newCSTree(Element elem, int offset, CSTree firstChild, CSTree nextSibling);

int size(CSTree t);

int nbChildren(CSTree t);

int nChildren(CSTree t);

void fill_array_cells(StaticTree* st, CSTree t, unsigned int index_for_t, int nSiblings, int* reserved_cells);

StaticTree exportStaticTree(CSTree t);

void insertWordWithOffset(CSTree* root, const char* word, int offset);

void exportToFile(const char* filename, StaticTree* st);

int findOffsetInStaticTree(StaticTree* st, const char* word);

CSTree build_lex_index(const char* filename);

int verifyCSTree(CSTree root, const char* word);

int dictionary_lookup(const char* lexFileName, const char* word);

typedef struct {
  long long words;
  long long size;
  char *vocab;
  float *M;
} WordModel;

WordModel* load_model(const char *file_name);

float sem_similarity(const WordModel *model, const char *word1, const char *word2);

void free_model(WordModel *model);

typedef struct {
    int lenS;
    int lenT;
    int * tab;
} LevArray;

LevArray init(int lenS, int lenT);

void set(LevArray a, int indexS, int indexT, int val);

int get(LevArray a, int indexS, int indexT);

int levenshtein(char * S, char * T);

double lev_similarity(char *S, char *T);

void extractWordsAndOffsets(const char *inputFileName, const char *outputFileName);

void new_game(const char *modelFile, int numWords, char *words[]);

void add_word(const char *modelFile, const char *newWord);

void print_authors();

void run_minimal_test();
#endif
