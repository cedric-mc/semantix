#include <stdio.h>
#include "fonctions.h"

int main(int argc, char *argv[]) {
    if (argc < 3) {
        fprintf(stderr, "Usage: %s model_file index_file word1 word2 ...\n", argv[0]);
        exit(EXIT_FAILURE);
    }

    const char *modelFile = argv[1];
    const char *indexFile = argv[2];
    int numWords = argc - 3;
    char **words = argv + 3;

    new_game(modelFile, indexFile, numWords, words);

    return 0;
}